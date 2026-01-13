function loadCategory(event, element) {
    event.preventDefault();

    // Remove active class from all
    document.querySelectorAll('.cat-item').forEach(el => el.classList.remove('active'));
    // Add to clicked
    element.classList.add('active');

    const categoryId = element.getAttribute('data-id');
    const container = document.getElementById('product-container');
    const loader = document.getElementById('product-loader');
    const exploreBtn = document.getElementById('explore-more-btn');

    container.style.opacity = '0.5';
    loader.style.display = 'block';

    fetch(`${homeConfig.ajaxProductsUrl}?category_id=${categoryId}`)
        .then(response => response.json())
        .then(data => {
            container.innerHTML = data.html;
            container.style.opacity = '1';
            loader.style.display = 'none';

            // Update Explore More Link
            if (data.category_slug && data.category_slug !== '#') {
                exploreBtn.href = homeConfig.categoryUrl + "/" + data.category_slug;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.style.opacity = '1';
            loader.style.display = 'none';
        });
}

function playVideo(card) {
    const video = card.querySelector("video");
    card.classList.add("playing");
    video.play();
}

document.addEventListener('DOMContentLoaded', function () {
    // Global mute state - all videos share this state
    let globalMuteState = true; // Start muted due to browser autoplay policies

    // Function to update all mute buttons
    function updateAllMuteButtons(isMuted) {
        document.querySelectorAll('.mute-toggle').forEach(btn => {
            const icon = btn.querySelector('i');
            if (isMuted) {
                btn.classList.add('muted');
                icon.className = 'fi fi-rr-volume-mute';
            } else {
                btn.classList.remove('muted');
                icon.className = 'fi fi-rr-volume';
            }
        });
    }

    // Helper to hide loader
    function hideLoader(video) {
        const container = video.closest('.moments-bg');
        if (container) {
            const loader = container.querySelector('.video-loader');
            if (loader) loader.classList.add('hidden');
        }
    }

    // Function to play video with sound (defined early for use in swiper)
    // Function to play video with sound (defined early for use in swiper)
    async function playVideoWithSound(video) {
        if (!video) return;

        // Apply global mute state
        video.muted = globalMuteState;
        video.volume = 1.0;

        try {
            await video.play();
            console.log('Video playing with global mute state:', globalMuteState);

            // Hide loader on success
            hideLoader(video);

            // Always update buttons to match the actual state
            updateAllMuteButtons(globalMuteState);

            // Handle loader for buffering events
            video.onwaiting = () => {
                const loader = video.closest('.moments-bg').querySelector('.video-loader');
                if (loader) loader.classList.remove('hidden');

                // Re-attach fail-safe listener to hide loader when playback resumes
                video.ontimeupdate = () => {
                    if (video.currentTime > 0.1 && !video.paused) {
                        hideLoader(video);
                        video.ontimeupdate = null;
                    }
                };
            };
            video.onplaying = () => {
                hideLoader(video);
            };
            video.oncanplay = () => {
                if (!video.paused) hideLoader(video);
            };

            // Fail-safe: Force hide loader when video actually progresses
            video.ontimeupdate = () => {
                if (video.currentTime > 0.1 && !video.paused) {
                    hideLoader(video);
                    // Remove listener once we've hidden it to save resources
                    video.ontimeupdate = null;
                }
            };

        } catch (error) {
            // Handle AbortError specifically (caused by rapid play/pause toggling)
            if (error.name === 'AbortError') {
                console.log('Video playback aborted (likely due to scrolling or rapid interaction).');
                return;
            }

            // If autoplay with sound fails (policy error), force muted
            console.log('Autoplay with sound blocked, forcing muted playback');
            video.muted = true;
            globalMuteState = true;
            updateAllMuteButtons(true);

            try {
                await video.play();
                hideLoader(video);
            } catch (err) {
                // If it still fails (even muted), or another AbortError happens
                if (err.name !== 'AbortError') {
                    console.error('Video playback failed:', err);
                }
            }
        }
    }

    // Initialize Vertical Swiper for Moments
    if (document.querySelector('.moments-vertical-swiper')) {
        const verticalSwiper = new Swiper('.moments-vertical-swiper', {
            direction: 'vertical',
            loop: true,
            mousewheel: {
                releaseOnEdges: true,
            },
            speed: 800,
            on: {
                init: function () {
                    console.log('Swiper initialized, waiting for visibility');

                    // Check for video_id in URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const videoId = urlParams.get('video_id');

                    if (videoId) {
                        // Find slide with this data-id
                        const slides = this.slides;
                        for (let i = 0; i < slides.length; i++) {
                            const slideId = slides[i].getAttribute('data-id');
                            if (slideId === videoId) {
                                // Slide to it (no animation if it's the target upon landing)
                                this.slideTo(i, 0);
                                break;
                            }
                        }
                    }
                },
                slideChange: function () {
                    // Unmute on swipe (User Interaction)
                    if (globalMuteState) {
                        globalMuteState = false;
                        updateAllMuteButtons(false);
                        // Also remove any hints
                        document.querySelectorAll('.unmute-hint').forEach(hint => hint.remove());
                    }

                    // Pause all videos
                    document.querySelectorAll('.story-video-bg').forEach(v => {
                        v.pause();
                        const pBtn = v.closest('.moments-container').querySelector(
                            '.toggle-video');
                        if (pBtn) pBtn.style.opacity = '1';
                    });

                    // Only auto-play if the section is actually visible in the viewport
                    const stickySection = document.querySelector('.moments-sticky-container');
                    if (stickySection && isElementInViewport(stickySection)) {
                        const activeSlide = this.slides[this.activeIndex];
                        const video = activeSlide.querySelector('video');
                        if (video) {
                            // Reset loader visibility for new slide
                            const loader = activeSlide.querySelector('.video-loader');
                            if (loader) loader.classList.remove('hidden');

                            playVideoWithSound(video);
                            const playBtn = activeSlide.querySelector('.toggle-video');
                            if (playBtn) playBtn.style.opacity = '0';
                        }
                    }
                }
            }
        });

        // Store swiper instance globally for access
        window.momentsSwiperInstance = verticalSwiper;
    }

    // Helper function to check if element is in viewport
    function isElementInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Initialize Swipers for each moments section (products slider)
    document.querySelectorAll('.moments-products-swiper').forEach(function (el) {
        new Swiper(el, {
            slidesPerView: 'auto',
            spaceBetween: 15,
            centeredSlides: false,
            grabCursor: true,
            nested: true, // Important for swiper inside swiper
        });
    });

    // Video Toggle Logic
    document.querySelectorAll('.toggle-video').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation(); // Prevent swiper trigger
            const video = this.closest('.moments-container').querySelector(
                '.story-video-bg');
            const icon = this.querySelector('i');

            if (video.paused) {
                video.play();
                icon.classList.remove('fi-sr-play');
                icon.classList.add('fi-sr-pause');
                this.style.opacity = '0';
            } else {
                video.pause();
                icon.classList.remove('fi-sr-pause');
                icon.classList.add('fi-sr-play');
                this.style.opacity = '1';
            }
        });
    });

    // Function to show play/pause overlay
    function showPlayPauseOverlay(container, isPaused) {
        const overlay = container.querySelector('.play-pause-overlay');
        const icon = overlay.querySelector('i');

        if (isPaused) {
            icon.className = 'fi fi-sr-play';
            overlay.style.display = 'flex';
            setTimeout(() => overlay.classList.add('show'), 10);
        } else {
            icon.className = 'fi fi-sr-pause';
            overlay.style.display = 'flex';
            overlay.classList.add('show');

            // Auto-hide pause icon after 1 second
            setTimeout(() => {
                overlay.classList.remove('show');
                setTimeout(() => overlay.style.display = 'none', 300);
            }, 1000);
        }
    }

    // Tap video to play/pause
    document.querySelectorAll('.story-video-bg').forEach(function (video) {
        video.addEventListener('click', function (e) {
            e.stopPropagation();
            const container = this.closest('.moments-container');

            if (this.paused) {
                this.play();
                showPlayPauseOverlay(container, false);
            } else {
                this.pause();
                showPlayPauseOverlay(container, true);
            }
        });

        // Listen to play/pause events
        video.addEventListener('play', function () {
            const container = this.closest('.moments-container');
            const overlay = container.querySelector('.play-pause-overlay');
            overlay.classList.remove('show');
            setTimeout(() => overlay.style.display = 'none', 300);

            // Ensure loader is hidden when video plays
            hideLoader(this);
        });

        video.addEventListener('playing', function () {
            hideLoader(this);
        });

        video.addEventListener('pause', function () {
            const container = this.closest('.moments-container');
            showPlayPauseOverlay(container, true);
        });
    });

    // Tap container to play/pause
    document.querySelectorAll('.moments-container').forEach(function (container) {
        container.addEventListener('click', function (e) {
            // Don't trigger if clicking on action buttons or products
            if (e.target.closest('.moments-actions') ||
                e.target.closest('.moments-bottom') ||
                e.target.closest('.unmute-hint')) {
                return;
            }

            const video = this.querySelector('.story-video-bg');
            if (video) {
                if (video.paused) {
                    video.play();
                    showPlayPauseOverlay(this, false);
                } else {
                    video.pause();
                    showPlayPauseOverlay(this, true);
                }
            }
        });
    });




    // Initial full stop on page load
    document.querySelectorAll('.story-video-bg').forEach(video => {
        video.pause();
        video.currentTime = 0;
    });

    // Auto-fit / Scroll Snapping Logic with Sound Enablement
    const stickySection = document.querySelector('.moments-sticky-container');
    let isSnapping = false;
    let hasUserInteracted = false;

    // Function to play video with sound


    // Show unmute hint
    function showUnmuteHint(video) {
        const container = video.closest('.moments-container');
        if (!container) return;

        let hint = container.querySelector('.unmute-hint');
        if (!hint) {
            hint = document.createElement('div');
            hint.className = 'unmute-hint';
            hint.innerHTML = '<i class="fi fi-rr-volume-mute"></i> Tap to unmute';
            hint.style.cssText = `
                position: absolute;
                bottom: 140px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 10px 20px;
                border-radius: 25px;
                font-size: 14px;
                z-index: 20;
                display: flex;
                align-items: center;
                gap: 8px;
                cursor: pointer;
                backdrop-filter: blur(10px);
            `;
            container.appendChild(hint);

            hint.addEventListener('click', function () {
                // Toggle global mute state
                globalMuteState = false;

                // Apply to all videos
                document.querySelectorAll('.story-video-bg').forEach(v => {
                    v.muted = false;
                    v.volume = 1.0;
                });

                // Update all mute buttons
                updateAllMuteButtons(false);

                // Remove all hints
                document.querySelectorAll('.unmute-hint').forEach(h => h.remove());
            });
        }
    }

    if (stickySection) {
        // Enable sound on first user interaction
        const enableSoundOnInteraction = () => {
            if (hasUserInteracted) return;

            // Try to unmute active video
            const activeVideo = document.querySelector('.swiper-slide-active .story-video-bg');
            if (activeVideo) {
                activeVideo.muted = false;

                // Check if unmuting actually worked (browser didn't block it)
                if (!activeVideo.muted) {
                    hasUserInteracted = true;
                    globalMuteState = false;

                    // Apply to all other videos
                    document.querySelectorAll('.story-video-bg').forEach(video => {
                        video.muted = false;
                        video.volume = 1.0;
                    });

                    // Update UI
                    updateAllMuteButtons(false);
                    document.querySelectorAll('.unmute-hint').forEach(hint => hint.remove());
                } else {
                    // Failed to unmute - keep state as is and try again next interaction
                    activeVideo.muted = true;
                }
            }
        };

        // Listen for user interactions (removed scroll/touchstart as they trigger prematurely on mobile)
        ['keydown', 'click'].forEach(event => {
            document.addEventListener(event, enableSoundOnInteraction, { passive: true });
        });

        const observerOptions = {
            threshold: [0, 0.2] // Trigger at 0, 20% visibility
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const rect = stickySection.getBoundingClientRect();

                // Play video if section is significantly visible (> 20%)
                if (entry.isIntersecting && entry.intersectionRatio >= 0.2) {

                    // Auto-play video
                    const activeVideo = document.querySelector('.swiper-slide-active .story-video-bg');
                    if (activeVideo && activeVideo.paused) {
                        playVideoWithSound(activeVideo);
                    }

                    // Snapping logic (only when entering from top and not already snapped)
                    if (!isSnapping && rect.top > 0 && rect.top < window.innerHeight * 0.8) {
                        isSnapping = true;
                        window.scrollTo({
                            top: stickySection.offsetTop,
                            behavior: 'smooth'
                        });

                        // Try to enter fullscreen on mobile
                        setTimeout(() => {
                            const wrapper = document.querySelector('.moments-vertical-wrapper');
                            if (wrapper && document.documentElement.requestFullscreen) {
                                wrapper.requestFullscreen().catch(err => console.log('Fullscreen failed:', err));
                            } else if (wrapper && wrapper.webkitRequestFullscreen) {
                                wrapper.webkitRequestFullscreen();
                            }
                        }, 800);

                        // Reset flag
                        setTimeout(() => isSnapping = false, 1000);
                    }
                } else {
                    // Less than 20% visible or not intersecting at all - PAUSE IMMEDIATELY
                    // This ensures strict play/pause behavior
                    if (entry.intersectionRatio < 0.2) {
                        console.log('Moments section not visible enough, stopping videos');
                        document.querySelectorAll('.story-video-bg').forEach(video => {
                            if (!video.paused) {
                                video.pause();
                                video.currentTime = 0; // Force full stop/reset
                            }
                        });
                    }
                }
            });
        }, observerOptions);

        observer.observe(stickySection);

        // Exit fullscreen when scrolling away from section
        const exitFullscreenObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting && document.fullscreenElement) {
                    document.exitFullscreen().catch(err => {
                        console.log('Exit fullscreen failed:', err);
                    });
                }
            });
        }, { threshold: 0.1 });

        exitFullscreenObserver.observe(stickySection);
    }

    // Global toggleMute function (accessible from HTML onclick)
    window.toggleMute = function (btn) {
        // Toggle global mute state
        globalMuteState = !globalMuteState;

        // Apply to all videos
        document.querySelectorAll('.story-video-bg').forEach(video => {
            video.muted = globalMuteState;
            video.volume = 1.0;
        });

        // Update all mute buttons
        updateAllMuteButtons(globalMuteState);

        // Remove all unmute hints if unmuting
        if (!globalMuteState) {
            document.querySelectorAll('.unmute-hint').forEach(hint => hint.remove());
        }
    };
});

