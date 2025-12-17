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
                    @if(Auth::guard('customer')->check())
                    <a href="{{ route('profile') }}" class="icon-btn" title="My Profile"><i class="fi fi-rr-user"></i></a>
                    @else
                    <a href="{{ route('login') }}" class="icon-btn" title="Login / Register"><i class="fi fi-rr-user"></i></a>
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
                    <li>
                        <a href="#">Ring <i class="fi fi-rr-angle-small-down"></i></a>
                        <ul class="dropdown">
                            <li><a href="#">Engagement Rings</a></li>
                            <li><a href="#">Wedding Bands</a></li>
                            <li><a href="#">Promise Rings</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Pendant <i class="fi fi-rr-angle-small-down"></i></a>
                        <ul class="dropdown">
                            <li><a href="#">Diamond Pendants</a></li>
                            <li><a href="#">Gold Pendants</a></li>
                            <li><a href="#">Gemstone Pendants</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Earring <i class="fi fi-rr-angle-small-down"></i></a>
                        <ul class="dropdown">
                            <li><a href="#">Studs</a></li>
                            <li><a href="#">Hoops</a></li>
                            <li><a href="#">Drops</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Bracelet <i class="fi fi-rr-angle-small-down"></i></a>
                        <ul class="dropdown">
                            <li><a href="#">Chain Bracelets</a></li>
                            <li><a href="#">Bangles</a></li>
                            <li><a href="#">Cuffs</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Nose Pin <i class="fi fi-rr-angle-small-down"></i></a>
                        <ul class="dropdown">
                            <li><a href="#">Studs</a></li>
                            <li><a href="#">Rings</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Diamond Cut <i class="fi fi-rr-angle-small-down"></i></a>
                        <ul class="dropdown">
                            <li><a href="#">Round</a></li>
                            <li><a href="#">Princess</a></li>
                            <li><a href="#">Emerald</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Ready to ship items <i class="fi fi-rr-angle-small-down"></i></a>
                        <ul class="dropdown">
                            <li><a href="#">Rings</a></li>
                            <li><a href="#">Necklaces</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Others <i class="fi fi-rr-angle-small-down"></i></a>
                        <ul class="dropdown">
                            <li><a href="#">Anklets</a></li>
                            <li><a href="#">Brooches</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </header>