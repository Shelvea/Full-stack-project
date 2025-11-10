@php
    // status passed from controller is already normalized (lowercase)
    $pendingShipmentStatuses = ['pending', 'confirmed', 'processing', 'ready for delivery'];
@endphp

<nav class="navbar navbar-expand-sm bg-light border-bottom fixed-top second-navbar">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ $status == 'pending payment' ? 'active' : '' }}"
                   href="{{ route('order.indexUser', ['status'=>'pending payment']) }}">
                   <span class="nav-text">pending payment</span>
                </a>
            </li>
            <li class="separator mx-2">|</li>

            <li class="nav-item">
                <a class="nav-link {{ in_array($status, $pendingShipmentStatuses) ? 'active' : '' }}"
                   href="{{ route('order.indexUser', ['status'=>'pending']) }}">
                   <span class="nav-text">pending shipment</span>
                </a>
            </li>
            <li class="separator mx-2">|</li>

            <li class="nav-item">
                <a class="nav-link {{ $status == 'on the way' ? 'active' : '' }}"
                   href="{{ route('order.indexUser', ['status'=>'On the Way']) }}">
                   <span class="nav-text">on the way</span>
                </a>
            </li>
            <li class="separator mx-2">|</li>

            <li class="nav-item">
                <a class="nav-link {{ $status == 'completed' ? 'active' : '' }}"
                   href="{{ route('order.indexUser', ['status'=>'Completed']) }}">
                   <span class="nav-text">order completed</span>
                </a>
            </li>
            <li class="separator mx-2">|</li>

            <li class="nav-item">
                <a class="nav-link {{ $status == 'returned' ? 'active' : '' }}"
                   href="{{ route('order.indexUser', ['status'=>'Returned']) }}">
                   <span class="nav-text">returned goods or refund</span>
                </a>
            </li>
            <li class="separator mx-2">|</li>

            <li class="nav-item">
                <a class="nav-link {{ $status == 'cancelled' ? 'active' : '' }}"
                   href="{{ route('order.indexUser', ['status'=>'Cancelled']) }}">
                   <span class="nav-text">cancelled</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
