/**
 * =============================================================================
 * resources/js/app.js
 * Smart-Saka — Main JavaScript Entry Point
 * Diproses oleh: Vite (ES Modules)
 * =============================================================================
 *
 * Catatan untuk Developer Laravel:
 * - File ini di-bundle oleh Vite, bukan dipakai inline.
 * - Untuk interaktivitas yang lebih kompleks, pertimbangkan Alpine.js.
 *   Install: npm install alpinejs
 *   Kemudian import Alpine di sini dan init via Alpine.start().
 *
 * Struktur Modul:
 *   1. initPasswordToggles()   — Visibility toggle untuk semua input password
 *   2. initPasswordStrength()  — Strength meter untuk halaman reset password
 *   3. initSelectFocus()       — Sync warna label select dengan focus-within
 * =============================================================================
 */

import './bootstrap'; // Axios, CSRF setup (sudah ada di Laravel starter)


/* =============================================================================
   1. PASSWORD VISIBILITY TOGGLE
   Mengelola semua tombol `.pw-toggle` yang berada di dalam container
   yang memiliki `.pw-input` pada halaman yang sama.
   ============================================================================= */

/**
 * Inisialisasi toggle visibility untuk semua pasangan
 * tombol `.pw-toggle` dan input `.pw-input`.
 *
 * Tombol menggunakan aria-pressed untuk aksesibilitas screen reader.
 */
function initPasswordToggles() {
    /** @type {NodeListOf<HTMLButtonElement>} */
    const toggleButtons = document.querySelectorAll('.pw-toggle');

    toggleButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const container = button.closest('.relative');
            if (!container) return;

            /** @type {HTMLInputElement|null} */
            const input = container.querySelector('.pw-input');
            const icon  = button.querySelector('.material-symbols-outlined');

            if (!input || !icon) return;

            const isHidden = input.type === 'password';

            // Toggle tipe input
            input.type = isHidden ? 'text' : 'password';

            // Update ikon Material Symbol
            icon.textContent = isHidden ? 'visibility_off' : 'visibility';

            // Update aria-pressed untuk screen reader
            button.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
        });
    });
}


/* =============================================================================
   2. PASSWORD STRENGTH METER
   Menghitung kekuatan password berdasarkan panjang dan kompleksitas,
   lalu memperbarui tampilan bar dan label kekuatan.

   Hanya aktif di halaman reset-password (ketika #strength-bars ada).
   ============================================================================= */

/** @typedef {'weak'|'medium'|'strong'} StrengthLevel */

/**
 * Kalkulasi kekuatan password.
 * @param {string} password
 * @returns {{ level: StrengthLevel, score: number, label: string }}
 */
function calculateStrength(password) {
    let score = 0;

    if (password.length >= 8)  score++;
    if (password.length >= 12) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    if (score <= 2) return { level: 'weak',   score: 1, label: 'Lemah' };
    if (score <= 3) return { level: 'medium',  score: 2, label: 'Sedang' };
    return               { level: 'strong',  score: 3, label: 'Kuat' };
}

/** Map level ke Tailwind color class */
const STRENGTH_COLORS = {
    weak:   'bg-error',
    medium: 'bg-secondary',
    strong: 'bg-primary',
};

/** Map level ke Tailwind text color class */
const STRENGTH_TEXT_COLORS = {
    weak:   'text-error',
    medium: 'text-secondary',
    strong: 'text-primary',
};

/**
 * Inisialisasi password strength meter.
 * Mencari input[name="password"] di dalam halaman yang juga memiliki
 * elemen #strength-bars.
 */
function initPasswordStrength() {
    const strengthContainer = document.getElementById('strength-bars');
    if (!strengthContainer) return; // Tidak ada di halaman ini

    const passwordInput = document.getElementById('password');
    const strengthLabel = document.querySelector('.strength-label');
    const bars          = strengthContainer.querySelectorAll('.strength-bar');

    if (!passwordInput) return;

    passwordInput.addEventListener('input', () => {
        const value    = passwordInput.value;
        const result   = calculateStrength(value);
        const barColor = STRENGTH_COLORS[result.level];
        const txtColor = STRENGTH_TEXT_COLORS[result.level];

        // Reset semua bar
        bars.forEach((bar, index) => {
            // Hapus semua class warna sebelumnya
            bar.classList.remove('bg-error', 'bg-secondary', 'bg-primary', 'bg-surface-container-highest');

            if (index < result.score) {
                bar.classList.add(barColor);
                bar.setAttribute('data-level', String(result.score));
            } else {
                bar.classList.add('bg-surface-container-highest');
                bar.removeAttribute('data-level');
            }
        });

        // Update label teks
        if (strengthLabel) {
            strengthLabel.textContent = value.length > 0 ? `Kekuatan: ${result.label}` : '';
            strengthLabel.className   = `strength-label text-[10px] uppercase tracking-widest mt-2 font-bold ${value.length > 0 ? txtColor : 'text-outline'}`;
        }

        // Update aria-valuenow pada progressbar
        const percentMap = { weak: 33, medium: 66, strong: 100 };
        strengthContainer.setAttribute('aria-valuenow', String(percentMap[result.level]));
    });
}


/* =============================================================================
   3. SELECT DROPDOWN STYLING
   Tailwind tidak dapat style native <select> dengan focus-within secara penuh.
   Script ini menambahkan class manual saat select dalam focus.
   ============================================================================= */

function initSelectFocus() {
    document.querySelectorAll('.input-focus-effect select').forEach((select) => {
        const wrapper = select.closest('.input-focus-effect');

        select.addEventListener('focus', () => {
            wrapper?.classList.add('ring-1', 'ring-primary/30');
        });
        select.addEventListener('blur', () => {
            wrapper?.classList.remove('ring-1', 'ring-primary/30');
        });
    });
}


/* =============================================================================
   INIT — Jalankan semua modul saat DOM siap
   ============================================================================= */

document.addEventListener('DOMContentLoaded', () => {
    initPasswordToggles();
    initPasswordStrength();
    initSelectFocus();
});

// resources/js/app.js

document.addEventListener("DOMContentLoaded", function() {

    // =========================================================================
    // LOGIKA FETCH DESA BERDASARKAN KECAMATAN (Halaman Register / Profil)
    // =========================================================================
    const kecamatanSelect = document.getElementById("kecamatan");
    const desaSelect = document.getElementById("desa");

    // Pastikan elemennya ada di halaman sebelum menjalankan script
    if (kecamatanSelect && desaSelect) {
        kecamatanSelect.addEventListener("change", function() {
            const idKecamatan = this.value;

            if (idKecamatan) {
                // Tampilkan loading saat fetch
                desaSelect.innerHTML = '<option value="" disabled selected hidden>Memuat desa...</option>';
                desaSelect.setAttribute("disabled", "disabled");

                fetch(`/api/desa/${idKecamatan}`)
                    .then(response => response.json())
                    .then(data => {
                        desaSelect.innerHTML = '<option value="" disabled selected hidden>Pilih desa</option>';

                        data.forEach(desa => {
                            const option = document.createElement('option');
                            option.value = desa.id_desa;
                            option.textContent = desa.nama_desa;
                            desaSelect.appendChild(option);
                        });

                        desaSelect.removeAttribute("disabled");
                    })
                    .catch(error => {
                        console.error('Error fetching desa:', error);
                        desaSelect.innerHTML = '<option value="" disabled selected hidden>Gagal memuat data</option>';
                    });
            }
        });
    }

});
