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

        return view('admin.orders.show', compact('order'));
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

            // Logic to restore stock if order is cancelled or returned
            if (in_array($newStatus, ['cancelled', 'returned']) && !in_array($oldStatus, ['cancelled', 'returned'])) {
                foreach ($order->items as $item) {
                    $item->product->increment('stock_quantity', $item->quantity);
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

            return redirect()->back()->with('success', 'Order status updated successfully!');
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
}
