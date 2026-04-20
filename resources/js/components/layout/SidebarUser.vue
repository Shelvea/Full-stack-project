<template>
    <div class="offcanvas offcanvas-start bg-success text-white" id="userSidebar" data-bs-backdrop="false">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
            
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><router-link class="nav-link text-white" :to="{ name: 'fruit' }"><i class="fas fa-apple-alt me-2"></i> Fruits</router-link></li>
            <li class="nav-item mb-2"><router-link class="nav-link text-white" :to="{ name: 'vegetable' }"><i class="fas fa-carrot me-2"></i> Vegetables</router-link></li>
            <li class="nav-item mb-2"><router-link class="nav-link text-white" :to="{ name: 'cart' }"><i class="fas fa-shopping-cart me-2"></i> Cart</router-link></li>
            <li class="nav-item mb-2"><router-link class="nav-link text-white" :to="{ name: 'customer-order' }"><i class="fas fa-box me-2"></i> Orders</router-link></li>
            <li class="nav-item mb-2"><router-link class="nav-link text-white" :to="{ name: 'payment' }"><i class="fas fa-credit-card me-2"></i> Payment</router-link></li>
        </ul>
    </div>
</div>
</template>
