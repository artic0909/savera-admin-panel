@extends('admin.layouts.app')

@section('title', 'Shipping Settings')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Settings /</span> Shipping Settings</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header d-flex justify-content-between align-items-center">
                        Manage Shipping Settings (Shiprocket)
                        <button type="button" class="btn btn-outline-info" id="test-connection">Test Connection</button>
                    </h5>
                    <div class="card-body">
                        <form action="{{ route('admin.shipping-settings.update') }}" method="POST"
                            id="shipping-settings-form">
                            @csrf
                            <div class="mb-3">
                                <label for="shiprocket_email" class="form-label">Shiprocket Email</label>
                                <input type="email" class="form-control" id="shiprocket_email" name="shiprocket_email"
                                    value="{{ $shippingSetting?->shiprocket_email }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="shiprocket_password" class="form-label">Shiprocket Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="shiprocket_password"
                                        name="shiprocket_password" value="{{ $shippingSetting?->shiprocket_password }}"
                                        required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="togglePassword">Show</button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="shiprocket_pickup_location" class="form-label">Shiprocket Pickup Location
                                    Nickname</label>
                                <input type="text" class="form-control" id="shiprocket_pickup_location"
                                    name="shiprocket_pickup_location"
                                    value="{{ $shippingSetting?->shiprocket_pickup_location ?? 'Primary' }}"
                                    placeholder="e.g. Primary or Shop1" required>
                                <small class="text-muted">Must match the Pickup Location nickname in your Shiprocket
                                    dashboard (e.g. "Home", "Office", "Primary").
                                    You can find this under <strong>Settings > Pickup Locations</strong> in
                                    Shiprocket.</small>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_shiprocket_enabled"
                                        name="is_shiprocket_enabled" value="1"
                                        {{ $shippingSetting?->is_shiprocket_enabled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_shiprocket_enabled">Enable Shiprocket</label>
                                </div>
                            </div>

                            @if ($shippingSetting?->shiprocket_token)
                                <div class="alert alert-success mt-3">
                                    Shiprocket Token is active. Expiry:
                                    {{ $shippingSetting->shiprocket_token_expiry->format('d-M-Y H:i') }}
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#togglePassword').on('click', function() {
                    const password = $('#shiprocket_password');
                    const type = password.attr('type') === 'password' ? 'text' : 'password';
                    password.attr('type', type);
                    $(this).text(type === 'password' ? 'Show' : 'Hide');
                });

                $('#test-connection').on('click', function() {
                    const btn = $(this);
                    const originalText = btn.text();
                    btn.prop('disabled', true).text('Testing...');

                    $.ajax({
                        url: "{{ route('admin.shipping-settings.test') }}",
                        method: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                location.reload();
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function() {
                            alert('Something went wrong!');
                        },
                        complete: function() {
                            btn.prop('disabled', false).text(originalText);
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
