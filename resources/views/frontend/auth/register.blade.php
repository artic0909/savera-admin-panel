@extends('frontend.layouts.app')

@section('title', 'Register')

@section('content')
    <style>
        .register-container {
            max-width: 460px;
            width: 100%;
            padding: 2rem;
            margin: 0 auto;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .register-header {
            text-align: center;
            padding: 2rem 1rem 1.5rem;
            background: var(--primary-color, #312111);
            color: white;
        }

        .register-header h4 {
            margin: 0;
            font-size: 1.85rem;
            font-weight: 600;
        }

        .register-body {
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
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color, #312111);
            box-shadow: 0 0 0 3px rgba(49, 33, 17, 0.15);
        }

        .password-row {
            display: flex;
            gap: 1rem;
            flex-direction: column;
        }

        .btn-register {
            width: 100%;
            padding: 0.95rem;
            background: var(--primary-color, #312111);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-register:hover {
            background: #1f140c;
        }

        .register-footer {
            text-align: center;
            padding: 1.5rem;
            background: #f8f9fa;
            font-size: 0.95rem;
            color: #666;
        }

        .register-footer a {
            color: var(--primary-color, #312111);
            font-weight: 600;
            text-decoration: none;
        }

        .register-footer a:hover {
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
            .register-container {
                padding: 1rem;
                max-width: 90%;

            }

            .register-body {
                padding: 1.5rem;
            }

            .password-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h4>Create Account</h4>
            </div>

            <div class="register-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                            required autofocus placeholder="Enter your full name">
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                            required placeholder="Enter your email">
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number <small
                                class="text-muted">(Optional)</small></label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}"
                            placeholder="Enter your phone number">
                    </div>

                    <div class="form-group">
                        <div class="password-row">
                            <div>
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required
                                    placeholder="Create password">
                            </div>
                            <div>
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" required placeholder="Confirm password">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-register">Register</button>
                </form>
            </div>

            <div class="register-footer">
                Already have an account? <a href="{{ route('login') }}">Login here</a>
            </div>
        </div>
    </div>
@endsection
