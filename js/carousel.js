(function() {
    let currentSlide = 0;
    let totalSlides = 0;
    let track = null;
    let dots = [];

    function init() {
        track = document.getElementById('carouselTrack');
        if (!track) return;

        totalSlides = track.children.length;
        dots = Array.from(document.querySelectorAll('#carouselDots .jb-dot'));
        Array.from(track.children).forEach(slide => {
            slide.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    slide.click();
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            // only respond to arrow keys when the home section is visible
            if (document.getElementById('home-section').style.display === 'none') return;
            if (e.key === 'ArrowLeft')  carouselPrev();
            if (e.key === 'ArrowRight') carouselNext();
        });

        let touchStartX = 0;
        track.addEventListener('touchstart', e => {
            touchStartX = e.touches[0].clientX;
        }, { passive: true });
        track.addEventListener('touchend', e => {
            const dx = e.changedTouches[0].clientX - touchStartX;
            if (Math.abs(dx) > 40) {
                dx > 0 ? carouselPrev() : carouselNext();
            }
        }, { passive: true });
        render();
    }

    function render() {
        track.style.transform = `translateX(-${currentSlide * 100}%)`;
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === currentSlide);
            dot.setAttribute('aria-current', i === currentSlide ? 'true' : 'false');
        });
        Array.from(track.children).forEach((slide, i) => {
            slide.tabIndex = i === currentSlide ? 0 : -1;
            slide.setAttribute('aria-hidden', i === currentSlide ? 'false' : 'true');
        });
    }

    window.carouselNext = function() {
        currentSlide = (currentSlide + 1) % totalSlides;
        render();
    };

    window.carouselPrev = function() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        render();
    };

    window.carouselGo = function(idx) {
        if (!Number.isInteger(idx) || idx < 0 || idx >= totalSlides) return;
        currentSlide = idx;
        render();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
