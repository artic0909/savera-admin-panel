@extends('admin.layouts.app')

@section('title', 'Payment Settings')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Settings /</span> Payment Settings</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Manage Payment Settings</h5>
                    <div class="card-body">
                        <form action="{{ route('admin.payment-settings.update') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="razorpay_key" class="form-label">Razorpay Key Id</label>
                                <input type="text" class="form-control" id="razorpay_key" name="razorpay_key"
                                    value="{{ $paymentSetting?->razorpay_key }}">
                            </div>
                            <div class="mb-3">
                                <label for="razorpay_secret" class="form-label">Razorpay Key Secret</label>
                                <input type="text" class="form-control" id="razorpay_secret" name="razorpay_secret"
                                    value="{{ $paymentSetting?->razorpay_secret }}">
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_cod_enabled"
                                        name="is_cod_enabled" value="1"
                                        {{ $paymentSetting?->is_cod_enabled ?? true ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_cod_enabled">Enable Cash On Delivery
                                        (COD)</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
