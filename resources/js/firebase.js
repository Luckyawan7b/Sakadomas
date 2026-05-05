import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage, isSupported, deleteToken } from "firebase/messaging";
import axios from 'axios';

const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID
};

let messaging = null;
let onMessageUnsubscribe = null;

export const initFirebase = async () => {
    if (messaging) return true;

    try {
        const supported = await isSupported();
        if (!supported) {
            console.warn('Firebase Messaging is not supported in this browser.');
            return false;
        }

        const app = initializeApp(firebaseConfig);
        messaging = getMessaging(app);

        // Unsubscribe listener lama agar tidak terdaftar 2x
        if (onMessageUnsubscribe) {
            onMessageUnsubscribe();
        }

        // onMessage hanya aktif saat TAB SEDANG TERBUKA (foreground).
        //
        // Karena server mengirim DATA-ONLY message, payload tidak memiliki
        // field `notification` — semua konten ada di `payload.data`.
        // Kita normalisasi ke format { notification: { title, body } }
        // agar komponen toast di blade tidak perlu diubah.
        //
        // TIDAK perlu showNotification() manual di sini — cukup toast UI.
        // SW tidak akan dipanggil saat foreground, jadi zero duplikat.
        onMessageUnsubscribe = onMessage(messaging, (payload) => {
            console.log('[Firebase] Foreground message received:', payload);

            // Normalisasi data-only payload → format notification object
            const normalized = {
                ...payload,
                notification: {
                    title: payload.data?.title || payload.notification?.title || '',
                    body:  payload.data?.body  || payload.notification?.body  || '',
                },
            };

            window.dispatchEvent(new CustomEvent('fcm-message', { detail: normalized }));
        });

        return true;
    } catch (error) {
        console.error('Error initializing Firebase:', error);
        return false;
    }
};

export const requestPermissionAndToken = async () => {
    if (!messaging) return false;

    try {
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') return false;

        const token = await getToken(messaging, {
            vapidKey: import.meta.env.VITE_FIREBASE_VAPID_KEY
        });

        if (token) {
            await axios.post('/api/fcm/register', { token });
            return true;
        }

        return false;
    } catch (error) {
        console.error('Error getting FCM token:', error);
        return false;
    }
};

export const removeToken = async () => {
    if (!messaging) return false;
    try {
        const token = await getToken(messaging, {
            vapidKey: import.meta.env.VITE_FIREBASE_VAPID_KEY
        });

        if (token) {
            await deleteToken(messaging);
            await axios.post('/api/fcm/remove', { token });
            return true;
        }

        return false;
    } catch (error) {
        console.error('Error removing FCM token:', error);
        return false;
    }
};

// Expose globally untuk Alpine.js
window.initFirebase = initFirebase;
window.requestPermissionAndToken = requestPermissionAndToken;
window.removeToken = removeToken;
