<?php

namespace App\Services;

use App\Models\ShippingSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShiprocketService
{
    protected $baseUrl = 'https://apiv2.shiprocket.in/v1/external/';
    protected $token;

    public function __construct()
    {
        $this->token = $this->getToken();
    }

    protected function getToken()
    {
        $setting = ShippingSetting::first();
        if (!$setting) return null;

        if ($setting->shiprocket_token && $setting->shiprocket_token_expiry > now()) {
            return $setting->shiprocket_token;
        }

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::post($this->baseUrl . 'auth/login', [
                'email' => $setting->shiprocket_email,
                'password' => $setting->shiprocket_password,
            ]);

            if ($response && $response->successful()) {
                $data = $response->json();
                if ($data && isset($data['token'])) {
                    $setting->update([
                        'shiprocket_token' => $data['token'],
                        'shiprocket_token_expiry' => now()->addDays(10),
                    ]);
                    return $data['token'];
                }
            }
        } catch (\Exception $e) {
            Log::error('Shiprocket Login Error: ' . $e->getMessage());
        }

        return null;
    }

    public function createOrder($order)
    {
        if (!$this->token) return ['success' => false, 'message' => 'Shiprocket token not available.'];

        $shipping_address = $order->shipping_address;

        // Shiprocket requires specific fields
        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'name' => $item->product_name,
                'sku' => $item->product->sku ?? 'SKU' . $item->product->id,
                'units' => $item->quantity,
                'selling_price' => $item->price,
                'discount' => 0,
                'tax' => 0,
                'hsn' => 0,
            ];
        }

        $setting = ShippingSetting::first();
        $pickup_location = $setting->shiprocket_pickup_location ?? 'Primary';

        // Clean phone number: remove any non-digit characters and take the last 10 digits
        $phone = preg_replace('/[^0-9]/', '', $shipping_address['phone'] ?? $order->customer->phone ?? '');
        if (strlen($phone) > 10) {
            $phone = substr($phone, -10);
        }

        // Basic validation for Shiprocket (Must be 10 digits and start with 6,7,8,9)
        if (!preg_match('/^[6-9][0-9]{9}$/', $phone)) {
            Log::warning('Shiprocket: Invalid phone number detected for order ' . $order->order_number . ': ' . $phone . '. Shiprocket may reject this.');
        }

        // Address validation for Shiprocket (combined address must be >= 3 chars)
        $addr1 = $shipping_address['address_line1'] ?? '';
        $addr2 = $shipping_address['address_line2'] ?? '';
        if (strlen($addr1 . $addr2) < 3) {
            $addr1 = str_pad($addr1, 3, ".");
        }

        $payload = [
            'order_id' => $order->order_number,
            'order_date' => $order->created_at->format('Y-m-d H:i'),
            'pickup_location' => $pickup_location,
            'billing_customer_name' => $shipping_address['full_name'] ?? 'Customer',
            'billing_last_name' => '',
            'billing_address' => $addr1,
            'billing_address_2' => $addr2,
            'billing_city' => $shipping_address['city'] ?? '',
            'billing_pincode' => $shipping_address['postal_code'] ?? '',
            'billing_state' => $shipping_address['state'] ?? '',
            'billing_country' => 'India',
            'billing_email' => $order->customer->email ?? 'no-email@example.com',
            'billing_phone' => $phone,
            'shipping_is_billing' => true,
            'order_items' => $items,
            'payment_method' => $order->payment_method === 'cod' ? 'COD' : 'Prepaid',
            'shipping_charges' => $order->shipping,
            'giftwrap_charges' => 0,
            'transaction_charges' => 0,
            'total_discount' => $order->discount_amount,
            'sub_total' => $order->total,
            'length' => 10,
            'breadth' => 10,
            'height' => 10,
            'weight' => 0.5,
        ];

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withToken($this->token)->post($this->baseUrl . 'orders/create/adhoc', $payload);

            if ($response && $response->successful()) {
                $data = $response->json();
                if (isset($data['order_id']) && isset($data['shipment_id'])) {
                    return ['success' => true, 'data' => $data];
                }

                $errorMessage = $data['message'] ?? 'Failed to create order. Invalid response format from Shiprocket.';
                Log::warning('Shiprocket Create Order: Unexpected response format', [
                    'order_number' => $order->order_number,
                    'response' => $data
                ]);
                return ['success' => false, 'message' => $errorMessage];
            }

            $errorData = $response?->json();

            // Handle specific Shiprocket errors like Wrong Pickup Location
            if (isset($errorData['message']) && strpos($errorData['message'], 'Wrong Pickup location') !== false) {
                $available = '';
                if (isset($errorData['data']['data'][0]['pickup_location'])) {
                    $available = '. Available location in your account: "' . $errorData['data']['data'][0]['pickup_location'] . '"';
                }
                return ['success' => false, 'message' => $errorData['message'] . $available . '. Please update this in Shipping Settings.'];
            }

            Log::error('Shiprocket Create Order Failed', [
                'order_number' => $order->order_number,
                'response' => $errorData,
                'status' => $response?->status()
            ]);

            return ['success' => false, 'message' => $errorData['message'] ?? 'Failed to create order. Status: ' . ($response?->status() ?? 'Unknown')];
        } catch (\Exception $e) {
            Log::error('Shiprocket Create Order Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
    }

    public function getTrackingStatus($shipmentId)
    {
        if (!$this->token) return ['success' => false, 'message' => 'Shiprocket token not available.'];

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withToken($this->token)->get($this->baseUrl . 'courier/track/shipment/' . $shipmentId);

            if ($response && $response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'message' => $response?->json()['message'] ?? 'Failed to fetch tracking status.'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function assignAwb($shipmentId)
    {
        if (!$this->token) return ['success' => false, 'message' => 'Shiprocket token not available.'];

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withToken($this->token)->post($this->baseUrl . 'courier/assign/awb', [
                'shipment_id' => $shipmentId
            ]);

            $data = $response->json();

            if ($response && $response->successful()) {
                if (isset($data['response']['data']['awb_code'])) {
                    return ['success' => true, 'awb_code' => $data['response']['data']['awb_code']];
                }

                // Deep search for a message in case of failure
                $msg = $data['message'] ?? null;

                // Check for nested awb_assign_error which is common for courier failures
                if (isset($data['response']['data']['awb_assign_error'])) {
                    $msg = $data['response']['data']['awb_assign_error'];
                }

                if (!$msg && isset($data['response']['data'])) {
                    $innerData = $data['response']['data'];
                    if (is_string($innerData)) {
                        $msg = $innerData;
                    } elseif (is_array($innerData)) {
                        $msg = $innerData['message'] ?? (isset($innerData['errors']) ? json_encode($innerData['errors']) : null);
                    }
                }

                $msg = $msg ?? 'AWB could not be assigned by Shiprocket. Please check your wallet balance and pickup location serviceability.';

                Log::warning('Shiprocket AWB Assignment Failed', [
                    'shipment_id' => $shipmentId,
                    'response' => $data
                ]);
                return ['success' => false, 'message' => $msg];
            }

            Log::error('Shiprocket AWB Assignment Error (HTTP Failure)', [
                'shipment_id' => $shipmentId,
                'status' => $response->status(),
                'response' => $data
            ]);

            return ['success' => false, 'message' => $data['message'] ?? 'Failed to reach Shiprocket API. Status: ' . $response->status()];
        } catch (\Exception $e) {
            Log::error('Shiprocket AWB Assignment Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function cancelOrder($shiprocketOrderId)
    {
        if (!$this->token) return ['success' => false, 'message' => 'Shiprocket token not available.'];

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withToken($this->token)->post($this->baseUrl . 'orders/cancel', [
                'ids' => [$shiprocketOrderId]
            ]);

            if ($response && $response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            return ['success' => false, 'message' => $response?->json()['message'] ?? 'Failed to cancel order in Shiprocket.'];
        } catch (\Exception $e) {
            Log::error('Shiprocket Cancel Order Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
