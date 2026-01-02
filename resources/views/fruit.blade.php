@extends('layouts.navbar')

@section('content')

    <h1 class="slide-in from-top">Fresh Fruits</h1>
    <p class="fade-in">We sell fresh fruits and vegetables to keep you healthy.</p>

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
