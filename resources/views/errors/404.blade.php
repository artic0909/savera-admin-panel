@extends('frontend.layouts.app')

@section('title', '404 - Page Not Found')

@section('content')
    <div class="error-container">
        <div class="error-content">
            <h1 class="error-code">404</h1>
            <div class="error-divider"></div>
            <h2 class="error-message">Oops! Page not found</h2>
            <p class="error-description">
                The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
            </p>
            <a href="{{ url('/') }}" class="home-btn">
                <i class="fi fi-rr-home me-2"></i> Back to Home
            </a>
        </div>

        <div class="blobs">
            <div class="blob"></div>
            <div class="blob"></div>
            <div class="blob"></div>
        </div>
    </div>

    <style>
        .error-container {
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            overflow: hidden;
            background: #fff;
            padding: 20px;
        }

        .error-content {
            position: relative;
            z-index: 10;
            max-width: 600px;
            animation: fadeInUp 0.8s ease-out;
        }

        .error-code {
            font-size: clamp(100px, 15vw, 180px);
            font-weight: 900;
            line-height: 1;
            margin-bottom: 0;
            background: linear-gradient(45deg, #1a1a1a, #4a4a4a);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -5px;
        }

        .error-divider {
            width: 60px;
            height: 4px;
            background: #ffb400;
            margin: 20px auto;
            border-radius: 2px;
        }

        .error-message {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }

        .error-description {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .home-btn {
            display: inline-flex;
            align-items: center;
            background: #1a1a1a;
            color: #fff !important;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .home-btn:hover {
            background: #ffb400;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(255, 180, 0, 0.2);
        }

        /* Animated Blobs in Background */
        .blobs {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .blob {
            position: absolute;
            filter: blur(60px);
            border-radius: 50%;
            opacity: 0.15;
            animation: move 20s infinite alternate;
        }

        .blob:nth-child(1) {
            width: 300px;
            height: 300px;
            background: #ffb400;
            top: -10%;
            left: -10%;
        }

        .blob:nth-child(2) {
            width: 400px;
            height: 400px;
            background: #1a1a1a;
            bottom: -10%;
            right: -10%;
            animation-duration: 25s;
        }

        .blob:nth-child(3) {
            width: 250px;
            height: 250px;
            background: #ffb400;
            top: 40%;
            right: 20%;
            animation-duration: 15s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes move {
            from {
                transform: translate(0, 0) scale(1);
            }

            to {
                transform: translate(20px, 40px) scale(1.1);
            }
        }

        @media (max-width: 768px) {
            .error-message {
                font-size: 22px;
            }

            .error-description {
                font-size: 14px;
            }
        }

        .header {
            position: relative;
        }
    </style>
@endsection
