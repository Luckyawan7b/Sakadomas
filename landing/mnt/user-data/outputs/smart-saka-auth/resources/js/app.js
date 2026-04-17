/**
 * =============================================================================
 * resources/js/app.js — Smart-Saka Auth
 * Tailwind v4 stack
 * =============================================================================
 *
 * Catatan v4: Tailwind utility class names tetap sama (bg-primary, text-error, dll).
 * Yang berubah adalah cara token didefinisikan (di app.css @theme),
 * bukan cara class dipakai di HTML atau JS.
 *
 * File ini menangani:
 *   1. Password visibility toggle
 *   2. Password strength meter (halaman reset)
 *   3. Select dropdown focus styling
 * =============================================================================
 */

import './bootstrap';


/* =============================================================================
   1. PASSWORD VISIBILITY TOGGLE
   ============================================================================= */
function initPasswordToggles() {
    document.querySelectorAll('.pw-toggle').forEach((button) => {
        button.addEventListener('click', () => {
            const container = button.closest('.relative');
            if (!container) return;

            const input = container.querySelector('.pw-input');
            const icon  = button.querySelector('.material-symbols-outlined');
            if (!input || !icon) return;

            const isHidden = input.type === 'password';
            input.type      = isHidden ? 'text' : 'password';
            icon.textContent = isHidden ? 'visibility_off' : 'visibility';
            button.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
        });
    });
}


/* =============================================================================
   2. PASSWORD STRENGTH METER
   Hanya aktif di halaman reset-password (elemen #strength-bars harus ada)
   ============================================================================= */

/**
 * @param {string} password
 * @returns {{ score: 1|2|3, label: string, level: 'weak'|'medium'|'strong' }}
 */
function calculateStrength(password) {
    let score = 0;
    if (password.length >= 8)           score++;
    if (password.length >= 12)          score++;
    if (/[A-Z]/.test(password))         score++;
    if (/[0-9]/.test(password))         score++;
    if (/[^A-Za-z0-9]/.test(password))  score++;

    if (score <= 2) return { score: 1, label: 'Lemah',  level: 'weak' };
    if (score <= 3) return { score: 2, label: 'Sedang', level: 'medium' };
    return                 { score: 3, label: 'Kuat',   level: 'strong' };
}

/*
 * Catatan v4: Kita tidak bisa inject Tailwind class seperti bg-primary secara
 * dinamis jika class tersebut belum pernah muncul di source file
 * (v4 melakukan static scan). Solusinya: gunakan inline style dengan var() CSS.
 * Atau gunakan data-level attribute + CSS rule di app.css (sudah ada).
 */
function initPasswordStrength() {
    const barsContainer = document.getElementById('strength-bars');
    if (!barsContainer) return;

    const passwordInput = document.getElementById('password');
    const strengthLabel = document.querySelector('.strength-label');
    const bars          = barsContainer.querySelectorAll('.strength-bar');
    if (!passwordInput) return;

    passwordInput.addEventListener('input', () => {
        const val    = passwordInput.value;
        const result = calculateStrength(val);

        bars.forEach((bar, i) => {
            // Gunakan data-level — dikontrol oleh CSS di app.css
            // (.strength-bar[data-level="1"] { background: var(--color-error); })
            if (i < result.score) {
                bar.setAttribute('data-level', String(result.score));
            } else {
                bar.removeAttribute('data-level');
                bar.style.backgroundColor = '';  // Reset ke default CSS
            }
        });

        if (strengthLabel) {
            strengthLabel.textContent = val.length > 0 ? `Kekuatan: ${result.label}` : '';

            // Reset classes, set warna via inline style (aman dari purging v4)
            const colorMap = {
                weak:   'var(--color-error)',
                medium: 'var(--color-secondary)',
                strong: 'var(--color-primary)',
            };
            strengthLabel.style.color = val.length > 0 ? colorMap[result.level] : 'var(--color-outline)';
        }

        const percentMap = { weak: 33, medium: 66, strong: 100 };
        barsContainer.setAttribute('aria-valuenow', String(percentMap[result.level]));
    });
}


/* =============================================================================
   3. SELECT DROPDOWN FOCUS STYLING
   Menambahkan ring saat select dalam focus (Tailwind tidak handle ini via CSS alone)
   ============================================================================= */
function initSelectFocus() {
    document.querySelectorAll('.input-focus-effect select').forEach((select) => {
        const wrapper = select.closest('.input-focus-effect');
        if (!wrapper) return;

        select.addEventListener('focus', () => {
            wrapper.style.boxShadow = '0 0 0 1px color-mix(in srgb, var(--color-primary) 30%, transparent)';
        });
        select.addEventListener('blur', () => {
            wrapper.style.boxShadow = '';
        });
    });
}


/* =============================================================================
   INIT
   ============================================================================= */
document.addEventListener('DOMContentLoaded', () => {
    initPasswordToggles();
    initPasswordStrength();
    initSelectFocus();
});
