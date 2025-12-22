<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentSetting;

class PaymentSettingController extends Controller
{
    public function index()
    {
        $paymentSetting = PaymentSetting::first();
        return view('admin.payment-settings.index', compact('paymentSetting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'razorpay_key' => 'nullable|string',
            'razorpay_secret' => 'nullable|string',
        ]);

        $paymentSetting = PaymentSetting::first();

        if (!$paymentSetting) {
            $paymentSetting = new PaymentSetting();
        }

        $paymentSetting->razorpay_key = $request->razorpay_key;
        $paymentSetting->razorpay_secret = $request->razorpay_secret;
        // Checkbox handling: if present, it's true, else false.
        // Make sure the input in blade has a value, e.g. value="1"
        $paymentSetting->is_cod_enabled = $request->has('is_cod_enabled') ? true : false;

        $paymentSetting->save();

        return redirect()->back()->with('success', 'Payment settings updated successfully.');
    }
}
