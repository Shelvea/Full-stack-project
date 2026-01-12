<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? '' }} | {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--font-awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>

<body>
    <div class="min-vh-100 bg-light pb-2">
        @include('layouts.dash-navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-light shadow-sm pt-5">
                <div class="container py-2">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="container mt-5 pt-5">
            @include('layouts.alert')
            {{ $slot }}
        </main>
    </div>

    <!-- Footer -->
    @include('layouts.footer')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let myModalEl = document.querySelector('[data-modal="1"]');
        if (myModalEl) {
            const myModal = new bootstrap.Modal(myModalEl);
            myModal.show();
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        // Select all Bootstrap alerts
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            // Auto dismiss after 3 seconds (3000ms)
            setTimeout(() => {
                // Use Bootstrap's alert instance to close it
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }, 3000);
        });
    });
    </script>
    @stack('scripts') <!-- stack for page-specific scripts -->
</body>

</html>
