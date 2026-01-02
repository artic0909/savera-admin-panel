@extends('frontend.layouts.app')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')
    <style>
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fefefe;
            padding: 2rem;
            border-radius: 15px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: relative;
            animation: modalSlideDown 0.3s ease-out;
        }

        @keyframes modalSlideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-icon {
            font-size: 3rem;
            color: #F44336;
            margin-bottom: 1rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .modal-desc {
            color: #666;
            line-height: 1.5;
            margin-bottom: 25px;
        }

        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn-modal {
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 14px;
        }

        .btn-modal-cancel {
            background: #eee;
            color: #333;
        }

        .btn-modal-confirm {
            background: #F44336;
            color: white;
        }

        .btn-modal:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>

    <section style="padding: 60px 0;">
        <div class="wrapper">
            <div style="max-width: 900px; margin: 0 auto;">
                <div style="background: white; padding: 30px; border-radius: 10px; margin-bottom: 20px;">
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
                        <div>
                            <h3 style="margin-bottom: 10px;">Order #{{ $order->order_number }}</h3>
                            <p style="color: #666;">Placed on {{ $order->created_at->format('d M, Y h:i A') }}</p>
                        </div>
                        <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                            @if (in_array($order->status, ['pending', 'processing']))
                                <button type="button" onclick="showCancelModal()"
                                    style="padding: 10px 20px; background: #fff; color: #F44336; border: 1px solid #F44336; border-radius: 25px; font-weight: bold; cursor: pointer; transition: all 0.3s ease;">
                                    <i class="fi fi-rr-cross-circle" style="margin-right: 5px; vertical-align: middle;"></i>
                                    Cancel Order
                                </button>

                                <form id="cancelOrderForm" action="{{ route('order.cancel', $order->order_number) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                </form>
                            @endif
                            <span
                                style="padding: 10px 20px; background: 
                            @if ($order->status == 'pending') #FFC107
                            @elseif($order->status == 'processing') #2196F3
                            @elseif($order->status == 'shipped') #9C27B0
                            @elseif($order->status == 'delivered') #4CAF50
                            @elseif($order->status == 'cancelled') #F44336
                            @else #F44336 @endif
                        ; color: white; border-radius: 25px; font-weight: bold; text-transform: uppercase;">{{ $order->status }}</span>
                        </div>
                    </div>

                    @if ($order->tracking_url)
                        <div
                            style="background: #e7f3ff; border-left: 5px solid #2196F3; padding: 20px; border-radius: 5px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                            <div>
                                <h5 style="margin-bottom: 5px; color: #0d6efd;"><i class="fi fi-rr-truck-side"></i> Shipment
                                    Tracking</h5>
                                <p style="margin-bottom: 0; font-size: 14px;">AWB Code:
                                    <strong>{{ $order->awb_code ?? 'N/A' }}</strong>
                                </p>
                            </div>
                            <a href="{{ $order->tracking_url }}" target="_blank"
                                style="padding: 10px 20px; background: #2196F3; color: white; border-radius: 5px; text-decoration: none; font-weight: bold; font-size: 14px;">
                                Track on Shiprocket <i class="fi fi-rr-external-link"
                                    style="margin-left: 5px; font-size: 12px;"></i>
                            </a>
                        </div>
                    @endif

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
                                        @if (isset($item->metal_configuration['color_name']))
                                            | Color: {{ $item->metal_configuration['color_name'] }}
                                        @endif
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

                            @if ($order->transaction_id)
                                <p style="margin-bottom: 10px;"><strong>Transaction ID:</strong>
                                    {{ $order->transaction_id }}</p>
                            @endif

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
                    <a href="{{ route('profile') }}?tab=orders"
                        style="padding: 12px 30px; background: #000; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Back
                        to Orders</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Cancel Modal -->
    <div id="cancelModal" class="custom-modal">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fi fi-rr-interrogation"></i>
            </div>
            <div class="modal-title">Cancel Order?</div>
            <div class="modal-desc">Are you sure you want to cancel this order? This action will also cancel the shipment in
                Shiprocket and cannot be undone.</div>
            <div class="modal-actions">
                <button type="button" class="btn-modal btn-modal-cancel" onclick="closeCancelModal()">No, Keep
                    Order</button>
                <button type="button" class="btn-modal btn-modal-confirm" onclick="confirmCancelOrder()">Yes, Cancel
                    Order</button>
            </div>
        </div>
    </div>

    <script>
        function showCancelModal() {
            document.getElementById('cancelModal').style.display = 'flex';
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').style.display = 'none';
        }

        function confirmCancelOrder() {
            document.getElementById('cancelOrderForm').submit();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('cancelModal');
            if (event.target == modal) {
                closeCancelModal();
            }
        }
    </script>
@endsection