function shareMoment(videoPath, url) {
    if (navigator.share) {
        navigator.share({
            title: 'Savera Moments',
            text: 'Check out this amazing collection!',
            url: url
        }).catch(console.error);
    } else {
        copyToClipboard(url);
    }
}

async function toggleBulkWishlist(btn) {
    const productIds = btn.dataset.productIds.split(',');
    const icon = btn.querySelector('i');
    const isActive = btn.classList.contains('wishlist-active');

    // Visually toggle immediately
    btn.classList.toggle('wishlist-active');

    if (btn.classList.contains('wishlist-active')) {
        icon.classList.remove('fi-rr-heart');
        icon.classList.add('fi-sr-heart');
    } else {
        icon.classList.remove('fi-sr-heart');
        icon.classList.add('fi-rr-heart');
    }

    if (!isActive) {
        // Add all to wishlist
        for (const productId of productIds) {
            try {
                const response = await fetch(homeConfig.wishlistAddUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': homeConfig.csrfToken
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                });
                const data = await response.json();
                if (data.success) {
                    const badge = document.querySelector('.wishlist-count');
                    if (badge) badge.innerText = data.wishlist_count;
                }
            } catch (error) {
                console.error('Wishlist error:', error);
            }
        }
    } else {
        // Remove all from wishlist
        for (const productId of productIds) {
            try {
                const response = await fetch(homeConfig.wishlistRemoveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': homeConfig.csrfToken
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                });
                const data = await response.json();
                if (data.success) {
                    const badge = document.querySelector('.wishlist-count');
                    if (badge) badge.innerText = data.wishlist_count;
                }
            } catch (error) {
                console.error('Wishlist removal error:', error);
            }
        }
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Link copied to clipboard!');
    });
}
