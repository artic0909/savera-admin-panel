@extends('admin.layouts.app')

@section('title', 'Edit Pincode')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Available Area (Pin) /</span> Edit Pincode</h4>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <h5 class="card-header">Pincode Details</h5>
                    <div class="card-body">
                        <form action="{{ route('admin.pincodes.update', $pincode->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="code" class="form-label">Pincode *</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                    id="code" name="code" value="{{ old('code', $pincode->code) }}"
                                    placeholder="Enter pincode (e.g., 110001)" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="active"
                                        {{ old('status', $pincode->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive"
                                        {{ old('status', $pincode->status) === 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Update Pincode</button>
                                <a href="{{ route('admin.pincodes.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
