import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Reveal body only after Alpine has fully initialised all x-show/x-cloak
// directives, preventing any flash of unstyled/unprocessed content.
document.addEventListener('alpine:initialized', () => {
    document.body.style.opacity = '1';
});

// Safety fallback: show page after 800ms even if something goes wrong
setTimeout(() => { document.body.style.opacity = '1'; }, 800);

Alpine.start();
