<x-app-layout>
    <x-slot name="title">Order Success</x-slot>

    <div class="container mt-5 pt-5 text-center">
        <h3 class="text-success">Thank you! Your order has been placed.</h3>
        <p>We have received your order and it will be processed shortly.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-success mt-3">Go to Home</a>
    </div>
</x-app-layout>
