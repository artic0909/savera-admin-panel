document.addEventListener('DOMContentLoaded', function () {
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.nav-arrow.prev');
    const nextBtn = document.querySelector('.nav-arrow.next');
    let currentSlide = 0;
    const intervalTime = 5000;
    let slideInterval;

    function resetAnimations(slide) {
        slide.style.transition = 'none';
        slide.style.transform = '';
        slide.style.opacity = '';
        setTimeout(() => {
            slide.style.transition = '';
        }, 50);
    }

    function showSlide(index, direction = 'next') {
        if (index === currentSlide) return;

        // Handle outgoing slide
        const outgoingSlide = slides[currentSlide];
        outgoingSlide.classList.remove('active');
        dots[currentSlide].classList.remove('active');

        // Handle incoming slide
        currentSlide = index;
        const incomingSlide = slides[currentSlide];
        dots[currentSlide].classList.add('active');

        // Prepare incoming slide position based on direction
        incomingSlide.style.transition = 'none';
        if (direction === 'next') {
            incomingSlide.style.transform = 'translateX(100px)';
        } else {
            incomingSlide.style.transform = 'translateX(-100px)';
        }

        // Force reflow
        void incomingSlide.offsetWidth;

        // Animate in
        incomingSlide.style.transition = 'all 0.6s cubic-bezier(0.25, 1, 0.5, 1)';
        incomingSlide.classList.add('active');
        incomingSlide.style.transform = 'translateX(0)';
    }

    function nextSlide() {
        let newIndex = (currentSlide + 1) % slides.length;
        showSlide(newIndex, 'next');
    }

    function prevSlide() {
        let newIndex = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(newIndex, 'prev');
    }

    // Auto play
    function startAutoPlay() {
        slideInterval = setInterval(nextSlide, intervalTime);
    }

    function resetAutoPlay() {
        clearInterval(slideInterval);
        startAutoPlay();
    }

    startAutoPlay();

    // Event listeners
    nextBtn.addEventListener('click', () => {
        nextSlide();
        resetAutoPlay();
    });

    prevBtn.addEventListener('click', () => {
        prevSlide();
        resetAutoPlay();
    });

    // Click on dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            let direction = index > currentSlide ? 'next' : 'prev';
            showSlide(index, direction);
            resetAutoPlay();
        });
    });
    // Text Rotation Logic
    const dynamicTextElement = document.querySelector('.dynamic-text');
    let texts = [];

    // Attempt to parse texts from data attribute
    try {
        const rawTexts = dynamicTextElement.getAttribute('data-texts');
        if (rawTexts) {
            texts = JSON.parse(rawTexts);
        } else {
            // Fallback if attribute is missing
            texts = [dynamicTextElement.innerText];
        }
    } catch (e) {
        console.error("Error parsing dynamic texts:", e);
        texts = [dynamicTextElement.innerText];
    }

    let textIndex = 0;

    function rotateText() {
        dynamicTextElement.style.opacity = '0';
        dynamicTextElement.style.transform = 'translateY(-10px)';

        setTimeout(() => {
            textIndex = (textIndex + 1) % texts.length;
            dynamicTextElement.innerText = texts[textIndex];
            dynamicTextElement.style.transform = 'translateY(10px)';

            // Force reflow
            void dynamicTextElement.offsetWidth;

            dynamicTextElement.style.opacity = '1';
            dynamicTextElement.style.transform = 'translateY(0)';
        }, 500); // 0.5s fade out
    }

    setInterval(rotateText, 3000); // Change text every 3 seconds
});

// Initialize Swiper for "For Every Beginning" section
var swiper = new Swiper(".beginning-swiper", {
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: "auto",
    initialSlide: 4, // Start in middle
    coverflowEffect: {
        rotate: 15,
        stretch: 0,
        depth: 100,
        modifier: 1.5,
        slideShadows: true,
    },
    loop: true,
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
});
