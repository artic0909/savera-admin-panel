<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShiprocketWebhookController extends Controller
{
    public function handleStatusUpdate(Request $request)
    {
        // 1. Detailed Logging for debugging for /webhooks/shipping-updates
        $payload = $request->all();
        $token = $request->header('x-api-key');

        Log::info('Shiprocket Webhook Activity', [
            'url' => $request->fullUrl(),
            'payload' => $payload,
            'token_provided' => $token ? 'Yes' : 'No'
        ]);

        // 2. Verify Token for security
        $secret = env('SHIPROCKET_WEBHOOK_TOKEN', 'savera_secure_webhook_token_2025');
        if ($token !== $secret) {
            Log::warning('Shiprocket Webhook: Unauthorized access attempt', ['provided_token' => $token]);
            return response()->json(['message' => 'Unauthorized: Invalid x-api-key'], 401);
        }

        $shiprocketOrderId = $payload['order_id'] ?? null;
        $shipmentId = $payload['shipment_id'] ?? null;
        $status = $payload['status'] ?? $payload['current_status'] ?? null;
        $awb = $payload['awb'] ?? null;

        if (!$shiprocketOrderId && !$shipmentId) {
            return response()->json([
                'message' => 'Invalid payload: order_id or shipment_id required',
                'received' => $payload
            ], 400);
        }

        // Find order by shiprocket_order_id, shipment_id, OR order_number
        $order = Order::with('items.product')
            ->where('shiprocket_order_id', $shiprocketOrderId)
            ->orWhere('shiprocket_shipment_id', $shipmentId)
            ->orWhere('order_number', $shiprocketOrderId) // Fallback for some webhook types
            ->first();

        if ($order) {
            $oldStatus = $order->status;

            if ($awb) {
                $order->awb_code = $awb;
                $order->tracking_url = "https://shiprocket.co/tracking/" . $awb;
            }

            if ($status) {
                $statusMap = [
                    'PICKED UP' => 'shipped',
                    'IN TRANSIT' => 'shipped',
                    'OUT FOR DELIVERY' => 'shipped',
                    'DELIVERED' => 'delivered',
                    '7' => 'delivered', // Numeric fallback
                    'CANCELLED' => 'cancelled',
                    'CANCELED' => 'cancelled',
                    '13' => 'cancelled', // Numeric fallback
                    'RETURNED' => 'returned',
                    'RTO INITIATED' => 'returned',
                    'RTO DELIVERED' => 'returned',
                    'SHIPPED' => 'shipped',
                    '6' => 'shipped', // Numeric fallback
                    'READY TO SHIP' => 'processing',
                    'MANIFESTED' => 'processing',
                    '4' => 'processing', // Numeric fallback
                    'NEW' => 'processing',
                ];

                $normalizedStatus = strtoupper(trim($status));
                if (isset($statusMap[$normalizedStatus])) {
                    $newStatus = $statusMap[$normalizedStatus];

                    if ($newStatus !== $oldStatus) {
                        // Logic to restore stock if order is cancelled or returned
                        if (in_array($newStatus, ['cancelled', 'returned']) && !in_array($oldStatus, ['cancelled', 'returned'])) {
                            foreach ($order->items as $item) {
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
            return response()->json(['message' => 'Status updated']);
        }

        return response()->json(['message' => 'Order not found'], 404);
    }
}
