@extends('frontend.layouts.app')

@section('title', 'My Orders')

@section('content')
    <section style="padding: 60px 0; ">
        <div class="wrapper">
            <h2 style="margin-bottom: 30px; text-align: center;">My Orders</h2>

            @if ($orders->isEmpty())
                <div style="background: white; padding: 60px; text-align: center; border-radius: 10px;">
                    <h3>No orders yet</h3>
                    <p>Start shopping to see your orders here!</p>
                    <a href="{{ route('home') }}" class="btn"
                        style="display: inline-block; margin-top: 20px; padding: 12px 30px; background: #000; color: white; text-decoration: none; border-radius: 5px;">Browse
                        Products</a>
                </div>
            @else
                <div style="background: white; border-radius: 10px; overflow: hidden;">
                    @foreach ($orders as $order)
                        <div
                            style="padding: 25px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                            <div style="flex: 1; min-width: 200px;">
                                <h5 style="margin-bottom: 10px;">Order #{{ $order->order_number }}</h5>
                                <p style="color: #666; font-size: 14px; margin-bottom: 5px;">Date:
                                    {{ $order->created_at->format('d M, Y') }}</p>
                                <p style="color: #666; font-size: 14px;">Items: {{ $order->items->count() }}</p>
                            </div>

                            <div style="text-align: center;">
                                <p style="font-weight: bold; font-size: 18px; margin-bottom: 5px;">Rs.
                                    {{ number_format($order->total, 2) }}</p>
                                <span
                                    style="padding: 5px 15px; background: 
                                @if ($order->status == 'pending') #FFC107
                                @elseif($order->status == 'processing') #2196F3
                                @elseif($order->status == 'shipped') #9C27B0
                                @elseif($order->status == 'delivered') #4CAF50
                                @else #F44336 @endif
                            ; color: white; border-radius: 20px; font-size: 12px; text-transform: uppercase;">{{ $order->status }}</span>
                            </div>

                            <div>
                                <a href="{{ route('order.details', $order->order_number) }}"
                                    style="padding: 10px 25px; background: #000; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">View
                                    Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 30px;">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
