@extends('layouts.app')

@section('content')
    <div x-data="{modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }} }">
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

        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-title-md2 font-bold text-black dark:text-white">Data Kandang</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau kapasitas dan isi setiap kandang.</p>
            </div>

            <button @click="modalTambah = true" type="button"
                class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-green-500 text-white shadow-theme-xs hover:bg-green-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Kandang
            </button>
        </div>

        {{-- MODAL TAMBAH KANDANG --}}
        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="modalTambah = false">
                <div class="no-scrollbar relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-6">
                        <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Tambah Kandang Baru</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Masukkan detail kandang di bawah ini.</p>
                    </div>

                    @if ($errors->any() && !old('_method'))
                        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('kandang.store') }}" class="flex flex-col gap-5">
                        @csrf
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Kandang</label>
                            <input type="number" name="nomor_kandang" required placeholder="Contoh: 3"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kapasitas (Jumlah Kamar)</label>
                            <input type="number" name="kapasitas" required min="1" placeholder="Contoh: 10"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        </div>
                        <div class="flex items-center gap-3 mt-2 justify-end">
                            <button @click="modalTambah = false" type="button"
                                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 sm:w-auto">
                                Batal
                            </button>
                            <button type="submit"
                                class="flex w-full justify-center rounded-lg bg-green-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-600 sm:w-auto">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    {{-- Card Kandang --}}
    @php
        use App\Models\kamarModel;
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($data_kandang as $kandang)
            @php
                $jumlah_kamar = kamarModel::where('id_kandang', $kandang->id_kandang)->count();
                $persen = $kandang->kapasitas > 0 ? round(($jumlah_kamar / $kandang->kapasitas) * 100) : 0;

                if ($persen >= 100) {
                    $barColor = 'bg-red-500';
                    $statusText = 'Penuh';
                    $statusBadge = 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400';
                } elseif ($persen >= 70) {
                    $barColor = 'bg-amber-500';
                    $statusText = 'Hampir Penuh';
                    $statusBadge = 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400';
                } elseif ($persen > 0) {
                    $barColor = 'bg-brand-500';
                    $statusText = 'Tersedia';
                    $statusBadge = 'bg-brand-100 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400';
                } else {
                    $barColor = 'bg-gray-300';
                    $statusText = 'Kosong';
                    $statusBadge = 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400';
                }
            @endphp

            <div x-data="{ modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_kandang_edit') == $kandang->id_kandang ? 'true' : 'false' }}, modalHapus: false }"
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900 flex flex-col gap-4 transition hover:shadow-md">

                {{-- Header --}}
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 dark:bg-green-500/10">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72l1.189-1.19A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72M6.75 18h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .414.336.75.75.75z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Kandang</p>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Nomor {{ $kandang->nomor_kandang }}</h3>
                        </div>
                    </div>
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $statusBadge }}">
                        {{ $statusText }}
                    </span>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-3">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $jumlah_kamar }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Kamar Dibuat</p>
                    </div>
                    <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-3">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $kandang->kapasitas - $jumlah_kamar }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Slot Tersisa</p>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Penggunaan Kapasitas</span>
                        <span class="text-xs font-bold {{ $persen >= 100 ? 'text-red-600' : 'text-gray-700 dark:text-gray-300' }}">
                            {{ $jumlah_kamar }} / {{ $kandang->kapasitas }}
                        </span>
                    </div>
                    <div class="h-2.5 w-full rounded-full bg-gray-100 dark:bg-gray-800 overflow-hidden">
                        <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-500"
                            style="width: {{ min($persen, 100) }}%"></div>
                    </div>
                    <p class="text-right text-[10px] text-gray-400 mt-1">{{ $persen }}% terisi</p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('kandang.kamar', $kandang->id_kandang) }}"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg bg-brand-500 px-3 py-2 text-xs font-medium text-white hover:bg-brand-600 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Lihat Kamar
                    </a>
                    <button @click="modalEdit = true" type="button"
                        class="inline-flex items-center justify-center rounded-lg bg-amber-100 px-3 py-2 text-xs font-medium text-amber-700 hover:bg-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20 transition">
                        Edit
                    </button>
                    <button @click="modalHapus = true" type="button"
                        class="inline-flex items-center justify-center rounded-lg bg-red-100 px-3 py-2 text-xs font-medium text-red-700 hover:bg-red-200 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20 transition">
                        Hapus
                    </button>
                </div>

                {{-- MODAL EDIT --}}
                <template x-teleport="body">
                    <div x-show="modalEdit" style="display: none;"
                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                        @click.self="modalEdit = false">
                        <div class="relative w-full max-w-[500px] rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
                            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100">
                            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Edit Kandang</h4>
                            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Perbarui <strong>Kandang {{ $kandang->nomor_kandang }}</strong>.</p>

                            @if ($errors->any() && old('_method') === 'PUT' && old('id_kandang_edit') == $kandang->id_kandang)
                                <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">{{ $errors->first() }}</div>
                            @endif

                            <form method="POST" action="{{ route('kandang.update', $kandang->id_kandang) }}" class="flex flex-col gap-5">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id_kandang_edit" value="{{ $kandang->id_kandang }}">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nomor Kandang</label>
                                    <input type="number" name="nomor_kandang" value="{{ $kandang->nomor_kandang }}" required
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                </div>
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                        Kapasitas (Kamar)
                                        @if($jumlah_kamar > 0)
                                            <span class="text-amber-600 text-xs ml-1">— Min. {{ $jumlah_kamar }} (sudah ada {{ $jumlah_kamar }} kamar)</span>
                                        @endif
                                    </label>
                                    <input type="number" name="kapasitas" value="{{ $kandang->kapasitas }}" required min="{{ $jumlah_kamar ?: 1 }}"
                                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                </div>
                                <div class="flex items-center gap-3 mt-2 justify-end">
                                    <button @click="modalEdit = false" type="button"
                                        class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 sm:w-auto">Batal</button>
                                    <button type="submit"
                                        class="rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-amber-600 sm:w-auto">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </template>

                {{-- MODAL HAPUS --}}
                <template x-teleport="body">
                    <div x-show="modalHapus" style="display: none;"
                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                        @click.self="modalHapus = false">
                        <div class="relative w-full max-w-[400px] rounded-3xl bg-white p-6 dark:bg-gray-900 text-center"
                            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-500">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <h4 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">Hapus Data?</h4>
                            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Yakin ingin menghapus <strong>Kandang {{ $kandang->nomor_kandang }}</strong>? Semua kamar di dalamnya akan ikut terhapus.</p>
                            <form method="POST" action="{{ route('kandang.delete', $kandang->id_kandang) }}" class="flex justify-center gap-3">
                                @csrf
                                @method('DELETE')
                                <button @click="modalHapus = false" type="button"
                                    class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Batal</button>
                                <button type="submit" class="rounded-lg bg-red-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-red-600">Ya, Hapus!</button>
                            </form>
                        </div>
                    </div>
                </template>
            </div>
        @empty
            <div class="col-span-full rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 py-16 text-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64M3.75 21V9.349m0 0a3.001 3.001 0 003.75-.615M3.75 9.35a3 3 0 01-.621-4.72l1.189-1.19A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72"/></svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 mb-4">Belum ada kandang yang terdaftar.</p>
                <button onclick="document.querySelector('[\\@click=\"modalTambah = true\"]').click()"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-600 transition">
                    Tambah Kandang Pertama
                </button>
            </div>
        @endforelse
    </div>
@endsection
