<!DOCTYPE html>
<html lang="en">

<head>
    @php
        $currentPath = '/' . request()->path();
        if ($currentPath == '//') {
            $currentPath = '/';
        }
        $seo = \App\Models\SeoSetting::where('page_url', $currentPath)->first();
    @endphp

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @if ($seo && $seo->meta_title)
            {{ $seo->meta_title }}
        @else
            @yield('title')
        @endif
    </title>

    @if ($seo && $seo->meta_description)
        <meta name="description" content="{{ $seo->meta_description }}">
    @endif

    @if ($seo && $seo->extra_tags)
        {!! $seo->extra_tags !!}
    @endif

    <link rel="stylesheet" href="{{ asset('assets/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/responsive.css') }}" />
    <link rel="icon" href="{{ asset('assets/images/logo-icon.png') }}" type="image/x-icon">

    <link rel="stylesheet"
        href="https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-rounded/css/uicons-regular-rounded.css" />
    <link rel="stylesheet"
        href="https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-rounded/css/uicons-solid-rounded.css" />
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-brands/css/uicons-brands.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    @stack('styles')

    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1946330439591829');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=1946330439591829&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->

    <meta name="google-site-verification" content="nloUqMBTAZKv9T_fpp04jpXmE4mLVTDXevpzFe5qjNc" />


    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-T7SVBQ7N');
    </script>
    <!-- End Google Tag Manager -->


    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JET2W2Y23X"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-JET2W2Y23X');
    </script>
</head>

<body class="{{ $pageclass ?? '' }}">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7SVBQ7N" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @include('frontend.includes.header')
    @yield('content')
    @include('frontend.includes.footer')

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('assets/script.js') }}"></script>

    @stack('scripts')
</body>

</html>
