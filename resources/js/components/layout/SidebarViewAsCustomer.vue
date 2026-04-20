<template>

<div class="offcanvas offcanvas-start bg-success text-white" id="ViewCustomerSidebar" data-bs-backdrop="false">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
            
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><router-link class="nav-link text-white" :to="{ name: 'fruit' }"><i class="fas fa-apple-alt me-2"></i> Fruits</router-link></li>
            <li class="nav-item mb-2"><router-link class="nav-link text-white" :to="{ name: 'vegetable' }"><i class="fas fa-carrot me-2"></i> Vegetables</router-link></li>
        </ul>
    </div>
</div>

</template>