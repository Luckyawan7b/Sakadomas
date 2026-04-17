/**
 * =============================================================================
 * resources/js/app.js — Smart-Saka Landing Page
 * Stack: Tailwind CSS v4 · Alpine.js 3 (npm) · Axios (npm)
 * =============================================================================
 *
 * package.json sudah menyertakan alpinejs ^3.14.9 dan axios ^1.11.0
 * Keduanya di-import langsung — TIDAK perlu CDN di Blade layout.
 *
 * Pembagian tugas:
 *   Alpine.js  → testimonial slider, FAQ accordion, mobile menu (di Blade)
 *   Vanilla JS → navbar scroll, scroll reveal, toast, newsletter form
 * =============================================================================
 */

import './bootstrap'; // axios setup + CSRF header

/* ── Alpine.js — di-import dari node_modules, bukan CDN ── */
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();


/* =============================================================================
   1. NAVBAR — scroll shadow + active link highlight via IntersectionObserver
   ============================================================================= */
function initNavbar() {
    const navbar = document.getElementById('navbar');
    if (!navbar) return;

    window.addEventListener('scroll', () => {
        navbar.classList.toggle('navbar-scrolled', window.scrollY > 20);
    }, { passive: true });

    const sections = document.querySelectorAll('section[id], footer[id]');
    const navLinks  = document.querySelectorAll('nav a[href^="#"]');

    new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            navLinks.forEach(link => {
                const active = link.getAttribute('href') === '#' + entry.target.id;
                link.classList.toggle('text-olive-600', active);
                link.classList.toggle('bg-olive-50', active);
                if (!active) {
                    link.classList.remove('text-olive-600', 'bg-olive-50');
                }
            });
        });
    }, { threshold: 0.4 }).observe;

    // Re-observe all sections
    const ioNav = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            navLinks.forEach(link => {
                const match = link.getAttribute('href') === '#' + entry.target.id;
                link.classList.toggle('text-olive-600', match);
                link.classList.toggle('bg-olive-50', match);
            });
        });
    }, { threshold: 0.4 });
    sections.forEach(s => ioNav.observe(s));
}


/* =============================================================================
   2. SCROLL REVEAL — .reveal / .reveal-left / .reveal-right
   ============================================================================= */
function initScrollReveal() {
    const els = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
    const io  = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('visible');
            io.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    els.forEach(el => io.observe(el));
}


/* =============================================================================
   3. TOAST NOTIFICATION — showToast(msg, type) callable globally
   ============================================================================= */
window.showToast = function (msg, type = 'success') {
    const toast    = document.getElementById('toast');
    const toastMsg = document.getElementById('toast-msg');
    const toastIcon = document.getElementById('toast-icon');
    if (!toast || !toastMsg) return;

    toastMsg.textContent = msg;

    if (toastIcon) {
        const path = toastIcon.querySelector('path');
        if (path) {
            path.setAttribute('d', type === 'error'
                ? 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
            );
        }
    }

    toast.classList.remove('translate-y-20', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');

    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
        toast.classList.remove('translate-y-0', 'opacity-100');
    }, 3200);
};


/* =============================================================================
   4. NEWSLETTER FORM
   ============================================================================= */
function initNewsletter() {
    const form = document.getElementById('nl-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const emailInput = document.getElementById('nl-email');
        const btn        = form.querySelector('button[type="submit"]');
        const email      = emailInput?.value.trim();
        if (!email) return;

        btn.textContent = 'Mendaftar…';
        btn.disabled    = true;

        try {
            // TODO: ganti dengan endpoint nyata
            // await window.axios.post('/newsletter/subscribe', { email });
            await new Promise(r => setTimeout(r, 1200)); // simulasi

            btn.textContent = '✓ Terdaftar!';
            // v4: jangan inject dynamic Tailwind class — pakai inline style dengan CSS var
            btn.style.background = 'var(--color-olive-300)';
            if (emailInput) emailInput.value = '';
            window.showToast('Berhasil! Anda akan mendapat info terbaru dari kami.');
        } catch {
            btn.textContent = 'Coba Lagi';
            btn.disabled    = false;
            window.showToast('Gagal mendaftar. Silakan coba lagi.', 'error');
        }
    });
}


/* =============================================================================
   INIT
   ============================================================================= */
document.addEventListener('DOMContentLoaded', () => {
    initNavbar();
    initScrollReveal();
    initNewsletter();
});
