document.addEventListener('DOMContentLoaded', () => {
    const blocks = document.querySelectorAll('.info-block');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.3 // Déclenche quand 30% du bloc est visible (tu peux essayer 0.1 ou 0.5 aussi)
    });

    blocks.forEach(block => observer.observe(block));
});