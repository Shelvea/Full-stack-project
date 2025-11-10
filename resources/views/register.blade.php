<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    @vite('resources/css/app.css') <!-- Include your app.css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-success text-white d-flex justify-content-center align-items-center vh-100">
    <div class="container" style="max-width:470px;">
        <div class="card shadow p-4" style="width: 500px;">
        <h1 class="card-title text-center text-success mb-4">Register</h1>
<form method="POST" action="{{ route('register.attempt') }}">
    @csrf

    <div class="mb-3">
        <label for="name">Name</label>
        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" placeholder="Name" value="{{ old('name') }}" required autofocus>
    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror    
    </div>

    <div class="mb-3">
        <label for="email">Email</label>
        <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
    @error('email')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror    
    </div>
    
    <div class="mb-3">
        <label for="password">Password</label>
        <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password" required>
        @error('password')
    <div class="invalid-feedback">{{ $message }}</div>
        @enderror 
    </div>

    <div class="mb-3">
        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" placeholder="Confirm password" required>
        @error('password_confirmation')
    <div class="invalid-feedback">{{ $message }}</div>
        @enderror 
    </div>

    <div class="d-flex justify-content-between">
    <a href="{{ url('/login') }}">Already registered?</a>
    
        <div class="d-flex">
            <button class="btn btn-success btn-custom" type="submit">Register</button>
            <a href="{{ url('/') }}" class="btn btn-warning ms-2 btn-back">Back</a>
        </div>
    </div>
</form>
        </div>
    </div>
</body>
</html>