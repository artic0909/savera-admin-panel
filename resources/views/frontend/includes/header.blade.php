    <header class="header">
        <div class="top">
            <span class="dynamic-text"
                data-texts='["Coupons and deals over here with dynamic animation!", "Get Flat 20% OFF on your first order!", "Free Shipping on all orders above ₹999"]'>Coupons
                and deals over here with dynamic animation!</span>
        </div>
        <div class="main">
            <div class="wrapper">
                <div class="logo">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" />
                </div>
                <div class="actions">
                    <a href="#" class="icon-btn"><i class="fi fi-rr-search"></i></a>
                    <a href="#" class="icon-btn"><i class="fi fi-rr-heart"></i></a>
                    @if (Auth::guard('customer')->check())
                        <a href="{{ route('profile') }}" class="icon-btn" title="My Profile"><i
                                class="fi fi-rr-user"></i></a>
                    @else
                        <a href="{{ route('login') }}" class="icon-btn" title="Login / Register"><i
                                class="fi fi-rr-user"></i></a>
                    @endif
                    <a href="#" class="icon-btn cart-btn">
                        <i class="fi fi-rr-shopping-cart"></i>
                        <span class="count">0</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="menu">
            <nav>
                <ul>
                    @foreach ($menuCategories as $category)
                        <li>
                            <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </header>
