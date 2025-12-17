@extends('frontend.layouts.app')

@section('title', 'My Account')

@section('content')
    <style>
        .account-container {
            max-width: 800px;
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

        /* Form styles - only for Profile tab */
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

        /* Empty / Placeholder state for other tabs */
        .placeholder-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #888;
        }

        .placeholder-state .icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.2;
        }

        .placeholder-state h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #555;
        }

        .placeholder-state p {
            font-size: 1.1rem;
            max-width: 400px;
            margin: 0 auto 2rem;
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

        @media (max-width: 768px) {
            .account-container {
                padding: 1rem;
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
                <div class="tab-link" data-tab="orders">Orders</div>
                <div class="tab-link" data-tab="cart">Cart</div>
                <div class="tab-link" data-tab="wishlist">Wishlist</div>
            </div>

            <!-- Profile Tab - Only functional part -->
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

            <!-- Orders Tab - Design Only -->
            <div id="orders" class="tab-content">
                <div class="section-title">My Orders</div>
                <div class="placeholder-state">
                    <div class="icon">📦</div>
                    <h3>Your Order History</h3>
                    <p>All your past and current orders will appear here with tracking details and status updates.</p>
                </div>
            </div>

            <!-- Cart Tab - Design Only -->
            <div id="cart" class="tab-content">
                <div class="section-title">Shopping Cart</div>
                <div class="placeholder-state">
                    <div class="icon">🛒</div>
                    <h3>Your Cart Items</h3>
                    <p>Items you add to cart will show here with quantity, price, and checkout options.</p>
                </div>
            </div>

            <!-- Wishlist Tab - Design Only -->
            <div id="wishlist" class="tab-content">
                <div class="section-title">Wishlist</div>
                <div class="placeholder-state">
                    <div class="icon">❤️</div>
                    <h3>Your Favorite Products</h3>
                    <p>Products you love and save for later will be listed here for easy access.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        document.querySelectorAll('.tab-link').forEach(link => {
            link.addEventListener('click', function() {
                // Remove active class from all
                document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

                // Add active to clicked
                this.classList.add('active');
                const target = document.getElementById(this.getAttribute('data-tab'));
                target.classList.add('active');
            });
        });
    </script>
@endsection
