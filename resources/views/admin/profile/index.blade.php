@extends('admin.layouts.app')

<style>
    .floating-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background-color: #0d6efd;
        color: #fff;
        font-size: 30px;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        z-index: 999;
    }

    .floating-btn:hover {
        background-color: #084298;
    }
</style>

@section('title', 'Manage Profile')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Account Settings</h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>
                <!-- Account -->
                <div class="card-body">
                    <!-- <div
                        class="d-flex align-items-start align-items-sm-center gap-4">
                        <img
                            src="{{ asset('./admin/assets/img/avatars/1.png') }}"
                            alt="user-avatar"
                            class="d-block rounded"
                            height="100"
                            width="100"
                            id="uploadedAvatar" />
                    </div> -->
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST" id="formAccountSettings">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="firstName" class="form-label">Admin Name</label>
                                <input
                                    class="form-control"
                                    type="text"
                                    name="name"
                                    value="{{ Auth::guard('admin')->user()->name }}" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input
                                    class="form-control"
                                    type="email"
                                    name="email"
                                    readonly
                                    value="{{ Auth::guard('admin')->user()->email }}" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">New Password</label>
                                <input
                                    class="form-control"
                                    type="password"
                                    name="password"
                                    autocomplete="new-password"
                                    placeholder="Enter new password" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    name="password_confirmation"
                                    placeholder="Confirm password" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">
                                Save changes
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                Reset
                            </button>
                        </div>
                    </form>

                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

@endpush