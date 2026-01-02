@extends('layouts.navbar')

@section('content')
<div class="container-fluid p-0 welcome-new">
        
    <!-- Carousel -->
<div id="heroCarousel" class="carousel slide fade-in" data-bs-ride="carousel">

    <!-- Indicators/dots -->
<div class="carousel-indicators">
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
</div>

<!-- The slideshow/carousel -->
<div class="carousel-inner">
        <!-- Hero Section -->
    <div class="carousel-item active">    
    <img src="https://media.istockphoto.com/id/1352758440/photo/paper-shopping-food-bag-with-grocery-and-vegetables.jpg?s=612x612&w=0&k=20&c=iEYDgT97dpF7DuG4-QUJU3l0-5MKQb01mKbQgEG1pcc=" alt="Slide 1" class="d-block" style="width:100%; height:400px;">
        <div class="carousel-caption">
            <h1 class="display-4 fw-bold">Fresh Fruits & Vegetables Delivered</h1>
            <p class="lead">Healthy, organic, and affordable produce — straight from the farm to your table.</p>
        </div>    
    </div>

    <!-- Slide 2 -->
        <div class="carousel-item">
            <img src="https://media.istockphoto.com/id/870915532/photo/man-holding-crate-ob-fresh-vegetables.jpg?s=612x612&w=0&k=20&c=k2dXOI-wxUy7lX77Pm90vU6TJXmAAv5VtK60ZZHIyCA=" alt="Slide 2" class="d-block" style="width:100%; height:400px;">    
                <div class="carousel-caption">
                    <h1 class="display-4 fw-bold">Farm Fresh, Always Organic</h1>
                    <p class="lead">We bring you produce directly from trusted local farmers.</p>
                </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
            <img src="https://img.lovepik.com/photo/48014/8692.jpg_wh300.jpg" alt="Slide 3" class="d-block" style="width:100%; height:400px;">
                <div class="carousel-caption">
                    <h1 class="display-4 fw-bold">Your Health, Our Priority</h1>
                    <p class="lead">Enjoy fresh, nutritious, and chemical-free fruits & veggies every day.</p>
                </div>
        </div>

        <!-- Left and right controls/icons -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>

    </div>
</div>

    <!-- About Us -->
    <section class="py-5 text-center fade-in" >
        <div class="container">
            <h2 class="mb-4">About Us</h2>
            <p class="lead">At <strong>FreshMart</strong>, we believe in bringing you only the best — hand-picked fruits and vegetables that are fresh, nutritious, and delivered with care. Whether you need juicy fruits or green veggies, we’ve got you covered.</p>
        </div>
    </section>

    <!-- Categories -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4 fade-in">Shop by Category</h2>
            <div class="row">
                <div class="col-md-6 mb-4 slide-in from-left">
                    <div class="card shadow-sm h-100">
                        <img src="https://img.freepik.com/premium-photo/fresh-fruit-background-as-healthy-eating-dieting-concept-winter-assortment-top-view_501761-506.jpg" class="card-img-top custom-img" alt="Fruits">
                        <div class="card-body text-center">
                            <h5 class="card-title">Fruits</h5>
                            <p class="card-text">Sweet and juicy fruits to brighten your day.</p>
                            <a href="{{ url('/fruit') }}" class="btn btn-success">Shop Fruits</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4 slide-in from-right">
                    <div class="card shadow-sm h-100">
                        <img src="https://t4.ftcdn.net/jpg/03/20/39/89/360_F_320398931_CO8r6ymeSFqeoY1cE6P8dbSGRYiAYj4a.jpg" class="card-img-top" alt="Vegetables">
                        <div class="card-body text-center">
                            <h5 class="card-title">Vegetables</h5>
                            <p class="card-text">Fresh and organic vegetables for your healthy meals.</p>
                            <a href="{{ url('vegetable') }}" class="btn btn-success">Shop Vegetables</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-5 slide-in from-left">
        <div class="container">
            <h2 class="text-center mb-4">Best Sellers</h2>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="https://www.shutterstock.com/image-photo/bananas-grapes-600nw-518328943.jpg" class="card-img-top" alt="Bananas">
                        <div class="card-body text-center">
                            <h5 class="card-title">Bananas</h5>
                            <p class="card-text">$2.50 / kg</p>
                            <a href="#" class="btn btn-outline-success">Buy Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="https://www.shutterstock.com/image-photo/fresh-ripe-red-apples-background-600nw-2529779817.jpg" class="card-img-top" alt="Apples">
                        <div class="card-body text-center">
                            <h5 class="card-title">Apples</h5>
                            <p class="card-text">$3.00 / kg</p>
                            <a href="#" class="btn btn-outline-success">Buy Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="https://previews.123rf.com/images/mblach/mblach1403/mblach140300119/27349276-spinach-background.jpg" class="card-img-top" alt="Spinach">
                        <div class="card-body text-center">
                            <h5 class="card-title">Spinach</h5>
                            <p class="card-text">$1.80 / bunch</p>
                            <a href="#" class="btn btn-outline-success">Buy Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm h-100">
                        <img src="https://thumbs.dreamstime.com/b/carrots-pile-carrot-field-background-367754204.jpg" class="card-img-top" alt="Carrots">
                        <div class="card-body text-center">
                            <h5 class="card-title">Carrots</h5>
                            <p class="card-text">$2.20 / kg</p>
                            <a href="#" class="btn btn-outline-success">Buy Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-5 bg-light slide-in from-right">
        <div class="container text-center">
            <h2 class="mb-4">Why Choose Us?</h2>
            <div class="row">
                <div class="col-md-3">
                    <i class="bi bi-basket-fill display-4 text-success"></i>
                    <p>Fresh & Organic</p>
                </div>
                <div class="col-md-3">
                    <i class="bi bi-truck display-4 text-success"></i>
                    <p>Fast Delivery</p>
                </div>
                <div class="col-md-3">
                    <i class="bi bi-cash-coin display-4 text-success"></i>
                    <p>Affordable Prices</p>
                </div>
                <div class="col-md-3">
                    <i class="bi bi-emoji-smile display-4 text-success"></i>
                    <p>Happy Customers</p>
                </div>
            </div>
        </div>
    </section>

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
