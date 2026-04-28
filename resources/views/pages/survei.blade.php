@extends('layouts.app')

@section('content')
    <div x-data="{
        modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
        filterStatus: 'semua'
    }">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-title-md2 font-bold text-black dark:text-white">
                    Manajemen Kunjungan
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola semua jadwal kunjungan kandang dari pelanggan.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <select x-model="filterStatus"
                        class="appearance-none rounded-lg border border-gray-300 bg-white px-4 py-3 pr-10 text-sm font-medium text-gray-700 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
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
                    class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600">
                    <svg class="fill-current w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4.5v15m7.5-7.5h-15" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Jadwal Survei Baru
                </button>
            </div>
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
                                        <span x-text="selectedText || &#39;-- Ketik Nama / Username --&#39;"
                                            :class="!selectedText ? & #39;text-gray-400 dark:text-gray-500&# 39;: & #39;&# 39;"></span>
                                        <svg class="h-4 w-4 text-gray-500 transition-transform"
                                            :class="open ? & #39;rotate-180&# 39;: & #39;&# 39;" fill="none"
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
                                                        :class="selectedId == user.id_akun ? &
                                                            #39;bg-brand-50 text-brand-600 dark:bg-brand-500/20 dark:text-brand-400&# 39;:
                                                        &
                                                        #39;&# 39;">
                                                        <div class="font-medium" x-text="user.nama"></div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400"
                                                            x-text="&#39;@&#39; + user.username"></div>
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
                                            locale: 'id',
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

        @php
            $counts = [
                'pending' => 0,
                'disetujui' => 0,
                'selesai' => 0,
                'batal' => 0,
            ];
            foreach ($data_survei as $s) {
                $st = strtolower(trim($s->status));
                if (isset($counts[$st])) {
                    $counts[$st]++;
                }
            }
        @endphp

        <div
            class="rounded-md border border-green-200 bg-white shadow-default dark:border-gray-800 dark:bg-gray-900 mb-8 mt-6">
            <div class="py-6 px-4 md:px-6 xl:px-7.5">
                <h4 class="text-xl font-semibold text-black dark:text-white">Daftar Jadwal Kunjungan</h4>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-emerald-300 text-left dark:bg-gray-800">
                            <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6 w-16">No</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6">Tanggal & Waktu</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6">Pemohon</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6">Tipe</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6 min-w-[200px]">Keterangan</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6 text-center">Status</th>
                            <th class="py-4 px-4 font-medium text-black dark:text-white xl:px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_survei as $index => $survei)
                            <tr x-data="{
                                modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_survei_edit') == $survei->id_survei ? 'true' : 'false' }},
                                modalHapus: false,
                                statusSurvei: '{{ strtolower(trim($survei->status)) }}'
                            }" x-show="filterStatus === 'semua' || filterStatus === statusSurvei"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                class="border-b border-gray-200 dark:border-gray-800">

                                <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300 font-medium">
                                    {{ \Carbon\Carbon::parse($survei->tgl_survei)->translatedFormat('d F Y, H:i') }}
                                </td>
                                <td class="py-5 px-4 xl:px-6 text-gray-800 dark:text-gray-300">
                                    <span class="font-semibold">{{ $survei->akun->nama ?? 'Akun Terhapus' }}</span><br>
                                    <span class="text-xs text-gray-500">{{ $survei->akun->no_hp ?? '-' }}</span>
                                </td>
                                <td class="py-5 px-4 xl:px-6 text-gray-600 dark:text-gray-400 text-sm">
                                    @if($survei->id_transaksi)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                            Pesanan #TRX-{{ $survei->id_transaksi }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.414L11 9.586V6z" clip-rule="evenodd"/></svg>
                                            Kunjungan Mandiri
                                        </span>
                                    @endif
                                </td>
                                <td class="py-5 px-4 xl:px-6 text-gray-600 dark:text-gray-400 text-sm">
                                    {{ $survei->ket ?: 'Tidak ada keterangan.' }}
                                </td>
                                <td class="py-5 px-4 xl:px-6 text-center">
                                    {{-- Badge Warna Status --}}
                                    @php
                                        $status = strtolower(trim($survei->status));
                                    @endphp

                                    @if ($status == 'disetujui')
                                        <span
                                            class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">Disetujui</span>
                                    @elseif($status == 'selesai')
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700 dark:bg-green-500/10 dark:text-green-400">Selesai</span>
                                    @elseif($status == 'batal')
                                        <span
                                            class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-700 dark:bg-red-500/10 dark:text-red-400">Dibatalkan</span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-sm font-medium text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400">Pending</span>
                                    @endif
                                </td>
                                <td class="py-5 px-4 xl:px-6 text-center">
                                    <div class="flex items-center justify-center space-x-3.5">
                                        <button @click="modalEdit = true" type="button"
                                            class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-yellow-500 text-white shadow-theme-xs hover:bg-yellow-600">
                                            Edit
                                        </button>
                                        <button @click="modalHapus = true" type="button"
                                            class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-2 text-sm bg-red-500 text-white shadow-theme-xs hover:bg-red-600">
                                            Batalkan
                                        </button>
                                    </div>
                                </td>

                                {{-- MODAL EDIT SURVEI --}}
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

                                            <form method="POST"
                                                action="{{ route('survei.update', $survei->id_survei) }}"
                                                class="flex flex-col gap-5"
                                                x-data="{ 
                                                    selectedDate: '{{ old('tanggal_survei', \Carbon\Carbon::parse($survei->tgl_survei)->format('Y-m-d')) }}', 
                                                    selectedTime: '{{ old('waktu_survei', \Carbon\Carbon::parse($survei->tgl_survei)->format('H:i')) }}', 
                                                    bookedTimes: [],
                                                    fetchBookedTimes() {
                                                        if (!this.selectedDate) return;
                                                        fetch('/api/jadwal/cek?tanggal=' + this.selectedDate)
                                                            .then(res => res.json())
                                                            .then(data => {
                                                                this.bookedTimes = data;
                                                                // Jangan reset jika waktu yang di-book adalah waktu asli saat ini (edit)
                                                                let originalTime = '{{ \Carbon\Carbon::parse($survei->tgl_survei)->format('H:i') }}';
                                                                if(this.bookedTimes.includes(this.selectedTime) && this.selectedTime !== originalTime) {
                                                                    this.selectedTime = '';
                                                                }
                                                            });
                                                    }
                                                }"
                                                x-init="
                                                    $watch('modalEdit', val => { 
                                                        if(val) { 
                                                            fetchBookedTimes();
                                                        } 
                                                    })
                                                ">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="id_survei_edit"
                                                    value="{{ $survei->id_survei }}">

                                                @if ($errors->any() && old('_method') === 'PUT' && old('id_survei_edit') == $survei->id_survei)
                                                    <div
                                                        class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                                                        {{ $errors->first() }}
                                                    </div>
                                                @endif

                                                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal
                                                            Survei</label>
                                                        <div class="relative">
                                                            <input type="text" name="tanggal_survei"
                                                                x-model="selectedDate"
                                                                required 
                                                                x-init="flatpickr($el, { 
                                                                    dateFormat: 'Y-m-d', 
                                                                    minDate: 'today', 
                                                                    maxDate: new Date().fp_incr(7),
                                                                    onChange: function(selectedDates, dateStr) {
                                                                        selectedDate = dateStr;
                                                                        fetchBookedTimes();
                                                                    }
                                                                })"
                                                                class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:focus:border-brand-800"
                                                                placeholder="Pilih Tanggal">

                                                            <span
                                                                class="absolute top-1/2 right-3.5 -translate-y-1/2 pointer-events-none">
                                                                <svg class="fill-gray-700 dark:fill-gray-400"
                                                                    width="14" height="14" viewBox="0 0 14 14"
                                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M4.33317 0.0830078C4.74738 0.0830078 5.08317 0.418794 5.08317 0.833008V1.24967H8.9165V0.833008C8.9165 0.418794 9.25229 0.0830078 9.6665 0.0830078C10.0807 0.0830078 10.4165 0.418794 10.4165 0.833008V1.24967L11.3332 1.24967C12.2997 1.24967 13.0832 2.03318 13.0832 2.99967V4.99967V11.6663C13.0832 12.6328 12.2997 13.4163 11.3332 13.4163H2.6665C1.70001 13.4163 0.916504 12.6328 0.916504 11.6663V4.99967V2.99967C0.916504 2.03318 1.70001 1.24967 2.6665 1.24967L3.58317 1.24967V0.833008C3.58317 0.418794 3.91896 0.0830078 4.33317 0.0830078ZM4.33317 2.74967H2.6665C2.52843 2.74967 2.4165 2.8616 2.4165 2.99967V4.24967H11.5832V2.99967C11.5832 2.8616 11.4712 2.74967 11.3332 2.74967H9.6665H4.33317ZM11.5832 5.74967H2.4165V11.6663C2.4165 11.8044 2.52843 11.9163 2.6665 11.9163H11.3332C11.4712 11.9163 11.5832 11.8044 11.5832 11.6663V5.74967Z"
                                                                        fill="" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                            Waktu Survei (Jam Operasional)
                                                        </label>

                                                        {{-- Container Alpine.js untuk mengelola pilihan jam --}}
                                                        <div x-data="{ selectedTime: '{{ old('waktu_survei', \Carbon\Carbon::parse($survei->tgl_survei)->format('H:i')) }}' }">
                                                            {{-- Input Hidden untuk mengirim data ke Backend --}}
                                                            <input type="hidden" name="waktu_survei"
                                                                x-model="selectedTime" required>

                                                            {{-- Grid Tombol Jam --}}
                                                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                                                                <template
                                                                    x-for="time in ['09:00', '11:00', '13:00', '15:00']"
                                                                    :key="time">
                                                                    <button type="button"
                                                                        :class="selectedTime === time ?
                                                                            'bg-brand-500 text-white border-brand-500 shadow-md transform scale-105' :
                                                                            'bg-white text-gray-700 border-gray-300 hover:border-brand-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700'"
                                                                        class="flex items-center justify-center rounded-lg border px-3 py-2.5 text-sm font-semibold transition-all duration-200 opacity-70 cursor-not-allowed"
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
                                                    <label
                                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Keterangan</label>
                                                    <textarea name="ket" rows="3"
                                                        class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ $survei->ket }}</textarea>
                                                </div>

                                                    <div x-data="{ status: '{{ strtolower(trim($survei->status)) }}' }">
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status
                                                            Persetujuan</label>
                                                        <select name="status" x-model="status" required
                                                            class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                            <option value="pending">Pending (Menunggu)</option>
                                                            <option value="disetujui">Disetujui</option>
                                                            <option value="selesai">Selesai</option>
                                                            <option value="batal">Dibatalkan</option>
                                                        </select>

                                                        {{-- Input Keterangan Batal (Hanya muncul jika status batal) --}}
                                                        <div x-show="status === 'batal'" class="mt-4" x-transition>
                                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                                Keterangan Batal <span class="text-red-500">*</span>
                                                            </label>
                                                            <textarea name="ket_admin" rows="3" placeholder="Berikan alasan mengapa kunjungan dibatalkan..."
                                                                :required="status === 'batal'"
                                                                class="dark:bg-dark-900 w-full rounded-lg border border-red-300 bg-red-50 px-4 py-2.5 text-sm text-red-800 focus:border-red-500 focus:ring-3 focus:ring-red-500/10 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200">{{ $survei->ket_admin }}</textarea>
                                                            <p class="mt-1 text-xs text-red-500">Pesan ini akan ditampilkan kepada pelanggan.</p>
                                                        </div>
                                                    </div>

                                                <div class="flex items-center gap-3 mt-4 justify-end">
                                                    <button @click="modalEdit = false" type="button"
                                                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 sm:w-auto">
                                                        Batal
                                                    </button>
                                                    <button type="submit"
                                                        class="flex w-full justify-center rounded-lg bg-yellow-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-yellow-600 sm:w-auto">
                                                        Simpan Perubahan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </template>

                                {{-- MODAL HAPUS SURVEI --}}
                                <template x-teleport="body">
                                    <div x-show="modalHapus" style="display: none;"
                                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                                        @click.self="modalHapus = false">
                                        <div class="relative w-full max-w-[400px] rounded-3xl bg-white p-6 dark:bg-gray-900 text-center"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100">

                                            <div
                                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-500 dark:bg-red-500/20">
                                                <svg class="h-8 w-8" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                    </path>
                                                </svg>
                                            </div>

                                            <h4 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">Batalkan
                                                Jadwal?</h4>
                                            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Yakin ingin membatalkan
                                                jadwal survei tanggal
                                                <strong>{{ \Carbon\Carbon::parse($survei->tgl_survei)->format('d-m-Y') }}</strong>?
                                            </p>

                                            <form method="POST"
                                                action="{{ route('survei.delete', $survei->id_survei) }}"
                                                class="flex justify-center gap-3">
                                                @csrf
                                                @method('DELETE')
                                                <button @click="modalHapus = false" type="button"
                                                    class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                    class="rounded-lg bg-red-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-red-600">
                                                    Ya, Batalkan!
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </template>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 px-4 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data jadwal kunjungan.
                                </td>
                            </tr>
                        @endforelse

                        {{-- MESSAGE FILTER KOSONG --}}
                        @if ($data_survei->isNotEmpty())
                            <tr x-show="filterStatus === 'pending' && {{ $counts['pending'] }} === 0"
                                style="display: none;">
                                <td colspan="6" class="py-8 px-4 text-center text-gray-500 dark:text-gray-400 italic">
                                    Tidak ada jadwal survei berstatus Pending.
                                </td>
                            </tr>
                            <tr x-show="filterStatus === 'disetujui' && {{ $counts['disetujui'] }} === 0"
                                style="display: none;">
                                <td colspan="6" class="py-8 px-4 text-center text-gray-500 dark:text-gray-400 italic">
                                    Tidak ada jadwal survei yang Disetujui.
                                </td>
                            </tr>
                            <tr x-show="filterStatus === 'selesai' && {{ $counts['selesai'] }} === 0"
                                style="display: none;">
                                <td colspan="6" class="py-8 px-4 text-center text-gray-500 dark:text-gray-400 italic">
                                    Tidak ada jadwal survei yang telah Selesai.
                                </td>
                            </tr>
                            <tr x-show="filterStatus === 'batal' && {{ $counts['batal'] }} === 0" style="display: none;">
                                <td colspan="6" class="py-8 px-4 text-center text-gray-500 dark:text-gray-400 italic">
                                    Tidak ada jadwal survei yang Dibatalkan.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
