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

    <script>
        (function(w, d, s, c, r, a, m) {
            w['KiwiObject'] = r;
            w[r] = w[r] || function() {
                (w[r].q = w[r].q || []).push(arguments)
            };
            w[r].l = 1 * new Date();
            a = d.createElement(s);
            m = d.getElementsByTagName(s)[0];
            a.async = 1;
            a.src = c;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', "https://app.interakt.ai/kiwi-sdk/kiwi-sdk-17-prod-min.js?v=" + new Date().getTime(),
            'kiwi');
        window.addEventListener("load", function() {
            kiwi.init('', 'VaI9ExdmjezdzFhyK1FURyICMavK4Pve', {});
        });
    </script>
    @include('frontend.includes.header')
    @yield('content')
    <!-- Global Floating Moments Widget (Mobile Only) -->
    @php
        $latestStory = \App\Models\StoryVideo::where('always_visible', true)
            ->where('is_active', true)
            ->with('products')
            ->first();
    @endphp
    @if ($latestStory)
        <div class="s-tkey-thumble-moments">
            <a href="{{ route('home') }}?video_id={{ $latestStory->id }}#moments-section"
                class="moments-video-thumbale active" id="momentsThumb">
                <video src="{{ asset('storage/' . $latestStory->video_path) }}" class="thumb-img" preload="metadata"
                    muted playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                <div class="thumb-overlay">
                    <!-- Top Icons -->
                    <div class="thumb-top-icons">
                        <div class="thumb-logo">
                            <img src="{{ asset('assets/images/white-icon-logo.png') }}" alt="Logo">
                        </div>
                        <div class="thumb-actions">
                            <div class="thumb-action-item">
                                <i class="fi fi-rr-heart"></i>
                            </div>
                            <div class="thumb-action-item">
                                <i class="fi fi-rr-share"></i>
                            </div>
                        </div>
                    </div>
                    <div class="thumb-play-btn">
                        <i class="fi fi-sr-play"></i>
                    </div>
                </div>
            </a>
            <a href="javascript:void(0)" class="global-moments-btn" onclick="toggleMomentsThumb()">
                <img src="{{ asset('assets/images/white-icon-logo.png') }}" alt="Moments">
            </a>

            <script>
                function toggleMomentsThumb() {
                    const thumb = document.getElementById('momentsThumb');
                    if (thumb) {
                        thumb.classList.toggle('active');
                    }
                }

                document.addEventListener('DOMContentLoaded', function() {
                    const widgetWrapper = document.querySelector('.s-tkey-thumble-moments');
                    const stickySection = document.getElementById('moments-section');

                    if (widgetWrapper && stickySection) {
                        const observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    widgetWrapper.style.visibility = 'hidden';
                                    widgetWrapper.style.opacity = '0';
                                    widgetWrapper.style.pointerEvents = 'none';
                                } else {
                                    widgetWrapper.style.visibility = 'visible';
                                    widgetWrapper.style.opacity = '1';
                                    widgetWrapper.style.pointerEvents = 'auto';
                                }
                            });
                        }, {
                            threshold: 0.1
                        });

                        observer.observe(stickySection);
                    }
                });
            </script>

            <style>
                .moments-video-thumbale {
                    display: none !important;
                }

                .s-tkey-thumble-moments {
                    transition: opacity 0.3s ease, visibility 0.3s ease;
                    display: none;
                    /* Hide by default */
                }

                @media (max-width: 768px) {
                    .s-tkey-thumble-moments {
                        display: block;
                    }

                    .moments-video-thumbale.active {
                        display: block !important;
                    }
                }
            </style>
    @endif
    </div>

    @include('frontend.includes.footer')


    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('assets/script.js') }}"></script>

    @stack('scripts')
</body>

</html>
