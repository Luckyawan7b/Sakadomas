@extends('layouts.app')

@section('content')
    <div x-data="{ 
        activeTab: 0,
        showSaveModal: false,
        showSyncModal: false,
        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
    }">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 4000)"
                 class="mb-6 flex items-center justify-between rounded-xl bg-green-50 px-4 py-3.5 text-sm text-green-800 border border-green-200 shadow-sm dark:bg-green-500/10 dark:text-green-300 dark:border-green-500/20">
                <div class="flex items-center gap-2.5">
                    <svg class="h-5 w-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-500 hover:text-green-700 dark:text-green-400 dark:hover:text-green-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 5000)"
                 class="mb-6 flex items-center justify-between rounded-xl bg-red-50 px-4 py-3.5 text-sm text-red-800 border border-red-200 shadow-sm dark:bg-red-500/10 dark:text-red-300 dark:border-red-500/20">
                <div class="flex items-center gap-2.5">
                    <svg class="h-5 w-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Header Section --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h2 class="text-title-md2 font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    {{-- Tag / Price Icon --}}
                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-brand-50 dark:bg-brand-500/10">
                        <svg class="w-5 h-5 text-brand-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </span>
                    Harga Katalog
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1.5 max-w-xl">
                    Atur harga patokan seluruh ras ternak berdasarkan klasifikasi <strong class="text-gray-700 dark:text-gray-300">Usia</strong>, <strong class="text-gray-700 dark:text-gray-300">Kelas Berat</strong>, dan <strong class="text-gray-700 dark:text-gray-300">Jenis Kelamin</strong>. Perubahan akan diterapkan ke seluruh ternak berstatus <em>siap jual</em>.
                </p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                {{-- Tombol Sinkronisasi DB --}}
                <button type="button"
                        @click="showSyncModal = true"
                        title="Sinkronkan seluruh data harga ternak siap jual di database dengan aturan katalog saat ini"
                        class="inline-flex items-center justify-center font-medium gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition duration-200">
                    {{-- Database Sync Icon --}}
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <ellipse cx="12" cy="5" rx="9" ry="3"/>
                        <path d="M3 5v14c0 1.66 4.03 3 9 3"/>
                        <path d="M3 12c0 1.66 4.03 3 9 3"/>
                        <path d="M19 15l-2 2 2 2"/>
                        <path d="M21 17h-4"/>
                    </svg>
                    Sinkronkan DB
                </button>
            </div>
        </div>

        {{-- Info Banner --}}
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/20 dark:bg-amber-500/5">
            <div class="flex gap-3">
                <div class="shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">Perhatian — Halaman ini terhubung langsung ke Database</p>
                    <ul class="mt-1.5 space-y-1 text-xs text-amber-700 dark:text-amber-400/90">
                        <li class="flex items-start gap-1.5">
                            <span class="mt-0.5 shrink-0">•</span>
                            <span><strong>Simpan Perubahan</strong> akan memperbarui file konfigurasi <code class="bg-amber-100 dark:bg-amber-500/10 px-1 py-0.5 rounded text-amber-800 dark:text-amber-300">value.json</code> dan mengubah harga seluruh ternak <em>siap jual</em> di database sesuai klasifikasi baru.</span>
                        </li>
                        <li class="flex items-start gap-1.5">
                            <span class="mt-0.5 shrink-0">•</span>
                            <span><strong>Sinkronkan DB</strong> akan mencocokkan ulang seluruh harga ternak <em>siap jual</em> dengan aturan katalog yang sedang berlaku, tanpa mengubah konfigurasi harga.</span>
                        </li>
                        <li class="flex items-start gap-1.5">
                            <span class="mt-0.5 shrink-0">•</span>
                            <span>Setiap aksi akan memicu konfirmasi terlebih dahulu untuk mencegah kesalahan. Pastikan nilai harga sudah benar sebelum menyimpan.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Breed Navigation Tabs --}}
        <div class="mb-6 bg-white dark:bg-gray-900 rounded-xl p-1.5 shadow-sm border border-gray-200 dark:border-gray-700 flex flex-wrap gap-1">
            @foreach ($data['ternak_klasifikasi'] as $bIdx => $breed)
                <button @click="activeTab = {{ $bIdx }}"
                        type="button"
                        :class="activeTab === {{ $bIdx }} ? 'bg-brand-500 text-white shadow-md' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800'"
                        class="flex-1 min-w-[120px] inline-flex items-center justify-center py-2.5 px-4 text-sm font-semibold rounded-lg transition-all duration-200">
                    {{ $breed['breed_name'] }}
                </button>
            @endforeach
        </div>

        {{-- Form Edit Catalog --}}
        <form action="{{ route('ternak.harga.update') }}" method="POST" id="formHargaKatalog">
            @csrf

            @foreach ($data['ternak_klasifikasi'] as $bIdx => $breed)
                <div x-show="activeTab === {{ $bIdx }}" 
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($breed['age_categories'] as $aIdx => $ageCat)
                            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900 flex flex-col overflow-hidden hover:shadow-md transition-shadow duration-300">
                                
                                {{-- Card Header --}}
                                <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/50 flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-gray-800 dark:text-white text-base">
                                            {{ $ageCat['category_name'] }}
                                        </h4>
                                        <span class="text-xs text-brand-600 dark:text-brand-400 font-medium block mt-0.5">
                                            Kategori Umur: {{ $ageCat['age_range'] }}
                                        </span>
                                    </div>
                                    <div class="h-9 w-9 rounded-full bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center text-brand-500">
                                        {{-- Calendar/Clock Icon --}}
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Card Body (List of Classes) --}}
                                <div class="p-5 flex-1 space-y-5">
                                    @foreach ($ageCat['weight_classes'] as $cIdx => $wClass)
                                        <div class="pb-5 last:pb-0 border-b border-gray-100 dark:border-gray-800 last:border-0">
                                            <div class="flex justify-between items-center mb-3">
                                                <span class="text-sm font-semibold text-gray-800 dark:text-white">
                                                    Kelas {{ $wClass['class_name'] }}
                                                </span>
                                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                                    {{ $wClass['weight_range'] }}
                                                </span>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                {{-- Jantan Input --}}
                                                <div>
                                                    <label class="flex items-center gap-1.5 text-xs font-semibold text-blue-700 dark:text-blue-400 mb-1.5">
                                                        {{-- Mars ♂ Symbol Icon --}}
                                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                            <circle cx="10" cy="14" r="5"/>
                                                            <path d="M21 3l-6.5 6.5M21 3h-5M21 3v5"/>
                                                        </svg>
                                                        Jantan
                                                    </label>
                                                    <div class="relative rounded-lg">
                                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                            <span class="text-xs text-gray-400 dark:text-gray-500 font-semibold">Rp</span>
                                                        </div>
                                                        <input type="number" 
                                                               name="prices[{{ $bIdx }}][{{ $aIdx }}][{{ $cIdx }}][Jantan]" 
                                                               value="{{ $wClass['prices']['Jantan'] }}" 
                                                               required 
                                                               min="0" 
                                                               step="1000"
                                                               class="pl-9 dark:bg-gray-800 block w-full rounded-lg border border-blue-200 bg-blue-50/30 px-3 py-2 text-sm text-gray-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-blue-900/50 dark:text-white font-medium transition duration-150 hover:border-blue-300 dark:hover:border-blue-800">
                                                    </div>
                                                </div>

                                                {{-- Betina Input --}}
                                                <div>
                                                    <label class="flex items-center gap-1.5 text-xs font-semibold text-pink-700 dark:text-pink-400 mb-1.5">
                                                        {{-- Venus ♀ Symbol Icon --}}
                                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                            <circle cx="12" cy="9" r="5"/>
                                                            <path d="M12 14v7M9 18h6"/>
                                                        </svg>
                                                        Betina
                                                    </label>
                                                    <div class="relative rounded-lg">
                                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                            <span class="text-xs text-gray-400 dark:text-gray-500 font-semibold">Rp</span>
                                                        </div>
                                                        <input type="number" 
                                                               name="prices[{{ $bIdx }}][{{ $aIdx }}][{{ $cIdx }}][Betina]" 
                                                               value="{{ $wClass['prices']['Betina'] }}" 
                                                               required 
                                                               min="0" 
                                                               step="1000"
                                                               class="pl-9 dark:bg-gray-800 block w-full rounded-lg border border-pink-200 bg-pink-50/30 px-3 py-2 text-sm text-gray-800 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 dark:border-pink-900/50 dark:text-white font-medium transition duration-150 hover:border-pink-300 dark:hover:border-pink-800">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Bottom Action Bar --}}
            <div class="mt-8 p-4 md:p-5 rounded-2xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-red-50 dark:bg-red-500/10 text-red-500 rounded-xl shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-800 dark:text-white">Peringatan Aksi Kritis</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Menyimpan akan menimpa file konfigurasi dan memperbarui <strong class="text-gray-700 dark:text-gray-300">seluruh harga ternak siap jual</strong> di database. Pastikan semua harga sudah benar.</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 justify-end shrink-0">
                    <a href="{{ route('ternak.index') }}" 
                       class="inline-flex items-center justify-center font-medium gap-2 rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition duration-200">
                        Batal
                    </a>
                    <button type="button"
                            @click="showSaveModal = true"
                            class="inline-flex items-center justify-center font-semibold gap-2 rounded-xl bg-brand-500 px-5 py-2.5 text-sm text-white shadow-md hover:bg-brand-600 transition duration-200">
                        {{-- Floppy Disk / Save Icon --}}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3h11l5 5v11a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 3v5h8V3"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 14h10v7H7z"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

        {{-- ======================= MODAL: Konfirmasi Simpan Perubahan ======================= --}}
        <div x-show="showSaveModal" 
             style="display: none;"
             class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
             @click.self="showSaveModal = false">
            <div x-show="showSaveModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-md rounded-2xl bg-white p-6 dark:bg-gray-900 shadow-2xl border border-gray-200 dark:border-gray-700">
                
                {{-- Icon --}}
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-500/15">
                    <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>

                {{-- Title --}}
                <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">
                    Konfirmasi Simpan Perubahan
                </h3>

                {{-- Description --}}
                <div class="text-center mb-5">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                        Aksi ini akan melakukan <strong class="text-gray-800 dark:text-gray-200">dua operasi sekaligus</strong>:
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-3 text-left space-y-2 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start gap-2 text-xs text-gray-600 dark:text-gray-400">
                            <span class="text-brand-500 font-bold mt-px">1.</span>
                            <span>Menimpa file konfigurasi <code class="bg-gray-200 dark:bg-gray-700 px-1.5 py-0.5 rounded text-gray-700 dark:text-gray-300 text-[11px]">value.json</code> dengan harga baru.</span>
                        </div>
                        <div class="flex items-start gap-2 text-xs text-gray-600 dark:text-gray-400">
                            <span class="text-brand-500 font-bold mt-px">2.</span>
                            <span>Meng-update harga <strong class="text-gray-700 dark:text-gray-300">seluruh ternak siap jual</strong> di database secara massal (Mass Update).</span>
                        </div>
                    </div>
                    <p class="text-xs text-red-500 dark:text-red-400 mt-3 font-medium">
                        ⚠ Tindakan ini tidak dapat dibatalkan. Pastikan semua nilai harga sudah benar.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 justify-center">
                    <button type="button" @click="showSaveModal = false"
                            class="rounded-xl border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition duration-150">
                        Batal
                    </button>
                    <button type="button"
                            @click="showSaveModal = false; document.getElementById('formHargaKatalog').submit();"
                            class="rounded-xl bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-600 shadow-md transition duration-150 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Ya, Simpan Sekarang
                    </button>
                </div>
            </div>
        </div>

        {{-- ======================= MODAL: Konfirmasi Sinkronisasi DB ======================= --}}
        <div x-show="showSyncModal" 
             style="display: none;"
             class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
             @click.self="showSyncModal = false">
            <div x-show="showSyncModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-md rounded-2xl bg-white p-6 dark:bg-gray-900 shadow-2xl border border-gray-200 dark:border-gray-700">
                
                {{-- Icon --}}
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/15">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <ellipse cx="12" cy="5" rx="9" ry="3"/>
                        <path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/>
                        <path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"/>
                    </svg>
                </div>

                {{-- Title --}}
                <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center mb-2">
                    Konfirmasi Sinkronisasi Database
                </h3>

                {{-- Description --}}
                <div class="text-center mb-5">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                        Aksi ini akan <strong class="text-gray-800 dark:text-gray-200">mencocokkan ulang (sinkronisasi)</strong> seluruh harga ternak dengan status <em>siap jual</em> berdasarkan aturan katalog yang sedang berlaku.
                    </p>
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-3 text-left space-y-2 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start gap-2 text-xs text-gray-600 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>File konfigurasi <code class="bg-gray-200 dark:bg-gray-700 px-1.5 py-0.5 rounded text-gray-700 dark:text-gray-300 text-[11px]">value.json</code> <strong>tidak akan diubah</strong>.</span>
                        </div>
                        <div class="flex items-start gap-2 text-xs text-gray-600 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01"/>
                            </svg>
                            <span>Harga ternak yang tidak sesuai aturan katalog akan dikoreksi secara otomatis.</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                        Gunakan fitur ini apabila ada ternak yang harganya tidak sesuai akibat perubahan data profil (usia/berat) secara manual.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 justify-center">
                    <button type="button" @click="showSyncModal = false"
                            class="rounded-xl border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition duration-150">
                        Batal
                    </button>
                    <form action="{{ route('ternak.harga.sync') }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit"
                                @click="showSyncModal = false"
                                class="rounded-xl bg-red-500 px-6 py-2.5 text-sm font-semibold text-white hover:bg-red-600 shadow-md transition duration-150 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <ellipse cx="12" cy="5" rx="9" ry="3"/>
                                <path d="M3 5v14c0 1.66 4.03 3 9 3"/>
                                <path d="M3 12c0 1.66 4.03 3 9 3"/>
                                <path d="M19 15l-2 2 2 2"/>
                                <path d="M21 17h-4"/>
                            </svg>
                            Ya, Sinkronkan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
