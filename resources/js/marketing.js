/**
 * resources/js/marketing.js — Smart-Saka Landing Page entry
 * Tailwind v4 + Alpine (npm) + Axios bootstrap (existing)
 */

import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

function initNavbar() {
    const navbar = document.getElementById('navbar');
    if (!navbar) return;

    window.addEventListener(
        'scroll',
        () => {
            navbar.classList.toggle('navbar-scrolled', window.scrollY > 20);
        },
        { passive: true },
    );

    const sections = document.querySelectorAll('section[id], footer[id]');
    const navLinks = document.querySelectorAll('nav a[href^="#"]');

    const ioNav = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                navLinks.forEach((link) => {
                    const match = link.getAttribute('href') === `#${entry.target.id}`;
                    link.classList.toggle('text-olive-600', match);
                    link.classList.toggle('bg-olive-50', match);
                });
            });
        },
        { threshold: 0.4 },
    );

    sections.forEach((s) => ioNav.observe(s));
}

function initScrollReveal() {
    const els = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('visible');
                io.unobserve(entry.target);
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' },
    );

    els.forEach((el) => io.observe(el));
}

window.showToast = function showToast(msg, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toast-msg');
    const toastIcon = document.getElementById('toast-icon');
    if (!toast || !toastMsg) return;

    toastMsg.textContent = msg;

    if (toastIcon) {
        const path = toastIcon.querySelector('path');
        if (path) {
            path.setAttribute(
                'd',
                type === 'error'
                    ? 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                    : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
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

function initNewsletter() {
    const form = document.getElementById('nl-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const emailInput = document.getElementById('nl-email');
        const btn = form.querySelector('button[type="submit"]');
        const email = emailInput?.value.trim();
        if (!email || !btn) return;

        const originalText = btn.textContent;
        btn.textContent = 'Mendaftar…';
        btn.disabled = true;

        try {
            // TODO: ganti dengan endpoint nyata saat siap
            // await window.axios.post('/newsletter/subscribe', { email });
            await new Promise((r) => setTimeout(r, 1200));

            btn.textContent = '✓ Terdaftar!';
            btn.style.background = 'var(--color-olive-300)';
            if (emailInput) emailInput.value = '';
            window.showToast('Berhasil! Anda akan mendapat info terbaru dari kami.');
        } catch {
            btn.textContent = originalText || 'Coba Lagi';
            btn.disabled = false;
            window.showToast('Gagal mendaftar. Silakan coba lagi.', 'error');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initNavbar();
    initScrollReveal();
    initNewsletter();
});

