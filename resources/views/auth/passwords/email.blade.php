@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="container py-5" style="margin-top: 30px">
    <div class="login-container">
        <div class="card shadow">
            <div class="row g-0">
                <!-- Left Side: Features -->
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="login-sidebar h-100 d-flex flex-column justify-content-between p-4">
                        <div>
                            <div class="brand-logo">Edu<span>Share</span></div>
                            <h3 class="mb-4">Need Help Logging In?</h3>
                            <p>We'll email you a link to reset your password. Just enter your registered email.</p>

                            <div class="login-feature">
                                <div class="login-feature-icon bg-light text-dark">
                                    <i class="fas fa-envelope-open-text"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Fast Reset</h6>
                                    <small>Link sent instantly</small>
                                </div>
                            </div>

                            <div class="login-feature">
                                <div class="login-feature-icon bg-light text-dark">
                                    <i class="fas fa-user-lock"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Secure</h6>
                                    <small>Your password is protected</small>
                                </div>
                            </div>
                        </div>

                        <div class="text-white mt-5">
                            <small>&copy; {{ date('Y') }} Domasi College of Education</small>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Form -->
                <div class="col-lg-7 p-4">
                    <h2 class="mb-4 text-center">Forgot Your Password?</h2>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="name@example.com" required>
                                <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">Send Reset Link</button>

                        <p class="text-center mb-0">
                            <a href="{{ route('login') }}" class="text-decoration-none">Back to login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
