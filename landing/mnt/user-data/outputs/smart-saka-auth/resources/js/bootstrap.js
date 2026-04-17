/**
 * resources/js/bootstrap.js — Smart-Saka Auth
 * Axios setup — axios ^1.11.0 sudah ada di package.json dependencies
 */
import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
} else {
    console.warn('[Smart-Saka] CSRF token meta tag tidak ditemukan.');
}

// Auto-reload on 419 (token expired)
window.axios.interceptors.response.use(
    res => res,
    err => {
        if (err.response?.status === 419) window.location.reload();
        return Promise.reject(err);
    }
);
