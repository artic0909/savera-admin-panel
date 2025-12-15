@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                Welcome Back {{ auth()->user()->name }}! 🎉
                            </h5>
                            <p class="mb-4">It's your space.</p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img
                                src="{{ asset('./admin/assets/img/illustrations/man-with-laptop-light.png') }}"
                                height="140"
                                alt="View Badge User" />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card-footer text-end">
                            <!-- Additional footer content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Page specific scripts -->
@endpush