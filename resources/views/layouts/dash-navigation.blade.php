<nav class="navbar navbar-expand-lg navbar-light bg-success border-bottom fixed-top">
    
    <!-- Hamburger button for sidebar -->
    @can('is_user')
        <button class="btn btn-light me-2 ms-3 btn-ham" type="button" data-bs-toggle="offcanvas" data-bs-target="#userSidebar" aria-controls="userSidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
    @endcan
    
    @can('is_admin')
        <button class="btn btn-light me-2 ms-3 btn-ham" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
    @endcan
    
    <div class="container">        
        <!-- Toggler (hamburger) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            
            <!-- Left Side -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <!-- Icon -->
                    <i class="fas fa-leaf me-2 text-white"></i> 
                    <span class="navbar-brand mb-0 h5 text-white">
                        {{ ('FreshMart') }}
                    </span>
                </li>
            </ul>

            @can('is_user')
            <form id="global-search-form" class="d-flex mx-auto position-relative" style="max-width: 500px; width:100%">
                <input id="global-search" class="form-control me-2" type="text" placeholder="Search" autocomplete="off">
                <!-- Dropdown -->
            <div id="search-dropdown"
            class="list-group position-absolute w-100 shadow"
            style="top:100%; z-index:1000; display:none;">
            </div>
                <button class="btn btn-light btn-search" type="submit">Search</button>
            </form>
            @endcan

            <!-- Right Side -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                <!-- Dropdown User -->
                
                    <a class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown" href="#">
                                {{ Auth::user()->name }}                        
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-end profile-dropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                        <!-- Logout -->
                        <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Log Out</button>
                        </form>
                        </li>
                    </ul>
                
                </li>
            </ul>

        </div>
    </div>
</nav>

@can('is_user')
<div class="offcanvas offcanvas-start bg-success text-white" id="userSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
            
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a class="nav-link text-white" href="#"><i class="fas fa-home me-2"></i> Home</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="#"><i class="fas fa-bell me-2"></i> Notifications</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('products.fruits') }}"><i class="fas fa-apple-alt me-2"></i> Fruits</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('products.vegetables') }}"><i class="fas fa-carrot me-2"></i> Vegetables</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('cart.index') }}"><i class="fas fa-shopping-cart me-2"></i> Cart</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('order.indexUser') }}"><i class="fas fa-box me-2"></i> Orders</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('payment.index') }}"><i class="fas fa-credit-card me-2"></i> Payment</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="#"><i class="fas fa-undo-alt me-2"></i> Returns / Refunds</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="#"><i class="fas fa-headset me-2"></i> Help & Support</a></li>
        </ul>
    </div>
</div>
@endcan

<!--if is_admin-->
@can('is_admin')
<div class="offcanvas offcanvas-start bg-success text-white" id="adminSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
            
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a class="nav-link text-white" href="#"><i class="fas fa-chart-line me-2"></i>Sales & Analytics</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>View Customer Dashboard</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('app', ['any' => 'notification']) }}"><i class="fas fa-bell me-2 position-relative"><!-- Badge --><span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">1</span></i>Notifications</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('admin.products.index') }}"><i class="fas fa-boxes me-2"></i>Product Management</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('admin.orders.index') }}"><i class="fas fa-clipboard-list me-2"></i>Order Management</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="{{ route('app', ['any' => 'users']) }}"><i class="fas fa-users me-2"></i>Customer Management</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="#"><i class="fas fa-credit-card me-2"></i> Payment</a></li>
            <li class="nav-item mb-2"><a class="nav-link text-white" href="#"><i class="fas fa-undo-alt me-2"></i>Returns / Refunds</a></li>
        </ul>
    </div>
</div>
@endcan