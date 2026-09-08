// Welcome page — Lenis smooth scroll + scroll-triggered animations

document.addEventListener('DOMContentLoaded', () => {
    // ===== Lenis Smooth Scroll =====
    const lenis = new Lenis({
        duration: 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        orientation: 'vertical',
        smoothWheel: true,
    });

    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }
    requestAnimationFrame(raf);

    // ===== Scroll-triggered Animations =====
    const animElements = document.querySelectorAll('[data-anim], [data-stagger]');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -20px 0px'
    });

    animElements.forEach(el => observer.observe(el));

    // Hero entrance animation (immediate, no scroll needed)
    const heroText = document.querySelector('.hero-text');
    const heroTicket = document.querySelector('.hero-ticket');
    if (heroText) heroText.classList.add('is-visible');
    if (heroTicket) {
        setTimeout(() => heroTicket.classList.add('is-visible'), 200);
    }

    // Floating cards subtle bounce loop
    const floatingCards = document.querySelectorAll('.floating-card');
    floatingCards.forEach((card, i) => {
        card.style.animationDelay = `${i * 0.8}s`;
    });

    // Stats counter animation
    const statNumbers = document.querySelectorAll('.stat-number');
    const statObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                statObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    statNumbers.forEach(el => statObserver.observe(el));
});

function animateCounter(el) {
    const target = parseInt(el.dataset.target, 10);
    if (isNaN(target) || target === 0) return;

    const duration = 1200;
    const start = performance.now();

    function update(now) {
        const elapsed = now - start;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        el.textContent = Math.round(eased * target);

        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            el.textContent = target;
        }
    }

    requestAnimationFrame(update);
}
