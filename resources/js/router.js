import { createRouter, createWebHistory } from 'vue-router';
import Users from './components/Admin/User.vue';
import Notification from './components/Admin/Notification.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';
import Dashboard from './components/Customer/Dashboard.vue';
import AdminDashboard from './components/Admin/AdminDashboard.vue';
import PreviewCustomerDashboard from './components/Admin/PreviewCustomerDashboard.vue';
import Product from './components/Admin/Product.vue';
import Order from './components/Admin/Order.vue';
import OrderSuccess from './components/Customer/OrderSuccess.vue';
import Fruit from './components/Customer/Fruit.vue';
import Vegetable from './components/Customer/Vegetable.vue';
import Cart from './components/Customer/Cart.vue';
import Checkout from './components/Customer/Checkout.vue';
import CustomerOrder from './components/Customer/Order.vue';
import Payment from './components/Customer/Payment.vue';
import Profile from './components/Profile.vue';
import CreateProduct from './components/Admin/Product/Create.vue';
import EditProduct from './components/Admin/Product/Edit.vue';
import ShowProduct from './components/Admin/Product/Show.vue';

import PublicLayout from './PublicLayout.vue';
import DashboardLayout from './DashboardLayout.vue';


const router = createRouter({
    history: createWebHistory(),
    routes: [
        //{ path: '/app/profile', component: Profile, name:'profile'},
        {
        component: PublicLayout,
        children:
        [
            { path: '/app/register', component: Register, name:'register'},
            { path: '/app/login', component: Login, name:'login'}
        ]
        },
        {
        component: DashboardLayout,
        children:[
        { path: '/app/profile', component: Profile, name:'profile'},
        //Product
        { path: '/app/create-product', component: CreateProduct, name:'create-product'},
        { path: '/app/edit-product/:id', component: EditProduct, name:'edit-product'},
        { path: '/app/show-product/:id', component: ShowProduct, name:'show-product'},
        //Admin
        { path: '/app/admin-dashboard', component: AdminDashboard, name:'admin-dashboard'},
        { path: '/app/preview-customer-dashboard', component: PreviewCustomerDashboard, name:'preview-customer-dashboard'},
        { path: '/app/product', component: Product, name:'product'},
        { path: '/app/order', component: Order, name:'order'},
        { path: '/app/placeorder-success', component: OrderSuccess, name:'orderSuccess'},
        { path: '/app/users', component: Users, name:'user' },
        { path: '/app/notification', component: Notification, name:'notification' },
        //customer
        { path: '/app/dashboard', component: Dashboard, name:'dashboard'},
        { path: '/app/fruit', component: Fruit, name:'fruit'},
        { path: '/app/vegetable', component: Vegetable, name:'vegetable'},
        { path: '/app/cart', component: Cart, name:'cart'},
        { path: '/app/checkout', component: Checkout, name: 'checkout'},
        { path: '/app/customer-order', component: CustomerOrder, name:'customer-order'},
        { path: '/app/payment', component: Payment, name:'payment'}
        ]
        }
    ]
});

export default router;