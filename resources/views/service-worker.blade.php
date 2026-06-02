importScripts('https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "{{ $config['api_key'] }}",
    authDomain: "{{ $config['auth_domain'] }}",
    projectId: "{{ $config['project_id'] }}",
    storageBucket: "{{ $config['storage_bucket'] }}",
    messagingSenderId: "{{ $config['messaging_sender_id'] }}",
    appId: "{{ $config['app_id'] }}"
});

const messaging = firebase.messaging();

// ============================================================
// onBackgroundMessage — berjalan saat tab TERTUTUP / background.
//
// Karena server mengirim DATA-ONLY message (tanpa field `notification`),
// browser tidak akan pernah auto-tampilkan notifikasi sendiri.
// SW ini adalah satu-satunya yang menampilkan notifikasi sistem
// → tidak ada duplikat, icon & konten fully controlled di sini.
//
// Saat app FOREGROUND, handler ini TIDAK dipanggil — firebase.js
// yang menangani via onMessage() dan menampilkan toast UI saja.
// ============================================================
messaging.onBackgroundMessage((payload) => {
    console.log('[SW] Background message received:', payload);

    // Ambil data dari payload.data (semua field ada di sini karena data-only)
    const title       = payload.data?.title        || 'Notifikasi';
    const body        = payload.data?.body         || '';
    const icon        = payload.data?.icon         || '/Favicon.ico';
    const badge       = payload.data?.badge        || '/Favicon.ico';
    const clickAction = payload.data?.click_action || '/dashboard';

    return self.registration.showNotification(title, {
        body,
        icon,
        badge,
        vibrate: [200, 100, 200],
        data: { click_action: clickAction },
    });
});

// ============================================================
// Klik notifikasi → buka / fokus ke URL tujuan
// BUG FIX: event.waitUntil() harus dipanggil sebagai fungsi,
// bukan sebagai property access (sebelumnya missing parentheses).
// ============================================================
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const clickAction = event.notification.data?.click_action || '/dashboard';

    // event.waitUntil( ... ) — tanda kurung wajib ada!
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            // Jika tab dengan URL tujuan sudah terbuka, fokus ke tab itu
            for (const client of clientList) {
                if (client.url === clickAction && 'focus' in client) {
                    return client.focus();
                }
            }
            // Jika belum ada, buka tab baru
            if (clients.openWindow) {
                return clients.openWindow(clickAction);
            }
        })
    );
});
