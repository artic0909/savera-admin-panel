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

        <!-- Category -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Category</span>
        </li>
        <li class="menu-item {{ Request::routeIs('admin.categories.*') ? 'active' : '' }}">
            <a href="{{ route('admin.categories.index') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-list-check'></i>
                <div data-i18n="manageCategory">Manage Categories</div>
            </a>
        </li>

        <!-- Material -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Material</span>
        </li>
        <li class="menu-item {{ Request::routeIs('admin.materials.*') ? 'active' : '' }}">
            <a href="{{ route('admin.materials.index') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-cube'></i>
                <div data-i18n="manageMaterial">Manage Materials</div>
            </a>
        </li>

        <!-- Product -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Product</span>
        </li>
        <li class="menu-item {{ Request::routeIs('admin.products.add') ? 'active' : '' }}">
            <a href="{{ route('admin.products.add') }}" class="menu-link">
                <i class='menu-icon tf inner-icons<|fim_middle|>-icons bx bx-plus-circle'></i>
                <div data-i18n="addProduct">Add Product</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.products.index') ? 'active' : '' }}">
            <a href="{{ route('admin.products.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="viewProduct">View Products</div>
            </a>
        </li>

        <!-- Setting -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Profile</span>
        </li>
        <li class="menu-item {{ Request::routeIs('admin.profile') ? 'active' : '' }}">
            <a href="{{ route('admin.profile') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Enquiry">Profile Details</div>
            </a>
        </li>
    </ul>
</aside>