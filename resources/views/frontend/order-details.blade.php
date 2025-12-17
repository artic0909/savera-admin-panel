@extends('frontend.layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
    <section style="padding: 60px 0; background: #f8f8f8;">
        <div class="wrapper">
            <div style="max-width: 900px; margin: 0 auto;">
                <div style="background: white; padding: 30px; border-radius: 10px; margin-bottom: 20px;">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                        <div>
                            <h3 style="margin-bottom: 10px;">Order #{{ $order->order_number }}</h3>
                            <p style="color: #666;">Placed on {{ $order->created_at->format('d M, Y h:i A') }}</p>
                        </div>
                        <span
                            style="padding: 10px 20px; background: 
                        @if ($order->status == 'pending') #FFC107
                        @elseif($order->status == 'processing') #2196F3
                        @elseif($order->status == 'shipped') #9C27B0
                        @elseif($order->status == 'delivered') #4CAF50
                        @else #F44336 @endif
                    ; color: white; border-radius: 25px; font-weight: bold; text-transform: uppercase;">{{ $order->status }}</span>
                    </div>

                    <h4 style="margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px;">Order Items</h4>

                    @foreach ($order->items as $item)
                        <div
                            style="display: flex; gap: 20px; padding: 20px; border-bottom: 1px solid #eee; align-items: center;">
                            <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="{{ $item->product_name }}"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;">

                            <div style="flex: 1;">
                                <h5 style="margin-bottom: 10px;">{{ $item->product_name }}</h5>
                                <p style="color: #666; font-size: 14px;">
                                    @if ($item->metal_configuration)
                                        Material: {{ $item->metal_configuration['material_name'] ?? 'N/A' }} |
                                        Size: {{ $item->metal_configuration['size_name'] ?? 'N/A' }}
                                    @endif
                                </p>
                                <p style="color: #666; font-size: 14px;">Quantity: {{ $item->quantity }}</p>
                            </div>

                            <div style="text-align: right;">
                                <p style="font-weight: bold; font-size: 18px;">Rs. {{ number_format($item->subtotal, 2) }}
                                </p>
                                <p style="color: #666; font-size: 12px;">@ Rs. {{ number_format($item->price, 2) }} each
                                </p>
                            </div>
                        </div>
                    @endforeach

                    <div style="margin-top: 30px; padding: 20px; background: #f8f8f8; border-radius: 5px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Subtotal:</span>
                            <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Tax:</span>
                            <span>Rs. {{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                            <span>Shipping:</span>
                            <span
                                style="color: green;">{{ $order->shipping > 0 ? 'Rs. ' . number_format($order->shipping, 2) : 'FREE' }}</span>
                        </div>
                        <div
                            style="display: flex; justify-content: space-between; padding-top: 15px; border-top: 2px solid #ddd;">
                            <strong style="font-size: 18px;">Total:</strong>
                            <strong style="font-size: 18px;">Rs. {{ number_format($order->total, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div style="background: white; padding: 25px; border-radius: 10px; height: 100%;">
                            <h5 style="margin-bottom: 15px; border-bottom: 2px solid #eee; padding-bottom: 10px;">Shipping
                                Address</h5>
                            @php $address = $order->shipping_address; @endphp
                            <p style="margin-bottom: 5px;"><strong>{{ $address['full_name'] }}</strong></p>
                            <p style="margin-bottom: 5px;">{{ $address['phone'] }}</p>
                            <p style="margin-bottom: 5px;">{{ $address['address_line1'] }}</p>
                            @if (!empty($address['address_line2']))
                                <p style="margin-bottom: 5px;">{{ $address['address_line2'] }}</p>
                            @endif
                            <p style="margin-bottom: 5px;">{{ $address['city'] }}, {{ $address['state'] }}</p>
                            <p style="margin-bottom: 5px;">{{ $address['postal_code'] }}</p>
                            <p>{{ $address['country'] }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div style="background: white; padding: 25px; border-radius: 10px; height: 100%;">
                            <h5 style="margin-bottom: 15px; border-bottom: 2px solid #eee; padding-bottom: 10px;">Payment
                                Information</h5>
                            <p style="margin-bottom: 10px;"><strong>Payment Method:</strong>
                                {{ strtoupper($order->payment_method) }}</p>
                            <p style="margin-bottom: 10px;"><strong>Payment Status:</strong>
                                <span
                                    style="padding: 3px 10px; background: 
                                @if ($order->payment_status == 'completed') #4CAF50
                                @elseif($order->payment_status == 'pending') #FFC107
                                @else #F44336 @endif
                            ; color: white; border-radius: 15px; font-size: 12px; text-transform: uppercase;">{{ $order->payment_status }}</span>
                            </p>

                            @if ($order->notes)
                                <div style="margin-top: 20px; padding: 15px; background: #f8f8f8; border-radius: 5px;">
                                    <strong>Order Notes:</strong>
                                    <p style="margin-top: 10px;">{{ $order->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="margin-top: 20px; text-align: center;">
                    <a href="{{ route('orders.index') }}"
                        style="padding: 12px 30px; background: #000; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Back
                        to Orders</a>
                </div>
            </div>
        </div>
    </section>
@endsection
