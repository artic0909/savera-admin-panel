<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('./img/rupee.png') }}" width="50px" alt="" />
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2" style="text-transform: capitalize">SAVERA</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <!-- Sidebar -->
    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.home-settings.index') ? 'active' : '' }}">
            <a href="{{ route('admin.home-settings.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div data-i18n="HomeConfiguration">Home Configuration</div>
            </a>
        </li>

        <!-- Category -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Category & Material</span>
        </li>
        <li class="menu-item {{ Request::routeIs('admin.collections.*') ? 'active' : '' }}">
            <a href="{{ route('admin.collections.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-images"></i>
                <div data-i18n="Collections">Manage Collections</div>
            </a>
        </li>
        <li class="menu-item {{ Request::routeIs('admin.categories.*') ? 'active' : '' }}">
            <a href="{{ route('admin.categories.index') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-list-check'></i>
                <div data-i18n="manageCategory">Manage Categories</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.materials.*') ? 'active' : '' }}">
            <a href="{{ route('admin.materials.index') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-cube'></i>
                <div data-i18n="manageMaterial">Manage Materials</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.sizes.*') ? 'active' : '' }}">
            <a href="{{ route('admin.sizes.index') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-expand'></i>
                <div data-i18n="manageSize">Manage Sizes</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.colors.*') ? 'active' : '' }}">
            <a href="{{ route('admin.colors.index') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-palette'></i>
                <div data-i18n="manageColor">Manage Colors</div>
            </a>
        </li>


        <!-- Product -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Mange Product</span>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.pincodes.*') ? 'active' : '' }}">
            <a href="{{ route('admin.pincodes.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-map-pin"></i>
                <div data-i18n="availableArea">Available Area (Pin)</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.products.create') ? 'active' : '' }}">
            <a href="{{ route('admin.products.create') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-plus-circle'></i>
                <div data-i18n="addProduct">Add Product</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.products.index') ? 'active' : '' }}">
            <a href="{{ route('admin.products.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="viewProduct">View Products</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.story-videos.*') ? 'active' : '' }}">
            <a href="{{ route('admin.story-videos.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-video"></i>
                <div data-i18n="storyVideos">Story Videos</div>
            </a>
        </li>



        <li class="menu-item {{ Request::routeIs('admin.coupons.*') ? 'active' : '' }}">
            <a href="{{ route('admin.coupons.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-gift"></i>
                <div data-i18n="Coupons">Coupons</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.orders.*') ? 'active' : '' }}">
            <a href="{{ route('admin.orders.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
                <div data-i18n="manageOrders">Manage Orders</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.customers.*') ? 'active' : '' }}">
            <a href="{{ route('admin.customers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div data-i18n="manageCustomers">Manage Customers</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.inventory.*') ? 'active' : '' }}">
            <a href="{{ route('admin.inventory.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-collection"></i>
                <div data-i18n="Inventory">Inventory</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.stock-notifications.*') ? 'active' : '' }}">
            <a href="{{ route('admin.stock-notifications.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bell"></i>
                <div data-i18n="StockNotifications">Stock Notifications</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.reports.*') ? 'active' : '' }}">
            <a href="{{ route('admin.reports.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div data-i18n="Reports">Reports & Exports</div>
            </a>
        </li>

        <!-- Setting -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Settings</span>
        </li>
        <li class="menu-item {{ Request::routeIs('admin.seo.index') ? 'active' : '' }}">
            <a href="{{ route('admin.seo.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-search"></i>
                <div data-i18n="manageSEO">Manage SEO</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Mange Profile</span>
        </li>
        <li class="menu-item {{ Request::routeIs('admin.profile') ? 'active' : '' }}">
            <a href="{{ route('admin.profile') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Enquiry">Profile Details</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.payment-settings.index') ? 'active' : '' }}">
            <a href="{{ route('admin.payment-settings.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div data-i18n="PaymentSettings">Payment Settings</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.shipping-settings.index') ? 'active' : '' }}">
            <a href="{{ route('admin.shipping-settings.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-truck"></i>
                <div data-i18n="ShippingSettings">Shipping Settings</div>
            </a>
        </li>
    </ul>
</aside>
