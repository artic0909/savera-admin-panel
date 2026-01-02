<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingSetting;
use Illuminate\Support\Facades\Http;

class ShippingSettingController extends Controller
{
    public function index()
    {
        $shippingSetting = ShippingSetting::first();
        return view('admin.shipping-settings.index', compact('shippingSetting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'shiprocket_email' => 'nullable|email',
            'shiprocket_password' => 'nullable|string',
        ]);

        $shippingSetting = ShippingSetting::first();

        if (!$shippingSetting) {
            $shippingSetting = new ShippingSetting();
        }

        $shippingSetting->shiprocket_email = $request->shiprocket_email;
        $shippingSetting->shiprocket_password = $request->shiprocket_password;
        $shippingSetting->shiprocket_pickup_location = $request->shiprocket_pickup_location;
        $shippingSetting->is_shiprocket_enabled = $request->has('is_shiprocket_enabled');

        $shippingSetting->save();

        return redirect()->back()->with('success', 'Shipping settings updated successfully.');
    }

    public function testConnection()
    {
        $shippingSetting = ShippingSetting::first();

        if (!$shippingSetting || !$shippingSetting->shiprocket_email || !$shippingSetting->shiprocket_password) {
            return response()->json(['success' => false, 'message' => 'Credentials not set.']);
        }

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::post('https://apiv2.shiprocket.in/v1/external/auth/login', [
                'email' => $shippingSetting->shiprocket_email,
                'password' => $shippingSetting->shiprocket_password,
            ]);

            if ($response && $response->successful()) {
                $data = $response->json();
                if ($data && isset($data['token'])) {
                    $shippingSetting->update([
                        'shiprocket_token' => $data['token'],
                        'shiprocket_token_expiry' => now()->addDays(10), // Shiprocket tokens last for 10 days
                    ]);
                    return response()->json(['success' => true, 'message' => 'Connection successful! Token updated.']);
                }
            }

            return response()->json(['success' => false, 'message' => 'Connection failed: ' . ($response?->json()['message'] ?? 'Unknown error')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
