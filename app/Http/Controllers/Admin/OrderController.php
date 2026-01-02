<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // List all orders
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items'])->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by order number, customer name, or transaction ID
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    // View order details
    public function show($id)
    {
        $order = Order::with(['customer', 'items.product'])->findOrFail($id);
        $shiprocketStatus = null;

        if ($order->shiprocket_shipment_id) {
            $statusResult = $this->syncShiprocketStatus($order);
            if ($statusResult['success']) {
                $shiprocketStatus = $statusResult['current_status'];
            }
        }

        return view('admin.orders.show', compact('order', 'shiprocketStatus'));
    }

    // Update order status
    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled,returned'
            ]);

            $order = Order::with('items')->findOrFail($id);
            $oldStatus = $order->status;
            $newStatus = $request->status;
            $shiprocketCancelled = false;
            $shiprocketError = null;

            // Logic to restore stock if order is cancelled or returned
            if (in_array($newStatus, ['cancelled', 'returned']) && !in_array($oldStatus, ['cancelled', 'returned'])) {
                foreach ($order->items as $item) {
                    $item->product->increment('stock_quantity', $item->quantity);
                }

                // If cancelled by admin, also cancel in Shiprocket
                if ($newStatus === 'cancelled' && $order->shiprocket_order_id) {
                    $shiprocketService = new \App\Services\ShiprocketService();
                    $srResult = $shiprocketService->cancelOrder($order->shiprocket_order_id);
                    if ($srResult['success']) {
                        $shiprocketCancelled = true;
                    } else {
                        $shiprocketError = $srResult['message'] ?? 'Unknown Shiprocket error';
                    }
                }
            }
            // Logic to re-deduct stock if order is restored from cancelled/returned (optional but good)
            elseif (!in_array($newStatus, ['cancelled', 'returned']) && in_array($oldStatus, ['cancelled', 'returned'])) {
                foreach ($order->items as $item) {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            }

            $order->status = $newStatus;
            $order->save();

            $msg = 'Order status updated successfully!';
            if ($newStatus === 'cancelled' && $order->shiprocket_order_id) {
                if ($shiprocketCancelled) {
                    $msg .= ' Also cancelled in Shiprocket Dashboard.';
                } else {
                    return redirect()->back()->with('warning', 'Order cancelled locally, but Shiprocket cancellation failed: ' . $shiprocketError);
                }
            }

            return redirect()->back()->with('success', $msg);
        } catch (\Exception $e) {
            Log::error('Order Status Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update order status.');
        }
    }

    // Update payment status
    public function updatePaymentStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'payment_status' => 'required|in:pending,completed,failed,refunded'
            ]);

            $order = Order::findOrFail($id);
            $order->payment_status = $request->payment_status;
            $order->save();

            return redirect()->back()->with('success', 'Payment status updated successfully!');
        } catch (\Exception $e) {
            Log::error('Payment Status Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update payment status.');
        }
    }

    // Delete order
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);

            // Delete order items first
            $order->items()->delete();

            // Delete the order
            $order->delete();

            DB::commit();

            return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Order Delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete order.');
        }
    }

    // Dashboard statistics
    public function statistics()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'shipped_orders' => Order::where('status', 'shipped')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::where('payment_status', 'completed')->sum('total'),
            'pending_payment' => Order::where('payment_status', 'pending')->sum('total'),
        ];

        return $stats;
    }

    public function pushToShiprocket($id)
    {
        $order = Order::with(['customer', 'items.product'])->findOrFail($id);

        $shiprocketService = new \App\Services\ShiprocketService();
        $result = $shiprocketService->createOrder($order);

        if ($result['success']) {
            $data = $result['data'];
            $updateData = [
                'shiprocket_order_id' => $data['order_id'],
                'shiprocket_shipment_id' => $data['shipment_id'],
                'status' => 'processing'
            ];

            // If AWB is returned immediately in the response
            if (isset($data['awb_code']) && $data['awb_code']) {
                $updateData['awb_code'] = $data['awb_code'];
                $updateData['tracking_url'] = "https://shiprocket.co/tracking/" . $data['awb_code'];
            }

            $order->update($updateData);

            // 1. Try to assign AWB immediately
            $awbResult = $shiprocketService->assignAwb($data['shipment_id']);
            if ($awbResult['success']) {
                $order->update([
                    'awb_code' => $awbResult['awb_code'],
                    'tracking_url' => "https://shiprocket.co/tracking/" . $awbResult['awb_code']
                ]);
            }

            // 2. Try to fetch tracking info/status
            $this->syncShiprocketStatus($order);

            return redirect()->back()->with('success', 'Order pushed to Shiprocket successfully! Tracking info will appear shortly.');
        }

        return redirect()->back()->with('error', 'Shiprocket Error: ' . $result['message']);
    }

    public function updateShiprocketStatus($id)
    {
        $order = Order::findOrFail($id);
        if (!$order->shiprocket_shipment_id) {
            return redirect()->back()->with('error', 'Order not pushed to Shiprocket yet.');
        }

        $status = $this->syncShiprocketStatus($order);

        if ($status['success']) {
            return redirect()->back()->with('success', 'Shiprocket status updated! Current Status: ' . $status['current_status']);
        }

        return redirect()->back()->with('error', 'Shiprocket Error: ' . $status['message']);
    }

    private function syncShiprocketStatus($order)
    {
        $shiprocketService = new \App\Services\ShiprocketService();

        // 1. If AWB is missing, try to assign it first
        if (!$order->awb_code && $order->shiprocket_shipment_id) {
            $awbResult = $shiprocketService->assignAwb($order->shiprocket_shipment_id);
            if ($awbResult['success']) {
                $order->update([
                    'awb_code' => $awbResult['awb_code'],
                    'tracking_url' => "https://shiprocket.co/tracking/" . $awbResult['awb_code']
                ]);
            } else {
                // Return failure if AWB assignment was attempted but failed
                return ['success' => false, 'message' => 'AWB Auto-Assignment failed: ' . $awbResult['message']];
            }
        }

        // 2. Fetch tracking details
        $result = $shiprocketService->getTrackingStatus($order->shiprocket_shipment_id);

        if ($result['success']) {
            $data = $result['data'];

            if (isset($data['tracking_data']['shipment_track'][0])) {
                $track = $data['tracking_data']['shipment_track'][0];
                $awb = $track['awb_code'] ?? null;
                if ($awb) {
                    $order->awb_code = $awb;
                    $order->tracking_url = "https://shiprocket.co/tracking/" . $awb;
                }

                $srStatus = $track['current_status'] ?? null;
                if ($srStatus) {
                    $statusMap = [
                        'PICKED UP' => 'shipped',
                        'IN TRANSIT' => 'shipped',
                        'OUT FOR DELIVERY' => 'shipped',
                        'DELIVERED' => 'delivered',
                        'CANCELLED' => 'cancelled',
                        'CANCELED' => 'cancelled',
                        'RETURNED' => 'returned',
                        'SHIPPED' => 'shipped',
                        'READY TO SHIP' => 'processing',
                        'MANIFESTED' => 'processing',
                        'NEW' => 'processing',
                    ];

                    if (isset($statusMap[strtoupper($srStatus)])) {
                        $newStatus = $statusMap[strtoupper($srStatus)];
                        $oldStatus = $order->status;

                        if ($newStatus !== $oldStatus) {
                            if (in_array($newStatus, ['cancelled', 'returned']) && !in_array($oldStatus, ['cancelled', 'returned'])) {
                                foreach ($order->load('items.product')->items as $item) {
                                    if ($item->product) {
                                        $item->product->increment('stock_quantity', $item->quantity);
                                    }
                                }
                            }
                            $order->status = $newStatus;
                        }
                    }
                }

                $order->save();
                return ['success' => true, 'current_status' => $srStatus ?? 'Processing'];
            }

            // If we have an AWB code but no tracking entries yet
            if ($order->awb_code) {
                return ['success' => true, 'current_status' => 'AWB Assigned (Tracking will be available once picked up)'];
            }

            return ['success' => false, 'message' => 'Tracking info not available yet. Please use the "Update Status" button in a few minutes.'];
        }

        return ['success' => false, 'message' => $result['message']];
    }
}
