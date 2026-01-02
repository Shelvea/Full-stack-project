@extends('layouts.navbar')

@section('content')
<div id="app"></div> 
<div class="container-fluid p-0"> 
    <!-- Hero Section -->
    <section class="contact-section text-white text-center py-5 mb-4 slide-in fade-in" 
        style="background-image: url('https://images.unsplash.com/photo-1506806732259-39c2d0268443'); ">
        <div class="container d-flex flex-column justify-content-center align-items-center h-100 fade-in">
            <h1 class="display-4 fw-bold">Contact Us</h1>
            <p class="lead">We’d love to hear from you!</p>
        </div>
    </section>

    <!-- Contact Info + Form -->
    <div class="container py-5">
        <div class="row">
            <!-- Contact Info -->
            <div class="col-md-6 mb-4 slide-in from-left">
                <h3 class="fw-bold mb-3 text-success">Get in Touch</h3>
                <p class="mb-2">📍 123 Fresh Street, Green City</p>
                <p class="mb-2">📞 +123 456 7890</p>
                <p class="mb-4">✉️ info@freshmart.com</p>
                
                <h5 class="text-success fw-bold">Business Hours</h5>
                <p class="mb-1">Mon - Fri: 8:00 AM – 8:00 PM</p>
                <p class="mb-1">Sat - Sun: 9:00 AM – 6:00 PM</p>
            </div>

            <!-- Contact Form -->
            <div class="col-md-6 slide-in from-right">
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

@push('scripts')
<script>

const appearOptions = {
  threshold: 0,
  rootMargin: "0px 0px -300px 0px"
};

const faders = document.querySelectorAll(".fade-in");

const appearOnScroll = new IntersectionObserver(function(
  entries,
  appearOnScroll
) {
  entries.forEach(entry => {
    if (!entry.isIntersecting) {
      return;
    } else {
      entry.target.classList.add("appear");
      appearOnScroll.unobserve(entry.target);
    }
  });
},
appearOptions);

faders.forEach(fader => {
  appearOnScroll.observe(fader);
});

const sliders = document.querySelectorAll(".slide-in");

sliders.forEach(slider => {
  appearOnScroll.observe(slider);
});

</script>
@endpush