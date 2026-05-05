<div class="relative" x-data="{
    dropdownOpen: false,
    hasPermission: ('Notification' in window) && Notification.permission === 'granted',
    isLoading: false,
    toastVisible: false,
    toastMessage: {},
    init() {
        if(window.initFirebase) {
            window.initFirebase().then(supported => {
                if (supported) {
                    if (Notification.permission !== 'denied') {
                        window.requestPermissionAndToken().then(success => {
                            this.hasPermission = success;
                        });
                    }
                }
            });
        }

        window.addEventListener('fcm-message', (e) => {
            this.toastMessage = e.detail.notification || {};
            this.toastVisible = true;
            setTimeout(() => {
                this.toastVisible = false;
            }, 6000);
        });
    },
    async toggleNotification() {
        this.isLoading = true;
        if (this.hasPermission) {
            const success = await window.removeToken();
            if(success) this.hasPermission = false;
        } else {
            const success = await window.requestPermissionAndToken();
            if (success) {
                this.hasPermission = true;
            } else {
                alert('Gagal mengaktifkan notifikasi. Pastikan Anda mengizinkan notifikasi pada pengaturan browser.');
            }
        }
        this.isLoading = false;
    }
}" @click.away="dropdownOpen = false">
    <!-- Notification Button -->
    <button
        class="relative flex items-center justify-center text-gray-500 transition-colors bg-white border border-gray-200 rounded-full hover:text-dark-900 h-11 w-11 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
        @click="dropdownOpen = !dropdownOpen"
        type="button"
    >
        <!-- Active indicator if permission is granted -->
        <span
            x-show="hasPermission"
            class="absolute right-0 top-0.5 z-1 h-2 w-2 rounded-full bg-success-500"
        >
            <span class="absolute inline-flex w-full h-full rounded-full opacity-75 bg-success-500 -z-1 animate-ping"></span>
        </span>

        <!-- Bell Icon -->
        <svg
            class="fill-current"
            width="20"
            height="20"
            viewBox="0 0 20 20"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M10.75 2.29248C10.75 1.87827 10.4143 1.54248 10 1.54248C9.58583 1.54248 9.25004 1.87827 9.25004 2.29248V2.83613C6.08266 3.20733 3.62504 5.9004 3.62504 9.16748V14.4591H3.33337C2.91916 14.4591 2.58337 14.7949 2.58337 15.2091C2.58337 15.6234 2.91916 15.9591 3.33337 15.9591H4.37504H15.625H16.6667C17.0809 15.9591 17.4167 15.6234 17.4167 15.2091C17.4167 14.7949 17.0809 14.4591 16.6667 14.4591H16.375V9.16748C16.375 5.9004 13.9174 3.20733 10.75 2.83613V2.29248ZM14.875 14.4591V9.16748C14.875 6.47509 12.6924 4.29248 10 4.29248C7.30765 4.29248 5.12504 6.47509 5.12504 9.16748V14.4591H14.875ZM8.00004 17.7085C8.00004 18.1228 8.33583 18.4585 8.75004 18.4585H11.25C11.6643 18.4585 12 18.1228 12 17.7085C12 17.2943 11.6643 16.9585 11.25 16.9585H8.75004C8.33583 16.9585 8.00004 17.2943 8.00004 17.7085Z"
                fill=""
            />
        </svg>
    </button>

    <!-- Dropdown -->
    <div
        x-show="dropdownOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute -right-20 mt-[17px] flex w-72 flex-col rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark lg:right-0"
        style="display: none;"
    >
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100 dark:border-gray-800">
            <h5 class="text-base font-semibold text-gray-800 dark:text-white/90">Notifikasi Browser</h5>
            <button @click="dropdownOpen = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="flex flex-col items-center text-center">
            <div class="mb-4">
                <!-- Icon granted -->
                <div x-show="hasPermission" class="flex items-center justify-center w-14 h-14 bg-success-50 rounded-full dark:bg-success-500/10">
                    <svg class="w-7 h-7 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <!-- Icon denied/default -->
                <div x-show="!hasPermission" class="flex items-center justify-center w-14 h-14 bg-gray-100 rounded-full dark:bg-gray-800">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.73 21a2 2 0 01-3.46 0m9.73-9.9c-.28-.27-.6-.5-.94-.71m-2.16-1.34A6.002 6.002 0 0010 5.341V5a2 2 0 10-4 0v.341c-.424.167-.822.385-1.18.647M4 17h16m-1-5.859V11c0-1.043.266-2.023.736-2.883M3 3l18 18"></path></svg>
                </div>
            </div>

            <h6 class="text-sm font-semibold text-gray-800 dark:text-white/90 mb-1" x-text="hasPermission ? 'Notifikasi Diaktifkan' : 'Aktifkan Notifikasi'"></h6>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-5 leading-relaxed">
                Dapatkan pemberitahuan real-time untuk status pesanan, kunjungan, dan update lainnya.
            </p>

            <button
                @click="toggleNotification()"
                :disabled="isLoading"
                class="flex items-center justify-center w-full px-4 py-2.5 text-sm font-medium text-white transition-colors rounded-lg shadow-theme-xs disabled:opacity-70 disabled:cursor-not-allowed"
                :class="hasPermission ? 'bg-error-500 hover:bg-error-600' : 'bg-brand-500 hover:bg-brand-600'"
            >
                <svg x-show="isLoading" class="w-4 h-4 mr-2 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span x-text="hasPermission ? 'Matikan Notifikasi' : 'Aktifkan Sekarang'"></span>
            </button>
        </div>
    </div>

    <!-- Toast Notification Overlay (Appears on bottom right) -->
    <template x-teleport="body">
        <div x-show="toastVisible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="transform translate-y-2 opacity-0"
            x-transition:enter-end="transform translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="transform translate-y-0 opacity-100"
            x-transition:leave-end="transform translate-y-2 opacity-0"
            class="fixed bottom-6 right-6 z-[999999] flex w-full max-w-sm overflow-hidden bg-white rounded-xl shadow-theme-lg dark:bg-gray-800 border border-gray-100 dark:border-gray-700 pointer-events-auto"
            style="display: none;"
        >
            <div class="flex items-center justify-center w-12 bg-brand-500">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>

            <div class="px-4 py-3 -mx-3">
                <div class="mx-3">
                    <span class="font-semibold text-brand-500 dark:text-brand-400 text-sm" x-text="toastMessage.title"></span>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1" x-text="toastMessage.body"></p>
                </div>
            </div>

            <button @click="toastVisible = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </template>
</div>
