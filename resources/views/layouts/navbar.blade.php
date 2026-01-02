<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Include your app.css -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        
        <div class="container-fluid">
            <!-- Icon -->
            <i class="fas fa-leaf me-2 text-white"></i> 
            <a class="navbar-brand text-white" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}" style="color: white;">HOME</a>
                </li>
                
                <li class="nav-item">
                <a class="nav-link" href="{{ route('aboutPage') }}" style="color: white;">ABOUT</a>
                </li>
        
                <li class="nav-item dropdown">
                <a style="color: white;" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">PRODUCTS</a>
                <ul class="dropdown-menu custom-dropdown">
                <li><a class="dropdown-item" href="{{ route('fruit') }}">FRUITS</a></li>
                <li><a class="dropdown-item" href="{{ route('vegetable') }}">VEGETABLES</a></li>
                </ul>
                </li>

                <li class="nav-item">
                <a class="nav-link" href="{{ route('contactPage') }}" style="color: white;">CONTACT</a>
                </li>  
            </ul>
                    
            
                    <ul class="navbar-nav">
                        @guest
                        <!-- Show when NOT logged in -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}" style="color: white; font-weight: bold;">Login</a>
                        </li>
                        @if (Route::has('register'))

                        <!-- separator -->
                        <li class="nav-item d-flex align-items-center text-white mx-2">|</li>
                            
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}" style="color: white; font-weight: bold;">Register</a>
                            </li>
                        @endif
                    @else
                        <!-- Show when logged in -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Logout</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

<!-- Page Content -->
    <div class="container mt-5 flex-grow-1">
        @yield('content')   {{-- Content will be injected here --}}
    </div>

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>