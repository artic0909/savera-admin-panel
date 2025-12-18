@extends('admin.layouts.app')

@section('title', 'Edit Coupon')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Marketing / Coupons /</span> Edit Coupon</h4>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <h5 class="card-header">Edit Coupon</h5>
                    <div class="card-body">
                        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="code" class="form-label">Coupon Code *</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                        id="code" name="code" value="{{ old('code', $coupon->code) }}" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="active"
                                            {{ old('status', $coupon->status) == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $coupon->status) == 'inactive' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="type" class="form-label">Discount Type *</label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type"
                                        name="type" required>
                                        <option value="fixed"
                                            {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)
                                        </option>
                                        <option value="percent"
                                            {{ old('type', $coupon->type) == 'percent' ? 'selected' : '' }}>Percentage (%)
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="value" class="form-label">Discount Value *</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('value') is-invalid @enderror" id="value"
                                        name="value" value="{{ old('value', $coupon->value) }}" required>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="min_order_amount" class="form-label">Min Order Amount (Optional)</label>
                                    <input type="number" step="0.01" class="form-control" id="min_order_amount"
                                        name="min_order_amount"
                                        value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="max_discount_amount" class="form-label">Max Discount Amount (Optional for
                                        %)</label>
                                    <input type="number" step="0.01" class="form-control" id="max_discount_amount"
                                        name="max_discount_amount"
                                        value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="valid_from" class="form-label">Valid From (Optional)</label>
                                    <input type="date" class="form-control" id="valid_from" name="valid_from"
                                        value="{{ old('valid_from', optional($coupon->valid_from)->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="valid_until" class="form-label">Valid Until (Optional)</label>
                                    <input type="date" class="form-control" id="valid_until" name="valid_until"
                                        value="{{ old('valid_until', optional($coupon->valid_until)->format('Y-m-d')) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="usage_limit" class="form-label">Usage Limit (Total uses, Optional)</label>
                                <input type="number" class="form-control" id="usage_limit" name="usage_limit"
                                    value="{{ old('usage_limit', $coupon->usage_limit) }}">
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Update Coupon</button>
                                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
