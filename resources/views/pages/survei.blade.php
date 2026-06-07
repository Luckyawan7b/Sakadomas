@extends('layouts.app')

@section('content')
    <div x-data="ajaxTable('{{ route('survei.index') }}')">
        <div x-data="{
            modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }}
        }">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 3000)"
                 class="mb-4 flex items-center justify-between rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800 border border-green-200">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 5000)"
                 class="mb-4 flex items-center justify-between rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800 border border-red-200">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ session('error') }}
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif
        <div class="mb-6 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div>
                <h2 class="text-title-md2 font-bold text-black dark:text-white">
                    Manajemen Kunjungan
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola semua jadwal kunjungan kandang dari pelanggan.</p>
            </div>

            <form id="filter-form" @submit.prevent="fetchData" @change="fetchData" class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                {{-- Search Input --}}
                <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" placeholder="Cari nama pemohon..." autocomplete="off"
                        class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-9 pr-4 text-sm text-gray-700 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                </div>

                {{-- Date Filter --}}
                <div class="relative flex-grow sm:flex-grow-0 w-full sm:w-auto flex items-center gap-2">
                    <input type="text" name="tgl_awal" placeholder="Tgl Awal"
                        x-init="flatpickr($el, { dateFormat: 'Y-m-d' })"
                        class="w-full sm:w-32 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    <span class="text-gray-500 text-sm">-</span>
                    <input type="text" name="tgl_akhir" placeholder="Tgl Akhir"
                        x-init="flatpickr($el, { dateFormat: 'Y-m-d' })"
                        class="w-full sm:w-32 rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                </div>

                {{-- Status Filter --}}
                <div class="relative flex-grow sm:flex-grow-0">
                    <select name="status"
                        class="w-full appearance-none rounded-lg border border-gray-300 bg-white py-2.5 pl-4 pr-10 text-sm font-medium text-gray-700 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                        <option value="semua">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="selesai">Selesai</option>
                        <option value="batal">Dibatalkan</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <button @click="modalTambah = true" type="button"
                    class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2.5 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600 w-full sm:w-auto">
                    <svg class="fill-current w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4.5v15m7.5-7.5h-15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Buat Jadwal
                </button>
            </form>
        </div>

        {{-- MODAL TAMBAH SURVEI --}}
        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="modalTambah = false">
                <div class="no-scrollbar relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-6">
                        <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Buat Jadwal Survei</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tentukan tanggal dan keterangan survei.</p>
                    </div>

                    @if ($errors->any() && !old('_method'))
                        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('survei.store') }}" class="flex flex-col gap-5"
                        x-data="{
                            selectedDate: '',
                            selectedTime: '',
                            bookedTimes: [],
                            fetchBookedTimes() {
                                if (!this.selectedDate) return;
                                fetch('/api/jadwal/cek?tanggal=' + this.selectedDate)
                                    .then(res => res.json())
                                    .then(data => {
                                        this.bookedTimes = data;
                                        if(this.bookedTimes.includes(this.selectedTime)) {
                                            this.selectedTime = '';
                                        }
                                    });
                            }
                        }">
                        @csrf

                        @if (Auth::user()->role === 'admin')
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pemohon
                                    (User)</label>

                                @php
                                    $oldId = old('id_akun');
                                    $oldUser = $oldId ? collect($data_akun)->firstWhere('id_akun', $oldId) : null;
                                    $oldText = $oldUser ? $oldUser->nama . ' (' . $oldUser->username . ')' : '';
                                @endphp

                                {{-- FIX: Teleport dropdown ke body agar tidak terpotong overflow-y-auto modal --}}
                                <div x-data='{
                                    open: false,
                                    search: "",
                                    selectedId: @json($oldId ?? ''),
                                    selectedText: @json($oldText ?? ''),
                                    users: @json($data_akun),
                                    rect: { top: 0, left: 0, bottom: 0, width: 0 },
                                    get filteredUsers() {
                                        if (this.search === "") return this.users;
                                        return this.users.filter(user =>
                                            user.nama.toLowerCase().includes(this.search.toLowerCase()) ||
                                            user.username.toLowerCase().includes(this.search.toLowerCase())
                                        );
                                    },
                                    toggle() {
                                        if (this.open) {
                                            this.open = false;
                                        } else {
                                            const r = this.$refs.trigger.getBoundingClientRect();
                                            this.rect = { top: r.top, left: r.left, bottom: r.bottom, width: r.width };
                                            this.open = true;
                                        }
                                    },
                                    closeIfNotTrigger(event) {
                                        if (this.$refs.trigger && this.$refs.trigger.contains(event.target)) return;
                                        this.open = false;
                                        this.search = "";
                                    },
                                    selectUser(user) {
                                        this.selectedId = user.id_akun;
                                        this.selectedText = user.nama + " (" + user.username + ")";
                                        this.open = false;
                                        this.search = "";
                                    }
                                }'
                                    class="relative">

                                    <input type="hidden" name="id_akun" x-model="selectedId" required>

                                    {{-- Trigger button --}}
                                    <div x-ref="trigger" @click="toggle()"
                                        class="flex h-11 w-full cursor-pointer items-center justify-between rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus-within:border-brand-500 focus-within:ring-3 focus-within:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                        <span x-text="selectedText || '-- Ketik Nama / Username --'"
                                            :class="!selectedText ? 'text-gray-400 dark:text-gray-500' : ''"></span>
                                        <svg class="h-4 w-4 text-gray-500 transition-transform"
                                            :class="open ? 'rotate-180' : ''" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>

                                    {{-- Panel dropdown di-teleport ke body agar tidak terpotong overflow modal --}}
                                    <template x-teleport="body">
                                        <div x-show="open" style="display: none;"
                                            @click.outside="closeIfNotTrigger($event)"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            :style="`position: fixed; top: ${rect.bottom + 4}px; left: ${rect.left}px; width: ${rect.width}px; z-index: 999999;`"
                                            class="rounded-lg border border-gray-200 bg-white shadow-xl dark:border-gray-700 dark:bg-gray-800">

                                            <div class="p-2 border-b border-gray-100 dark:border-gray-700">
                                                <input type="text" x-model="search"
                                                    placeholder="Cari nama atau username..."
                                                    class="w-full rounded-md border border-gray-200 px-3 py-2 text-sm focus:border-brand-500 focus:outline-none dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                                                    @click.stop>
                                            </div>

                                            <ul class="max-h-52 overflow-y-auto p-1">
                                                <template x-for="user in filteredUsers" :key="user.id_akun">
                                                    <li @click="selectUser(user)"
                                                        class="cursor-pointer rounded-md px-3 py-2 text-sm text-gray-800 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                                                        :class="selectedId == user.id_akun ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/20 dark:text-brand-400' : ''">
                                                        <div class="font-medium" x-text="user.nama"></div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400"
                                                            x-text="'@' + user.username"></div>
                                                    </li>
                                                </template>

                                                <li x-show="filteredUsers.length === 0"
                                                    class="px-3 py-3 text-sm text-gray-500 text-center italic">
                                                    Pengguna tidak ditemukan.
                                                </li>
                                            </ul>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal
                                    Survei</label>
                                <div class="relative">
                                    <input type="text" name="tanggal_survei" value="{{ old('tanggal_survei') }}" required
                                        x-model="selectedDate"
                                        x-init="flatpickr($el, {
                                            dateFormat: 'Y-m-d',
                                            locale: 'en',
                                            onChange: function(selectedDates, dateStr) {
                                                selectedDate = dateStr;
                                                fetchBookedTimes();
                                            }
                                        })"
                                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800"
                                        placeholder="Pilih Tanggal">

                                    <span class="absolute top-1/2 right-3.5 -translate-y-1/2 pointer-events-none">
                                        <svg class="fill-gray-700 dark:fill-gray-400" width="14" height="14"
                                            viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M4.33317 0.0830078C4.74738 0.0830078 5.08317 0.418794 5.08317 0.833008V1.24967H8.9165V0.833008C8.9165 0.418794 9.25229 0.0830078 9.6665 0.0830078C10.0807 0.0830078 10.4165 0.418794 10.4165 0.833008V1.24967L11.3332 1.24967C12.2997 1.24967 13.0832 2.03318 13.0832 2.99967V4.99967V11.6663C13.0832 12.6328 12.2997 13.4163 11.3332 13.4163H2.6665C1.70001 13.4163 0.916504 12.6328 0.916504 11.6663V4.99967V2.99967C0.916504 2.03318 1.70001 1.24967 2.6665 1.24967L3.58317 1.24967V0.833008C3.58317 0.418794 3.91896 0.0830078 4.33317 0.0830078ZM4.33317 2.74967H2.6665C2.52843 2.74967 2.4165 2.8616 2.4165 2.99967V4.24967H11.5832V2.99967C11.5832 2.8616 11.4712 2.74967 11.3332 2.74967H9.6665H4.33317ZM11.5832 5.74967H2.4165V11.6663C2.4165 11.8044 2.52843 11.9163 2.6665 11.9163H11.3332C11.4712 11.9163 11.5832 11.8044 11.5832 11.6663V5.74967Z"
                                                fill="" />
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Waktu Survei (Jam Operasional)
                                </label>

                                {{-- Container Alpine.js untuk mengelola pilihan jam --}}
                                <div x-data="{ selectedTime: '{{ old('waktu_survei', '') }}' }">
                                    {{-- Input Hidden untuk mengirim data ke Backend --}}
                                    <input type="hidden" name="waktu_survei" x-model="selectedTime" required>

                                    {{-- Grid Tombol Jam --}}
                                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                                        <template x-for="time in ['09:00', '11:00', '13:00', '15:00']"
                                            :key="time">
                                            <button type="button"
                                                @click="if(!bookedTimes.includes(time)) selectedTime = time"
                                                :disabled="bookedTimes.includes(time)"
                                                :class="{
                                                    'bg-brand-500 text-white border-brand-500 shadow-md transform scale-105': selectedTime === time,
                                                    'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed dark:bg-gray-800 dark:text-gray-600 dark:border-gray-700': bookedTimes.includes(time),
                                                    'bg-white text-gray-700 border-gray-300 hover:border-brand-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700': selectedTime !== time && !bookedTimes.includes(time)
                                                }"
                                                class="flex items-center justify-center rounded-lg border px-3 py-2.5 text-sm font-semibold transition-all duration-200"
                                                x-text="time">
                                            </button>
                                        </template>
                                    </div>

                                    {{-- Pesan error jika validasi gagal --}}
                                    @error('waktu_survei')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Keterangan
                                (Opsional)</label>
                            <textarea name="ket" rows="3" placeholder="Tuliskan tujuan atau detail tambahan..."
                                class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('ket') }}</textarea>
                        </div>

                        <div class="flex items-center gap-3 mt-4 justify-end">
                            <button @click="modalTambah = false" type="button"
                                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                                Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <div class="rounded-md border border-gray-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900 mb-8 mt-6">
            <div class="py-6 px-4 md:px-6 xl:px-7.5">
                <h4 class="text-xl font-semibold text-black dark:text-white">Daftar Jadwal Kunjungan</h4>
            </div>

            <div class="max-w-full overflow-x-auto relative min-h-[300px]">
                {{-- Loading Overlay --}}
                <div x-show="isFetching" x-transition.opacity.duration.200ms
                    class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm">
                    <svg class="animate-spin h-8 w-8 text-brand-500 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-sm text-gray-500 font-medium">Memuat data kunjungan...</p>
                </div>

                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 text-left dark:bg-gray-800 border-b border-gray-200 dark:border-gray-800">
                            <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6 w-16">No</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6">Tanggal & Waktu</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6">Pemohon</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6">Tipe</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6 min-w-[200px]">Keterangan</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6 text-center">Status</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody :class="{ 'opacity-25 pointer-events-none': isFetching }" class="transition-opacity duration-200">
                        <template x-for="(survei, index) in rows" :key="survei.id_survei">
                            <tr class="border-b border-gray-200 dark:border-gray-800 hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition">
                                <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300" x-text="fromData + index"></td>
                                <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300 font-medium">
                                    <span x-text="formatDateFull(survei.tgl_survei)"></span><br>
                                    <span class="text-xs text-brand-500 font-semibold" x-text="formatTime(survei.tgl_survei)"></span>
                                </td>
                                <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                    <span class="font-semibold" x-text="survei.akun.nama"></span><br>
                                    <span class="text-xs text-gray-500" x-text="survei.akun.no_hp"></span>
                                </td>
                                <td class="py-5 px-4 xl:px-6 text-gray-600 dark:text-gray-400 text-sm">
                                    <template x-if="survei.id_transaksi">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                            <span x-text="'Pesanan #TRX-' + survei.id_transaksi"></span>
                                        </span>
                                    </template>
                                    <template x-if="!survei.id_transaksi">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.414L11 9.586V6z" clip-rule="evenodd"/></svg>
                                            Kunjungan Mandiri
                                        </span>
                                    </template>
                                </td>
                                <td class="py-5 px-4 xl:px-6 text-gray-600 dark:text-gray-400 text-sm whitespace-pre-wrap" x-text="survei.ket || 'Tidak ada keterangan.'"></td>
                                <td class="py-5 px-4 xl:px-6 text-center">
                                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium"
                                        :class="{
                                            'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400': survei.status === 'disetujui',
                                            'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400': survei.status === 'selesai',
                                            'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400': survei.status === 'batal',
                                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400': survei.status === 'pending'
                                        }"
                                        x-text="survei.status.charAt(0).toUpperCase() + survei.status.slice(1)">
                                    </span>
                                </td>
                                <td class="py-5 px-4 xl:px-6 text-center">
                                    <div class="flex items-center justify-center space-x-3.5">
                                        <button @click="openEditModal(survei)" type="button"
                                            class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-yellow-500 text-white shadow-theme-xs hover:bg-yellow-600">
                                            Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="rows.length === 0 && !isFetching">
                            <td colspan="7" class="py-10 px-4 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p class="text-gray-500 dark:text-gray-400">Tidak ada data kunjungan yang sesuai dengan filter.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Client-Side Pagination Controls -->
            <div x-show="lastPage > 1" class="border-t border-gray-200 dark:border-gray-800 px-5 py-4">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Menampilkan <span class="font-medium text-gray-900 dark:text-white" x-text="fromData"></span>
                        hingga <span class="font-medium text-gray-900 dark:text-white" x-text="toData"></span>
                        dari <span class="font-medium text-gray-900 dark:text-white" x-text="totalData"></span> data
                    </p>
                    <div class="flex items-center gap-1">
                        <button @click="goToPage(currentPage - 1)" :disabled="currentPage <= 1"
                            class="inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium transition disabled:opacity-40 disabled:cursor-not-allowed border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            &laquo; Prev
                        </button>
                        <template x-for="p in paginationPages" :key="'page-'+p">
                            <button @click="if(p !== '...') goToPage(p)"
                                :class="p === currentPage ? 'bg-brand-500 text-white border-brand-500' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700'"
                                class="inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium transition border min-w-[40px]"
                                :disabled="p === '...'"
                                x-text="p"></button>
                        </template>
                        <button @click="goToPage(currentPage + 1)" :disabled="currentPage >= lastPage"
                            class="inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-medium transition disabled:opacity-40 disabled:cursor-not-allowed border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            Next &raquo;
                        </button>
                    </div>
                </div>
            </div>

            {{-- MODAL EDIT SURVEI (SHARED) --}}
            <template x-teleport="body">
                <div x-show="modalEdit" style="display: none;"
                    class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                    @click.self="modalEdit = false">
                    <div class="no-scrollbar relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100">

                        <div class="mb-6">
                            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                                Edit Jadwal Kunjungan</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui data kunjungan.</p>
                        </div>

                        @if ($errors->any() && old('_method') === 'PUT')
                            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST"
                            :action="editData ? `{{ url('/survei') }}/${editData.id_survei}` : '#'"
                            class="flex flex-col gap-5">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id_survei_edit" :value="editData ? editData.id_survei : ''">

                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Survei</label>
                                    <div class="relative">
                                        <input type="text" name="tanggal_survei"
                                            x-model="editSelectedDate" required
                                            x-init="flatpickr($el, { dateFormat: 'Y-m-d', minDate: 'today', maxDate: new Date().fp_incr(7), onChange: function(s, d) { editSelectedDate = d; fetchBookedTimes(); } })"
                                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800"
                                            placeholder="Pilih Tanggal">
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Waktu Survei (Jam Operasional)</label>
                                    <input type="hidden" name="waktu_survei" x-model="editSelectedTime" required>
                                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                                        <template x-for="time in ['09:00', '11:00', '13:00', '15:00']" :key="time">
                                            <button type="button" @click="if(!bookedTimes.includes(time) || editOriginalTime === time) editSelectedTime = time"
                                                :disabled="bookedTimes.includes(time) && editOriginalTime !== time"
                                                :class="{
                                                    'bg-brand-500 text-white border-brand-500 shadow-md transform scale-105': editSelectedTime === time,
                                                    'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed dark:bg-gray-800 dark:text-gray-600 dark:border-gray-700': bookedTimes.includes(time) && editOriginalTime !== time,
                                                    'bg-white text-gray-700 border-gray-300 hover:border-brand-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700': editSelectedTime !== time && (!bookedTimes.includes(time) || editOriginalTime === time)
                                                }"
                                                class="flex items-center justify-center rounded-lg border px-3 py-2.5 text-sm font-semibold transition-all duration-200"
                                                x-text="time">
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Keterangan</label>
                                <textarea name="ket" rows="3" :value="editData ? editData.ket : ''"
                                    class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></textarea>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status Persetujuan</label>
                                <select name="status" x-model="editStatus" required
                                    class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="pending">Pending (Menunggu)</option>
                                    <option value="disetujui">Disetujui</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="batal">Dibatalkan</option>
                                </select>

                                <div x-show="editStatus === 'batal'" class="mt-4" x-transition>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Keterangan Batal <span class="text-red-500">*</span></label>
                                    <textarea name="ket_admin" rows="3" placeholder="Berikan alasan mengapa kunjungan dibatalkan..."
                                        :required="editStatus === 'batal'" :value="editData ? editData.ket_admin : ''"
                                        class="dark:bg-dark-900 w-full rounded-lg border border-red-300 bg-red-50 px-4 py-2.5 text-sm text-red-800 focus:border-red-500 focus:ring-3 focus:ring-red-500/10 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200"></textarea>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 mt-4 justify-end">
                                <button @click="modalEdit = false" type="button"
                                    class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:w-auto">Batal</button>
                                <button type="submit"
                                    class="flex w-full justify-center rounded-lg bg-yellow-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-yellow-600 sm:w-auto">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function ajaxTable(baseUrl) {
        return {
            isFetching: false,
            abortController: null,
            rows: @json($data_survei_json),
            currentPage: {{ $data_survei->currentPage() }},
            lastPage: {{ $data_survei->lastPage() }},
            totalData: {{ $data_survei->total() }},
            fromData: {{ $data_survei->firstItem() ?? 0 }},
            toData: {{ $data_survei->lastItem() ?? 0 }},

            // Modal Edit State
            modalEdit: {{ $errors->any() && old('_method') === 'PUT' ? 'true' : 'false' }},
            editData: null,
            editSelectedDate: '',
            editSelectedTime: '',
            editOriginalTime: '',
            editStatus: 'pending',
            bookedTimes: [],

            init() {
                // Fetch awal jika belum ada data tapi ini load pertama (opsional, krn sudah ada data awal)
            },

            async fetchData() {
                this.isFetching = true;

                if (this.abortController) {
                    this.abortController.abort();
                }
                this.abortController = new AbortController();

                try {
                    const form = document.getElementById('filter-form');
                    const params = new URLSearchParams(new FormData(form));
                    params.append('page', this.currentPage);

                    const url = `${baseUrl}?${params.toString()}`;
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        signal: this.abortController.signal
                    });

                    if (!response.ok) throw new Error('Network response was not ok');

                    const json = await response.json();

                    this.rows = json.data;
                    this.currentPage = json.pagination.current_page;
                    this.lastPage = json.pagination.last_page;
                    this.totalData = json.pagination.total;
                    this.fromData = json.pagination.from || 0;
                    this.toData = json.pagination.to || 0;

                } catch (error) {
                    if (error.name !== 'AbortError') {
                        console.error("Terjadi kesalahan:", error);
                    }
                } finally {
                    this.isFetching = false;
                }
            },

            goToPage(page) {
                if (page < 1 || page > this.lastPage) return;
                this.currentPage = page;
                this.fetchData();
            },

            get paginationPages() {
                let pages = [];
                const current = this.currentPage;
                const last = this.lastPage;

                if (last <= 5) {
                    for (let i = 1; i <= last; i++) pages.push(i);
                } else {
                    if (current <= 3) {
                        pages = [1, 2, 3, 4, '...', last];
                    } else if (current >= last - 2) {
                        pages = [1, '...', last - 3, last - 2, last - 1, last];
                    } else {
                        pages = [1, '...', current - 1, current, current + 1, '...', last];
                    }
                }
                return pages;
            },

            formatDateFull(dateStr) {
                if (!dateStr) return '-';
                const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute:'2-digit' };
                return new Date(dateStr).toLocaleDateString('id-ID', options).replace('pukul', '').trim();
            },

            formatTime(dateStr) {
                if (!dateStr) return '-';
                return new Date(dateStr).toLocaleTimeString('id-ID', { hour: '2-digit', minute:'2-digit' }) + ' WIB';
            },

            openEditModal(data) {
                this.editData = data;

                // Pisahkan YYYY-MM-DD dan HH:mm
                const tgl = data.tgl_survei; // format "2024-05-12 10:00:00"
                this.editSelectedDate = tgl.split(' ')[0];
                this.editSelectedTime = tgl.split(' ')[1].substring(0, 5); // Ambil HH:mm
                this.editOriginalTime = this.editSelectedTime;

                this.editStatus = data.status;
                this.modalEdit = true;
                this.fetchBookedTimes();
            },

            fetchBookedTimes() {
                if (!this.editSelectedDate) return;
                fetch('/api/jadwal/cek?tanggal=' + this.editSelectedDate)
                    .then(res => res.json())
                    .then(data => {
                        this.bookedTimes = data;
                        // Reset pilihan waktu jika bentrok dan bukan waktu aslinya
                        if(this.bookedTimes.includes(this.editSelectedTime) && this.editSelectedTime !== this.editOriginalTime) {
                            this.editSelectedTime = '';
                        }
                    });
            }
        }
    }
</script>
@endpush
