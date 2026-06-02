@extends('layouts.app')

@section('content')
    <div x-data="{ modalTambah: false, modalEdit: false, modalBatal: false, editData: {}, batalId: null, batalTanggal: '' }">

        {{-- HEADER --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-title-md2 font-bold text-black dark:text-white">Jadwal Kunjungan</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ajukan jadwal untuk mengunjungi kandang kami secara langsung.</p>
            </div>

            <button @click="modalTambah = true" type="button"
                class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-brand-500 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-brand-500/30 hover:bg-brand-600 transition-all hover:shadow-xl hover:shadow-brand-500/40 hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Ajukan Kunjungan Baru
            </button>
        </div>

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-800 dark:bg-green-900/20 dark:border-green-800/30 dark:text-green-400" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-800 dark:bg-red-900/20 dark:border-red-800/30 dark:text-red-400">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- STATISTIK RINGKAS --}}
        @php
            $counts = ['pending' => 0, 'disetujui' => 0, 'selesai' => 0, 'batal' => 0];
            foreach ($data_survei as $s) {
                $st = strtolower(trim($s->status));
                if (isset($counts[$st])) $counts[$st]++;
            }
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-4 dark:bg-yellow-900/10 dark:border-yellow-800/30">
                <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-400">{{ $counts['pending'] }}</p>
                <p class="text-xs text-yellow-600 dark:text-yellow-500 mt-1">Menunggu</p>
            </div>
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 dark:bg-blue-900/10 dark:border-blue-800/30">
                <p class="text-2xl font-bold text-blue-700 dark:text-blue-400">{{ $counts['disetujui'] }}</p>
                <p class="text-xs text-blue-600 dark:text-blue-500 mt-1">Disetujui</p>
            </div>
            <div class="rounded-xl border border-green-200 bg-green-50 p-4 dark:bg-green-900/10 dark:border-green-800/30">
                <p class="text-2xl font-bold text-green-700 dark:text-green-400">{{ $counts['selesai'] }}</p>
                <p class="text-xs text-green-600 dark:text-green-500 mt-1">Selesai</p>
            </div>
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 dark:bg-red-900/10 dark:border-red-800/30">
                <p class="text-2xl font-bold text-red-700 dark:text-red-400">{{ $counts['batal'] }}</p>
                <p class="text-xs text-red-600 dark:text-red-500 mt-1">Dibatalkan</p>
            </div>
        </div>

        {{-- DAFTAR KUNJUNGAN --}}
        <div class="space-y-4">
            @forelse ($data_survei as $survei)
                @php $status = strtolower(trim($survei->status)); @endphp
                <div class="rounded-xl border bg-white shadow-sm dark:bg-gray-900 overflow-hidden transition-all hover:shadow-md
                    {{ $status === 'batal' ? 'border-red-200 dark:border-red-800/30' : 'border-gray-200 dark:border-gray-800' }}">
                    <div class="p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3">
                                {{-- Icon Kalender --}}
                                <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl
                                    {{ $status === 'pending' ? 'bg-yellow-100 text-yellow-600 dark:bg-yellow-500/20 dark:text-yellow-400' : '' }}
                                    {{ $status === 'disetujui' ? 'bg-blue-100 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400' : '' }}
                                    {{ $status === 'selesai' ? 'bg-green-100 text-green-600 dark:bg-green-500/20 dark:text-green-400' : '' }}
                                    {{ $status === 'batal' ? 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>

                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-white">
                                        {{ \Carbon\Carbon::parse($survei->tgl_survei)->translatedFormat('l, d F Y') }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Pukul {{ \Carbon\Carbon::parse($survei->tgl_survei)->format('H:i') }} WIB
                                    </p>
                                </div>
                            </div>

                            {{-- Badge Status --}}
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium w-fit
                                {{ $status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400' : '' }}
                                {{ $status === 'disetujui' ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400' : '' }}
                                {{ $status === 'selesai' ? 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400' : '' }}
                                {{ $status === 'batal' ? 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400' : '' }}">
                                {{ $status === 'pending' ? '⏳ Menunggu Persetujuan' : '' }}
                                {{ $status === 'disetujui' ? '✅ Disetujui' : '' }}
                                {{ $status === 'selesai' ? '🎉 Selesai' : '' }}
                                {{ $status === 'batal' ? '❌ Dibatalkan' : '' }}
                            </span>
                        </div>

                        {{-- Keterangan --}}
                        @if($survei->ket)
                            <div class="mb-3 rounded-lg bg-gray-50 dark:bg-gray-800 p-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 font-medium">Keterangan Anda:</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $survei->ket }}</p>
                            </div>
                        @endif

                        {{-- Pesan Batal Admin --}}
                        @if($status === 'batal' && $survei->ket_admin)
                            <div class="mb-3 rounded-lg bg-red-50 border border-red-200 p-3 dark:bg-red-900/20 dark:border-red-800/30">
                                <p class="text-xs font-semibold text-red-800 dark:text-red-400 mb-1">💬 Pesan dari Admin:</p>
                                <p class="text-sm text-red-700 dark:text-red-300">{{ $survei->ket_admin }}</p>
                            </div>
                        @endif

                        {{-- Tombol Aksi (hanya jika pending) --}}
                        @if($status === 'pending')
                            <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-100 dark:border-gray-800">
                                <button type="button"
                                    @click="editData = {
                                        id: {{ $survei->id_survei }},
                                        tanggal: '{{ \Carbon\Carbon::parse($survei->tgl_survei)->format('Y-m-d') }}',
                                        waktu: '{{ \Carbon\Carbon::parse($survei->tgl_survei)->format('H:i') }}',
                                        ket: `{{ addslashes($survei->ket ?? '') }}`
                                    }; modalEdit = true"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-yellow-500 px-4 py-2 text-xs font-medium text-white hover:bg-yellow-600 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit Jadwal
                                </button>
                                <button type="button"
                                    @click="batalId = {{ $survei->id_survei }}; batalTanggal = '{{ \Carbon\Carbon::parse($survei->tgl_survei)->translatedFormat('d F Y') }}'; modalBatal = true"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-red-500 px-4 py-2 text-xs font-medium text-white hover:bg-red-600 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Batalkan
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 py-16 text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-medium">Belum ada jadwal kunjungan</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mb-5">Ajukan jadwal pertama Anda untuk mengunjungi kandang kami!</p>
                    <button @click="modalTambah = true" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Ajukan Kunjungan
                    </button>
                </div>
            @endforelse
        </div>

        {{-- ================================================================ --}}
        {{-- MODAL: AJUKAN KUNJUNGAN BARU --}}
        {{-- ================================================================ --}}
        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="modalTambah = false">
                <div class="no-scrollbar relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-6">
                        <h4 class="mb-1 text-xl font-semibold text-gray-800 dark:text-white/90">Ajukan Jadwal Kunjungan</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pilih tanggal dan waktu yang sesuai (maks 7 hari ke depan).</p>
                    </div>

                    <form method="POST" action="{{ route('kunjungan.store') }}" class="flex flex-col gap-5"
                        x-data="{ 
                            selectedDate: '', 
                            selectedTime: '', 
                            bookedTimes: [],
                            loadingJadwal: false,
                            fetchBookedTimes() {
                                if (!this.selectedDate) return;
                                this.loadingJadwal = true;
                                fetch('/api/jadwal/cek?tanggal=' + this.selectedDate)
                                    .then(res => res.json())
                                    .then(data => {
                                        this.bookedTimes = data;
                                        if(this.bookedTimes.includes(this.selectedTime)) {
                                            this.selectedTime = '';
                                        }
                                    })
                                    .finally(() => {
                                        this.loadingJadwal = false;
                                    });
                            }
                        }">
                        @csrf

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                                <input type="text" name="tanggal_survei" required placeholder="Pilih Tanggal"
                                    x-model="selectedDate"
                                    x-init="flatpickr($el, { 
                                        dateFormat: 'Y-m-d', 
                                        minDate: 'today', 
                                        maxDate: new Date().fp_incr(7),
                                        onChange: function(selectedDates, dateStr) {
                                            selectedDate = dateStr;
                                            fetchBookedTimes();
                                        }
                                    })"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Waktu Kunjungan <span class="text-red-500">*</span></label>
                                <div>
                                    <input type="hidden" name="waktu_survei" x-model="selectedTime" required>
                                    <div class="grid grid-cols-2 gap-2" :class="loadingJadwal ? 'opacity-50 pointer-events-none' : ''">
                                        <template x-for="time in ['09:00', '11:00', '13:00', '15:00']" :key="time">
                                            <button type="button" 
                                                @click="if(!bookedTimes.includes(time)) selectedTime = time"
                                                :disabled="bookedTimes.includes(time) || loadingJadwal"
                                                :class="{
                                                    'bg-brand-500 text-white border-brand-500 shadow-md scale-105': selectedTime === time,
                                                    'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed dark:bg-gray-800 dark:text-gray-600 dark:border-gray-700': bookedTimes.includes(time),
                                                    'bg-white text-gray-700 border-gray-300 hover:border-brand-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700': selectedTime !== time && !bookedTimes.includes(time)
                                                }"
                                                class="flex items-center justify-center rounded-lg border px-3 py-2.5 text-sm font-semibold transition-all duration-200"
                                                x-text="time">
                                            </button>
                                        </template>
                                    </div>
                                    <p x-show="loadingJadwal" class="text-[10px] text-brand-600 mt-1 animate-pulse">Memeriksa ketersediaan...</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tujuan Kunjungan (Opsional)</label>
                            <textarea name="ket" rows="3" placeholder="Contoh: Ingin melihat koleksi domba garut..."
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></textarea>
                        </div>

                        <div class="flex items-center gap-3 justify-end">
                            <button @click="modalTambah = false" type="button"
                                class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Batal</button>
                            <button type="submit"
                                class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Ajukan Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- ================================================================ --}}
        {{-- MODAL: EDIT KUNJUNGAN --}}
        {{-- ================================================================ --}}
        <template x-teleport="body">
            <div x-show="modalEdit" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="modalEdit = false">
                <div class="no-scrollbar relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-6">
                        <h4 class="mb-1 text-xl font-semibold text-gray-800 dark:text-white/90">Edit Jadwal Kunjungan</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ubah tanggal atau waktu kunjungan Anda.</p>
                    </div>

                    <form method="POST" :action="'/kunjungan/' + editData.id" class="flex flex-col gap-5"
                        x-data="{ 
                            selectedDate: '', 
                            selectedTime: '', 
                            bookedTimes: [],
                            loadingJadwal: false,
                            fetchBookedTimes() {
                                if (!this.selectedDate) return;
                                this.loadingJadwal = true;
                                fetch('/api/jadwal/cek?tanggal=' + this.selectedDate)
                                    .then(res => res.json())
                                    .then(data => {
                                        this.bookedTimes = data;
                                        // Jangan reset jika waktu yang di-book adalah waktu asli saat ini (edit)
                                        if(this.bookedTimes.includes(this.selectedTime) && this.selectedTime !== editData.waktu) {
                                            this.selectedTime = '';
                                        }
                                    })
                                    .finally(() => {
                                        this.loadingJadwal = false;
                                    });
                            }
                        }"
                        x-init="
                            $watch('modalEdit', val => { 
                                if(val) { 
                                    selectedDate = editData.tanggal;
                                    selectedTime = editData.waktu;
                                    fetchBookedTimes();
                                } 
                            })
                        ">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                                <input type="text" name="tanggal_survei" required :value="editData.tanggal"
                                    x-init="$watch('modalEdit', val => { 
                                        if(val) { 
                                            setTimeout(() => flatpickr($el, { 
                                                dateFormat: 'Y-m-d', 
                                                minDate: 'today', 
                                                maxDate: new Date().fp_incr(7), 
                                                defaultDate: editData.tanggal,
                                                onChange: function(selectedDates, dateStr) {
                                                    selectedDate = dateStr;
                                                    fetchBookedTimes();
                                                }
                                            }), 100) 
                                        } 
                                    })"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Waktu Kunjungan <span class="text-red-500">*</span></label>
                                <div>
                                    <input type="hidden" name="waktu_survei" x-model="selectedTime" required>
                                    <div class="grid grid-cols-2 gap-2" :class="loadingJadwal ? 'opacity-50 pointer-events-none' : ''">
                                        <template x-for="time in ['09:00', '11:00', '13:00', '15:00']" :key="time">
                                            <button type="button" 
                                                @click="if(!(bookedTimes.includes(time) && time !== editData.waktu)) selectedTime = time"
                                                :disabled="(bookedTimes.includes(time) && time !== editData.waktu) || loadingJadwal"
                                                :class="{
                                                    'bg-brand-500 text-white border-brand-500 shadow-md scale-105': selectedTime === time,
                                                    'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed dark:bg-gray-800 dark:text-gray-600 dark:border-gray-700': bookedTimes.includes(time) && time !== editData.waktu,
                                                    'bg-white text-gray-700 border-gray-300 hover:border-brand-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700': selectedTime !== time && !(bookedTimes.includes(time) && time !== editData.waktu)
                                                }"
                                                class="flex items-center justify-center rounded-lg border px-3 py-2.5 text-sm font-semibold transition-all duration-200"
                                                x-text="time">
                                            </button>
                                        </template>
                                    </div>
                                    <p x-show="loadingJadwal" class="text-[10px] text-brand-600 mt-1 animate-pulse">Memeriksa ketersediaan...</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tujuan Kunjungan (Opsional)</label>
                            <textarea name="ket" rows="3" x-text="editData.ket"
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></textarea>
                        </div>

                        <div class="flex items-center gap-3 justify-end">
                            <button @click="modalEdit = false" type="button"
                                class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Batal</button>
                            <button type="submit"
                                class="rounded-lg bg-yellow-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-yellow-600">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- ================================================================ --}}
        {{-- MODAL: BATALKAN KUNJUNGAN --}}
        {{-- ================================================================ --}}
        <template x-teleport="body">
            <div x-show="modalBatal" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="modalBatal = false">
                <div class="relative w-full max-w-[400px] rounded-3xl bg-white p-6 dark:bg-gray-900 text-center"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-500 dark:bg-red-500/20">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>

                    <h4 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">Batalkan Kunjungan?</h4>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Yakin ingin membatalkan jadwal kunjungan tanggal <strong x-text="batalTanggal"></strong>?</p>

                    <form method="POST" :action="'/kunjungan/' + batalId" class="flex justify-center gap-3">
                        @csrf
                        @method('DELETE')
                        <button @click="modalBatal = false" type="button"
                            class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Tidak</button>
                        <button type="submit"
                            class="rounded-lg bg-red-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-red-600">Ya, Batalkan</button>
                    </form>
                </div>
            </div>
        </template>

    </div>
@endsection
