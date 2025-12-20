@extends('frontend.layouts.app')

@section('content')
    <style>
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            margin: 0 auto;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .login-header {
            text-align: center;
            padding: 2rem 1rem 1.5rem;
            background: var(--primary-color, #312111);
            color: white;
        }

        .login-header h4 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 600;
        }

        .login-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #444;
        }

        .form-control {
            width: 90%;
            padding: 0.85rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color, #312111);
            box-shadow: 0 0 0 3px rgba(49, 33, 17, 0.15);
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }

        .form-check-label {
            color: #666;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--primary-color, #312111);
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 0.9rem;
            background: var(--primary-color, #312111);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-login:hover {
            background: #1f140c;
        }

        .login-footer {
            text-align: center;
            padding: 1.5rem;
            background: #f8f9fa;
            font-size: 0.95rem;
            color: #666;
        }

        .login-footer a {
            color: var(--primary-color, #312111);
            font-weight: 600;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: none;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 1.2rem;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 1rem;
                max-width: 90%;
            }

            .login-body {
                padding: 1.5rem;
            }
        }
    </style>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h4>Customer Login</h4>
            </div>

            <div class="login-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                            required autofocus placeholder="Enter your email">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required
                            placeholder="Enter your password">
                    </div>

                    <div class="options-row">
                        <div class="form-check">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input">
                            <label for="remember" class="form-check-label">Remember Me</label>
                        </div>
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-login">Login</button>
                </form>
            </div>

            <div class="login-footer">
                Don't have an account? <a href="{{ route('register') }}">Register here</a>
            </div>
        </div>
    </div>
@endsection
