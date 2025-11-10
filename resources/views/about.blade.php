@extends('layouts.navbar')

@section('content')
<div class="container-fluid p-0">    
<!-- Hero Section -->
<section class="hero-section text-white text-center py-5 mb-4" style="background-image: url('https://images.unsplash.com/photo-1567306226416-28f0efdc88ce'); background-size: cover; background-position: center; height: 400px; border-radius: 20px; overflow: hidden; ">
    <div class="container about-section">
        <h1 class="display-4 fw-bold">About FreshMart</h1>
        <p class="lead">Delivering fresh, organic fruits and vegetables straight from the farm to your table.</p>
    </div>
</section>

<!-- Our Story -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-4 text-center">Our Story</h2>
        <p class="text-center">FreshMart started with a simple mission: to provide healthy, high-quality produce at affordable prices. We partner directly with local farmers to ensure every fruit and vegetable you receive is fresh, nutritious, and grown sustainably. Our goal is to bring the taste of farm-fresh products to your home every day.</p>
    </div>
</section>

<!-- Our Values -->
<section class="py-5 text-center">
    <div class="container">
        <h2 class="mb-4">Our Values</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <i class="bi bi-leaf display-4 text-success"></i>
                <h5 class="mt-3">Organic</h5>
                <p>We prioritize organic farming practices for a healthier lifestyle.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="bi bi-hand-thumbs-up display-4 text-success"></i>
                <h5 class="mt-3">Quality</h5>
                <p>Only the freshest produce makes it to your doorstep.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="bi bi-people display-4 text-success"></i>
                <h5 class="mt-3">Community</h5>
                <p>Supporting local farmers and communities is at our core.</p>
            </div>
        </div>
    </div>
</section>

<!-- Team / Optional Images -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-4 text-center">Meet Our Team</h2>
        <div class="row justify-content-center">
            <div class="col-md-3 text-center mb-4">
                <img src="https://newyork2024.advertisingweek.com/images/app_images/speakers_crop_13566.jpg" class="rounded-circle mb-2 team-img" alt="Team Member">
                <h6>Jane Doe</h6>
                <p>Founder & CEO</p>
            </div>
            <div class="col-md-3 text-center mb-4">
                <img src="https://media.licdn.com/dms/image/v2/C4D03AQGuyjK1vwQSlw/profile-displayphoto-shrink_400_400/profile-displayphoto-shrink_400_400/0/1516802853971?e=2147483647&v=beta&t=nua1g4BzP90746p6YUtbuGmFcD6_9f4fszuvPCk-E2U" class="rounded-circle mb-2 team-img" alt="Team Member">
                <h6>John Smith</h6>
                <p>Operations Manager</p>
            </div>
        </div>
    </div>
</section>

</div>
@endsection
