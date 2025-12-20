@extends('frontend.layouts.app')

@section('title', 'Order Success')

@section('content')
    <section style="padding: 80px 0; ">
        <div class="wrapper">
            <div
                style="max-width: 800px; margin: 0 auto; background: white; padding: 60px; border-radius: 10px; text-align: center;">
                <div
                    style="width: 80px; height: 80px; background: #4CAF50; border-radius: 50%; margin: 0 auto 30px; display: flex; align-items: center; justify-content: center;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>

                <h2 style="color: #4CAF50; margin-bottom: 15px;">Order Placed Successfully!</h2>
                <p style="font-size: 16px; color: #666; margin-bottom: 30px;">Thank you for your order. We'll send you a
                    confirmation email shortly.</p>

                <div style=" padding: 30px; border-radius: 10px; margin-bottom: 30px;">
                    <h4 style="margin-bottom: 20px;">Order Details</h4>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; text-align: left;">
                        <span style="color: #666;">Order Number:</span>
                        <strong>{{ $order->order_number }}</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; text-align: left;">
                        <span style="color: #666;">Order Date:</span>
                        <strong>{{ $order->created_at->format('d M, Y') }}</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; text-align: left;">
                        <span style="color: #666;">Total Amount:</span>
                        <strong style="font-size: 18px; color: #000;">Rs. {{ number_format($order->total, 2) }}</strong>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; text-align: left;">
                        <span style="color: #666;">Payment Method:</span>
                        <strong>{{ strtoupper($order->payment_method) }}</strong>
                    </div>
                </div>

                {{-- <div
                    style="background: #fff3cd; border: 1px solid #ffc107; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                    <p style="margin: 0; color: #856404;">Estimated delivery: <strong></strong></p>
                </div> --}}

                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('order.details', $order->order_number) }}"
                        style="padding: 12px 30px; background: #000; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">View
                        Order Details</a>
                    <a href="{{ route('home') }}"
                        style="padding: 12px 30px; background: white; color: #000; text-decoration: none; border-radius: 5px; border: 1px solid #ddd; font-weight: bold;">Continue
                        Shopping</a>
                </div>
            </div>
        </div>
    </section>
@endsection
