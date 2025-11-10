@extends('layouts.navbar')

@section('content')
<div class="container-fluid p-0"> 
    <!-- Hero Section -->
    <section class="contact-section text-white text-center py-5 mb-4" 
        style="background-image: url('https://images.unsplash.com/photo-1506806732259-39c2d0268443'); ">
        <div class="container d-flex flex-column justify-content-center align-items-center h-100">
            <h1 class="display-4 fw-bold">Contact Us</h1>
            <p class="lead">We’d love to hear from you!</p>
        </div>
    </section>

    <!-- Contact Info + Form -->
    <div class="container py-5">
        <div class="row">
            <!-- Contact Info -->
            <div class="col-md-6 mb-4">
                <h3 class="fw-bold mb-3 text-success">Get in Touch</h3>
                <p class="mb-2">📍 123 Fresh Street, Green City</p>
                <p class="mb-2">📞 +123 456 7890</p>
                <p class="mb-4">✉️ info@freshmart.com</p>
                
                <h5 class="text-success fw-bold">Business Hours</h5>
                <p class="mb-1">Mon - Fri: 8:00 AM – 8:00 PM</p>
                <p class="mb-1">Sat - Sun: 9:00 AM – 6:00 PM</p>
            </div>

            <!-- Contact Form -->
            <div class="col-md-6">
                <h3 class="fw-bold mb-3 text-success">Send Us a Message</h3>
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" id="name" class="form-control" placeholder="Enter your name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" id="email" class="form-control" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea id="message" class="form-control" rows="4" placeholder="Write your message..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Send Message</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection