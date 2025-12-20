@extends('frontend.layouts.app')

@section('title', 'My Account')

@section('content')
    <style>
        .account-container {
            max-width: 1000px;
            width: 100%;
            padding: 2rem;
            margin: 0 auto;
        }

        .account-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .account-header {
            background: var(--primary-color, #312111);
            color: white;
            padding: 1.8rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .account-header h4 {
            margin: 0;
            font-size: 1.85rem;
            font-weight: 600;
        }

        .btn-logout {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.65rem 1.4rem;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .tabs-nav {
            display: flex;
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
        }

        .tab-link {
            flex: 1;
            padding: 1rem;
            text-align: center;
            font-weight: 500;
            color: #666;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .tab-link.active {
            background: white;
            color: var(--primary-color, #312111);
            border-bottom: 3px solid var(--primary-color, #312111);
        }

        .tab-link:hover:not(.active) {
            background: #eef0f3;
        }

        .tab-content {
            display: none;
            padding: 2.5rem;
        }

        .tab-content.active {
            display: block;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
        }

        /* Form styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #444;
        }

        .form-control {
            width: 90%;
            padding: 0.9rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color, #312111);
            box-shadow: 0 0 0 3px rgba(49, 33, 17, 0.15);
        }

        .row-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .btn-primary {
            background: var(--primary-color, #312111);
            color: white;
            border: none;
            padding: 0.95rem 2rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #1f140c;
            transform: translateY(-2px);
        }

        .alert-success,
        .alert-danger {
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .alert-danger ul {
            margin: 0.5rem 0 0;
            padding-left: 1.2rem;
        }

        /* Orders styles */
        .order-item {
            background: #f8f9fa;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .order-number {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .order-status {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #FFC107;
            color: #000;
        }

        .status-processing {
            background: #2196F3;
            color: white;
        }

        .status-shipped {
            background: #9C27B0;
            color: white;
        }

        .status-delivered {
            background: #4CAF50;
            color: white;
        }

        .status-cancelled {
            background: #F44336;
            color: white;
        }

        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .order-info span {
            color: #666;
        }

        .order-total {
            font-weight: 700;
            font-size: 1.2rem;
        }

        .btn-view-order {
            background: var(--primary-color, #312111);
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .btn-view-order:hover {
            background: #1f140c;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #888;
        }

        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .page-item .page-link,
        .page-item span {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            background: #f8f9fa;
            color: #333;
            text-decoration: none;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .page-item.active .page-link,
        .page-item.active span {
            background: var(--primary-color, #312111) !important;
            color: white !important;
            border-color: var(--primary-color, #312111) !important;
        }

        .page-item.disabled .page-link,
        .page-item.disabled span {
            background: #f1f1f1 !important;
            color: #ccc !important;
            cursor: not-allowed;
            border-color: #eee !important;
        }

        .page-item:not(.active):not(.disabled) .page-link:hover {
            background: #e9ecef;
            border-color: #ccc;
            color: var(--primary-color, #312111);
        }

        @media (max-width: 768px) {
            .account-container {
                padding: 1rem;
                max-width: 90%;

            }

            .row-grid {
                grid-template-columns: 1fr;
            }

            .account-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .tabs-nav {
                flex-wrap: wrap;
            }

            .tab-link {
                padding: 0.8rem;
                font-size: 0.95rem;
            }

            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="account-container">
        <div class="account-card">
            <!-- Header -->
            <div class="account-header">
                <h4>My Account</h4>
                <form action="{{ route('logout') }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to logout?')">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>

            <!-- Tabs Navigation -->
            <div class="tabs-nav">
                <div class="tab-link active" data-tab="profile">Profile</div>
                <div class="tab-link" data-tab="orders">My Orders</div>
            </div>

            <!-- Profile Tab -->
            <div id="profile" class="tab-content active">
                <div class="section-title">Personal Information</div>

                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success">
                        Profile updated successfully!
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row-grid">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number <small
                                    class="text-muted">(Optional)</small></label>
                            <input type="text" name="phone" id="phone" class="form-control"
                                value="{{ old('phone', $user->phone ?? '') }}">
                        </div>
                    </div>

                    <div class="section-title mt-4">Change Password <small class="text-muted">(Leave blank to keep
                            current)</small></div>
                    <div class="row-grid">
                        <div class="form-group">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Enter new password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="Confirm new password">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>

            <!-- Orders Tab -->
            <div id="orders" class="tab-content">
                <div class="section-title">My Orders</div>

                @if ($orders->isEmpty())
                    <div class="empty-state">
                        <div class="icon">📦</div>
                        <h3>No Orders Yet</h3>
                        <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
                        <a href="{{ route('home') }}" class="btn-primary"
                            style="display: inline-block; margin-top: 1rem; text-decoration: none;">Browse Products</a>
                    </div>
                @else
                    @foreach ($orders as $order)
                        <div class="order-item">
                            <div class="order-header">
                                <div>
                                    <div class="order-number">Order #{{ $order->order_number }}</div>
                                    <small style="color: #666;">{{ $order->created_at->format('d M, Y h:i A') }}</small>
                                </div>
                                <span class="order-status status-{{ $order->status }}">{{ $order->status }}</span>
                            </div>

                            <div class="order-info">
                                <span>Items: {{ $order->items->count() }}</span>
                                <span>Payment: {{ strtoupper($order->payment_method) }}</span>
                            </div>

                            <div class="order-info">
                                <span class="order-total">Total: Rs. {{ number_format($order->total, 2) }}</span>
                            </div>

                            <a href="{{ route('order.details', $order->order_number) }}" class="btn-view-order">View
                                Details</a>
                        </div>
                    @endforeach

                    <div style="margin-top: 2rem;">
                        {{ $orders->links('frontend.partials.pagination') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to switch tabs
            function switchTab(tabId) {
                // Remove active class from all
                document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

                // Add active to target
                const tabLink = document.querySelector(`.tab-link[data-tab="${tabId}"]`);
                const tabContent = document.getElementById(tabId);

                if (tabLink && tabContent) {
                    tabLink.classList.add('active');
                    tabContent.classList.add('active');
                }
            }

            // Event listeners for tab clicks
            document.querySelectorAll('.tab-link').forEach(link => {
                link.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);

                    // Optional: Update URL without reload (for history)
                    // history.pushState(null, null, '#' + tabId);
                });
            });

            // Check URL parameters for pagination
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('page')) {
                switchTab('orders');

                // Scroll to top of orders section
                const orderSection = document.getElementById('orders');
                if (orderSection) {
                    orderSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    </script>
@endsection
