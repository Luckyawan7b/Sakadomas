@extends('layouts.app')

@section('content')
    <div x-data="{
        modalTambah: {{ $errors->any() && !old('_method') ? 'true' : 'false' }},
        modalFilter: false,
        activeTab: 'manual'
    }">
        {{-- FLASH MESSAGE --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"
                class="mb-4 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-500/10 dark:text-green-300">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-4 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-500/10 dark:text-red-300">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Riwayat Monitoring Ternak
            </h2>

            <div class="flex flex-wrap items-center gap-3">
                @php
                    $isFiltered =
                        (request()->filled('id_ternak') && request('id_ternak') !== 'semua') ||
                        request()->filled('tgl_awal') ||
                        request()->filled('tgl_akhir') ||
                        (request()->filled('kondisi') && request('kondisi') !== 'semua');
                @endphp

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button @click="modalFilter = true" type="button"
                        class="relative inline-flex items-center justify-center font-medium gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter
                        @if ($isFiltered)
                            <span class="absolute top-2 right-2 flex h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
                        @endif
                    </button>

                    <button @click="modalTambah = true; activeTab = 'manual'" type="button"
                        class="inline-flex items-center justify-center font-medium gap-2 rounded-lg transition px-4 py-3 text-sm bg-brand-500 text-white shadow-theme-xs hover:bg-brand-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                        Tambah
                    </button>
                </div>
            </div>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-4 mb-6">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Catatan</p>
                <h4 class="mt-1.5 text-2xl font-bold text-gray-800 dark:text-white">{{ $stat_total }}</h4>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Hasil Filter</p>
                <h4 class="mt-1.5 text-2xl font-bold text-brand-600 dark:text-brand-400">{{ $data_monitoring->total() }}</h4>
            </div>
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 shadow-sm dark:border-red-900/30 dark:bg-red-500/10">
                <p class="text-xs font-medium text-red-600 dark:text-red-400">Catatan Sakit</p>
                <h4 class="mt-1.5 text-2xl font-bold text-red-700 dark:text-red-300">{{ $stat_sakit }}</h4>
            </div>
            <button @click="modalTambah = true; activeTab = 'belum'" class="rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm dark:border-amber-900/30 dark:bg-amber-500/10 text-left hover:ring-2 hover:ring-amber-300 transition group">
                <div class="flex justify-between items-start">
                    <p class="text-xs font-medium text-amber-600 dark:text-amber-400">Belum Monitor (Bulan Ini)</p>
                    <svg class="w-4 h-4 text-amber-500 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                </div>
                <h4 class="mt-1.5 text-2xl font-bold text-amber-700 dark:text-amber-300">{{ $stat_belum }} <span class="text-sm font-medium opacity-70">Ekor</span></h4>
            </button>
        </div>

        {{-- MODAL FILTER ADVANCED --}}
        <template x-teleport="body">
            <div x-show="modalFilter" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="modalFilter = false">
                <div class="relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-8"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-6 flex justify-between items-start">
                        <div>
                            <h4 class="mb-1 text-2xl font-semibold text-gray-800 dark:text-white/90">Filter Pencarian</h4>
                        </div>
                        <button @click="modalFilter = false"
                            class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form method="GET" action="{{ route('monitoring.index') }}" class="flex flex-col gap-4"
                          x-data='{
                              selectedKandang: "semua",
                              selectedKamar: "semua",
                              selectedTernak: "{{ request("id_ternak", "semua") }}",
                              semuaKamar: @json($data_kamar),
                              semuaTernak: @json($data_ternak),

                              get kamarTersedia() {
                                  if (this.selectedKandang === "semua") return [];
                                  if (this.selectedKandang === "kosong") return [{id_kamar: "kosong", nomor_kamar: "Kosong (Tanpa Kamar)"}];
                                  return this.semuaKamar.filter(k => k.id_kandang == this.selectedKandang);
                              },
                              get ternakTersedia() {
                                  let filtered = this.semuaTernak;
                                  if (this.selectedKandang === "kosong") {
                                      filtered = filtered.filter(t => t.id_kamar == null);
                                  } else if (this.selectedKandang !== "semua") {
                                      if (this.selectedKamar !== "semua" && this.selectedKamar !== "") {
                                          filtered = filtered.filter(t => t.id_kamar == this.selectedKamar);
                                      } else {
                                          let validKamars = this.semuaKamar.filter(k => k.id_kandang == this.selectedKandang).map(k => k.id_kamar);
                                          filtered = filtered.filter(t => validKamars.includes(t.id_kamar));
                                      }
                                  }
                                  return filtered;
                              }
                          }'>

                          <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Filter Kandang</label>
                                <select x-model="selectedKandang" @change="selectedKamar = 'semua'; selectedTernak = 'semua'"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                    <option value="semua">Semua Kandang</option>
                                    @foreach ($data_kandang as $kd)
                                        <option value="{{ $kd->id_kandang }}">Kandang {{ $kd->nomor_kandang }}</option>
                                    @endforeach
                                    <option value="kosong">Kosong (Tanpa Kandang)</option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Filter Kamar</label>
                                <select x-model="selectedKamar" @change="selectedTernak = 'semua'"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                    <option value="semua">Semua Kamar</option>
                                    <template x-for="kamar in kamarTersedia" :key="kamar.id_kamar">
                                        <option :value="kamar.id_kamar" x-text="kamar.nomor_kamar === 'Kosong (Tanpa Kamar)' ? kamar.nomor_kamar : 'Kamar ' + kamar.nomor_kamar"></option>
                                    </template>
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Ternak</label>
                                <select name="id_ternak" x-model="selectedTernak"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-brand-500 bg-transparent px-4 py-2 text-sm text-gray-800 ring-1 ring-brand-500 dark:border-brand-500 dark:text-white">
                                    <option value="semua">Semua Ternak</option>
                                    <template x-for="t in ternakTersedia" :key="t.id_ternak">
                                        <option :value="t.id_ternak" x-text="'#ID-' + t.id_ternak"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        {{-- <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih
                                Ternak</label>
                            <select name="id_ternak"
                                class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                <option value="semua" {{ request('id_ternak') == 'semua' ? 'selected' : '' }}>Semua Ternak
                                </option>
                                @foreach ($data_ternak as $t)
                                    <option value="{{ $t->id_ternak }}"
                                        {{ request('id_ternak') == $t->id_ternak ? 'selected' : '' }}>Ternak
                                        #ID-{{ $t->id_ternak }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal
                                    Awal</label>
                                <div class="relative">
                                    <input type="text" name="tgl_awal" value="{{ request('tgl_awal') }}"
                                        x-init="flatpickr($el, { dateFormat: 'Y-m-d', locale: 'id' })"
                                        class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:text-white dark:focus:border-brand-800"
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
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal
                                    Akhir</label>
                                <div class="relative">
                                    <input type="text" name="tgl_akhir" value="{{ request('tgl_akhir') }}"
                                        x-init="flatpickr($el, { dateFormat: 'Y-m-d', locale: 'id' })"
                                        class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:text-white dark:focus:border-brand-800"
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
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kondisi</label>
                            <select name="kondisi"
                                class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                <option value="semua" {{ request('kondisi') == 'semua' ? 'selected' : '' }}>Semua Kondisi
                                </option>
                                <option value="sehat" {{ request('kondisi') == 'sehat' ? 'selected' : '' }}>Sehat (Tanpa
                                    Penyakit)</option>
                                <option value="sakit" {{ request('kondisi') == 'sakit' ? 'selected' : '' }}>Sakit (Ada
                                    Catatan Penyakit)</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-3 mt-4 justify-end">
                            <a href="{{ route('monitoring.index') }}"
                                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">Reset
                                Filter</a>
                            <button type="submit"
                                class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">Terapkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- MODAL TAMBAH MONITORING --}}

        <template x-teleport="body">
            <div x-show="modalTambah" style="display: none;"
                class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                @click.self="modalTambah = false">
                <div class="relative w-full max-w-[700px] overflow-y-auto grow rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-8"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-2">
                        <h4 class="mb-1 text-2xl font-semibold text-gray-800 dark:text-white/90">Catat Monitoring Baru</h4>
                        <p class="text-sm text-gray-500">*Usia ternak akan dihitung otomatis oleh sistem berdasarkan riwayat.</p>
                    </div>

                    @if ($errors->any() && !old('_method'))
                        <div class="mt-4 mb-2 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">
                            {{ $errors->first() }}</div>
                    @endif

                    <div class="flex border-b border-gray-200 dark:border-gray-700 mt-5 mb-6">
                        <button type="button" @click="activeTab = 'belum'"
                            :class="activeTab === 'belum' ? 'border-brand-500 text-brand-500' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                            class="flex-1 py-2.5 text-sm font-medium border-b-2 transition-colors text-center">
                            🔔 Belum Di-monitor
                        </button>
                        <button type="button" @click="activeTab = 'manual'"
                            :class="activeTab === 'manual' ? 'border-brand-500 text-brand-500' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'"
                            class="flex-1 py-2.5 text-sm font-medium border-b-2 transition-colors text-center">
                            ✍️ Pilih Manual
                        </button>
                    </div>

                    {{-- TAB 1: BELUM DI-MONITOR --}}
                    <form x-show="activeTab === 'belum'" method="POST" action="{{ route('monitoring.store') }}" class="flex flex-col gap-4"
                        x-data='{
                            selectedTernak: "",
                            ternakBelum: @json($ternakBelumMonitor),
                            autoFillBerat() {
                                let t = this.ternakBelum.find(x => x.id_ternak == this.selectedTernak);
                                if(t) {
                                    $refs.beratInputBelum.value = t.berat;
                                }
                            }
                        }'>
                        @csrf
                        @if($stat_belum > 0)
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Ternak (Wajib Monitor Bulan Ini)</label>
                                    <select name="id_ternak" x-model="selectedTernak" @change="autoFillBerat" required
                                        class="dark:bg-gray-900 h-11 w-full rounded-lg border border-brand-300 bg-brand-50/30 px-4 py-2 text-sm text-gray-800 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10 dark:border-brand-500/50 dark:bg-brand-500/5 dark:text-white dark:focus:border-brand-500 ring-1 ring-brand-500/20">
                                        <option value="" disabled>Pilih Ternak...</option>
                                        <template x-for="t in ternakBelum" :key="t.id_ternak">
                                            <option :value="t.id_ternak" x-text="'#ID-' + t.id_ternak + ' (' + t.berat + ' Kg)'"></option>
                                        </template>
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Pengecekan</label>
                                    <div class="relative">
                                        <input type="text" name="tgl_monitoring" value="{{ old('tgl_monitoring', date('Y-m-d')) }}" required
                                            x-init="flatpickr($el, { dateFormat: 'Y-m-d', locale: 'id' })"
                                            class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:text-white dark:focus:border-brand-800">
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Berat Saat Ini (Kg)</label>
                                    <input type="number" name="berat" x-ref="beratInputBelum" value="{{ old('berat') }}" required min="0"
                                        class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white">
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Penyakit / Catatan Medis <span class="text-xs text-gray-500">(Opsional)</span></label>
                                <textarea name="penyakit" rows="3" placeholder="Kosongkan jika hewan sehat..."
                                    class="dark:bg-gray-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 dark:border-gray-700 dark:text-white">{{ old('penyakit') }}</textarea>
                            </div>

                            <div class="flex items-center gap-3 mt-4 justify-end">
                                <button @click="modalTambah = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">Batal</button>
                                <button type="submit" class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">Simpan Data</button>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-12 opacity-80 border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-2xl">
                                <svg class="w-16 h-16 text-green-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-lg font-medium text-gray-700 dark:text-gray-300">Semua ternak sudah di-monitor bulan ini!</p>
                                <p class="text-sm text-gray-500 mt-1">Gunakan tab "Pilih Manual" jika ingin memonitor ulang.</p>
                            </div>
                        @endif
                    </form>

                    {{-- TAB 2: PILIH MANUAL --}}
                    <form x-show="activeTab === 'manual'" style="display: none;" method="POST" action="{{ route('monitoring.store') }}" class="flex flex-col gap-4"
                        x-data='{
                              selectedKandang: "",
                              selectedKamar: "",
                              selectedTernak: "",
                              semuaKamar: @json($data_kamar),
                              semuaTernak: @json($data_ternak),

                              get kamarTersedia() {
                                  if (this.selectedKandang === "kosong") return [{id_kamar: "kosong", nomor_kamar: "Kosong (Tanpa Kamar)"}];
                                  return this.semuaKamar.filter(k => k.id_kandang == this.selectedKandang);
                              },
                              get ternakTersedia() {
                                  let filtered = this.semuaTernak;
                                  if (this.selectedKandang === "kosong") {
                                      filtered = filtered.filter(t => t.id_kamar == null);
                                  } else if (this.selectedKamar && this.selectedKamar !== "kosong") {
                                      filtered = filtered.filter(t => t.id_kamar == this.selectedKamar);
                                  } else {
                                      return [];
                                  }
                                  return filtered;
                              },
                              autoFillBerat() {
                                  let t = this.semuaTernak.find(x => x.id_ternak == this.selectedTernak);
                                  if(t) {
                                      $refs.beratInput.value = t.berat;
                                  }
                              }
                          }'>
                        @csrf

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            {{-- Dropdown Kandang --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih
                                    Kandang</label>
                                <select x-model="selectedKandang"
                                    @change="selectedKamar = ''; selectedTernak = ''; $refs.beratInput.value = ''"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white focus:border-brand-500">
                                    <option value="" disabled>Pilih Kandang...</option>
                                    @foreach ($data_kandang as $kd)
                                        <option value="{{ $kd->id_kandang }}">Kandang {{ $kd->nomor_kandang }}</option>
                                    @endforeach
                                    <option value="kosong" >Kosong (Tanpa Kandang)
                                    </option>
                                </select>
                            </div>

                            {{-- Dropdown Kamar --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih
                                    Kamar</label>
                                <select x-model="selectedKamar" @change="selectedTernak = ''; $refs.beratInput.value = ''"
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white focus:border-brand-500">
                                    <option value="" disabled x-show="!selectedKandang">Pilih Kandang Dulu</option>
                                    <option value="" disabled x-show="selectedKandang">Pilih Kamar...</option>
                                    <template x-for="kamar in kamarTersedia" :key="kamar.id_kamar">
                                        <option :value="kamar.id_kamar" x-text="kamar.nomor_kamar"></option>
                                    </template>
                                </select>
                            </div>

                            {{-- Dropdown Ternak Terfilter --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih
                                    Ternak</label>
                                <select name="id_ternak" x-model="selectedTernak" @change="autoFillBerat" required
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-brand-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-brand-500 dark:text-white ring-1 ring-brand-500 focus:border-brand-500 focus:ring-3 focus:ring-brand-500/10">
                                    <option value="" disabled>Pilih Ternak...</option>
                                    <template x-for="t in ternakTersedia" :key="t.id_ternak">
                                        <option :value="t.id_ternak"
                                            x-text="'#ID-' + t.id_ternak + ' (' + t.berat + ' Kg)'"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mt-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal
                                    Pengecekan</label>
                                <div class="relative">
                                    <input type="text" name="tgl_monitoring"
                                        value="{{ old('tgl_monitoring', date('Y-m-d')) }}" required
                                        x-init="flatpickr($el, { dateFormat: 'Y-m-d', locale: 'id' })"
                                        class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:text-white dark:focus:border-brand-800">
                                </div>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Berat Saat
                                    Ini (Kg)</label>
                                <input type="number" name="berat" x-ref="beratInput" value="{{ old('berat') }}"
                                    required min="0" 
                                    class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white focus:border-brand-500">
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Penyakit /
                                Catatan Medis <span class="text-xs text-gray-500">(Opsional)</span></label>
                            <textarea name="penyakit" rows="3" placeholder="Kosongkan jika hewan sehat..."
                                class="dark:bg-gray-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white focus:border-brand-500">{{ old('penyakit') }}</textarea>
                        </div>

                        <div class="flex items-center gap-3 mt-4 justify-end">
                            <button @click="modalTambah = false" type="button"
                                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">Batal</button>
                            <button type="submit"
                                class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">Simpan
                                Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden">
            <div class="py-5 px-5 md:px-6 flex justify-between items-center border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Riwayat Monitoring
                    @if($data_monitoring->total() > 0)
                        <span class="ml-2 inline-flex items-center rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-600 ring-1 ring-inset ring-brand-500/10 dark:bg-brand-500/10 dark:text-brand-400 dark:ring-brand-500/20">{{ $data_monitoring->total() }} Data</span>
                    @endif
                </h4>
            </div>
            <div class="max-w-full overflow-x-auto">
                <table class="w-full table-auto min-w-[700px]">
                    <thead>
                        <tr class="bg-gray-50 text-left dark:bg-gray-800 border-b border-gray-200 dark:border-gray-800">
                            <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm">Tanggal & Ternak</th>
                            <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm">Kondisi Fisik</th>
                            <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm">Catatan Medis</th>
                            <th class="py-4 px-5 font-semibold text-gray-700 dark:text-gray-300 text-sm text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data_monitoring as $monitor)
                            <tr x-data="{
                                modalEdit: {{ $errors->any() && old('_method') === 'PUT' && old('id_monitoring_edit') == $monitor->id_monitoring ? 'true' : 'false' }},
                                modalHapus: false
                            }"
                                class="border-b border-gray-100 dark:border-gray-800 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">

                                <td class="py-4 px-5">
                                    <span class="font-medium text-gray-800 dark:text-white block">{{ \Carbon\Carbon::parse($monitor->tgl_monitoring)->translatedFormat('d M Y') }}</span>
                                    <span class="text-sm font-bold text-brand-500">#ID-{{ $monitor->id_ternak }}</span>
                                </td>

                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $monitor->berat }} Kg</div>
                                        <div class="h-4 w-px bg-gray-300 dark:bg-gray-700"></div>
                                        <div class="text-sm text-gray-500">{{ $monitor->usia }} Bulan</div>
                                    </div>
                                </td>

                                <td class="py-4 px-5">
                                    @if (empty($monitor->penyakit))
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-500/20 dark:text-green-300">
                                            <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-green-500"></span>Sehat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-800 dark:bg-red-500/20 dark:text-red-300 mb-1">
                                            <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-red-500"></span>Sakit
                                        </span>
                                        <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $monitor->penyakit }}</p>
                                    @endif
                                </td>

                                <td class="py-4 px-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="modalEdit = true" type="button"
                                            class="inline-flex items-center rounded-lg bg-amber-50 px-3 py-2 text-sm font-medium text-amber-600 transition hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-400 dark:hover:bg-amber-500/20">Edit</button>
                                        <button @click="modalHapus = true" type="button"
                                            class="inline-flex items-center rounded-lg bg-red-50 px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20">Hapus</button>
                                    </div>
                                </td>

                                {{-- MODAL EDIT MONITORING --}}
                                <template x-teleport="body">
                                    <div x-show="modalEdit" style="display: none;"
                                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                                        @click.self="modalEdit = false">
                                        <div
                                            class="relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-8">
                                            <div class="mb-6">
                                                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                                                    Edit Data Monitoring</h4>
                                            </div>

                                            <form method="POST"
                                                action="{{ route('monitoring.update', $monitor->id_monitoring) }}"
                                                class="flex flex-col gap-4">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="id_monitoring_edit"
                                                    value="{{ $monitor->id_monitoring }}">

                                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Ternak</label>
                                                        <select name="id_ternak" required
                                                            class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                            @foreach ($data_ternak as $t)
                                                                <option value="{{ $t->id_ternak }}"
                                                                    {{ $monitor->id_ternak == $t->id_ternak ? 'selected' : '' }}>
                                                                    #ID-{{ $t->id_ternak }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal</label>
                                                        <div class="relative">
                                                            <input type="text" name="tgl_monitoring"
                                                                value="{{ $monitor->tgl_monitoring }}" required
                                                                x-init="flatpickr($el, { dateFormat: 'Y-m-d', locale: 'id' })"
                                                                class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:text-white dark:focus:border-brand-800"
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
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Usia
                                                            (Bulan)
                                                        </label>
                                                        <input type="number" name="usia"
                                                            value="{{ $monitor->usia }}" required min="0"
                                                            class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Berat
                                                            (Kg)</label>
                                                        <input type="number" name="berat"
                                                            value="{{ $monitor->berat }}" required min="0"
                                                            class="dark:bg-gray-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 dark:border-gray-700 dark:text-white">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label
                                                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Penyakit
                                                        / Catatan Medis</label>
                                                    <textarea name="penyakit" rows="3"
                                                        class="dark:bg-gray-900 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 dark:border-gray-700 dark:text-white">{{ $monitor->penyakit }}</textarea>
                                                </div>

                                                <div class="flex items-center gap-3 mt-4 justify-end">
                                                    <button @click="modalEdit = false" type="button"
                                                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] sm:w-auto">Batal</button>
                                                    <button type="submit"
                                                        class="flex w-full justify-center rounded-lg bg-yellow-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-yellow-600 sm:w-auto">Simpan
                                                        Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </template>

                                {{-- MODAL HAPUS MONITORING --}}
                                <template x-teleport="body">
                                    <div x-show="modalHapus" style="display: none;"
                                        class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
                                        @click.self="modalHapus = false">
                                        <div
                                            class="relative w-full max-w-[400px] rounded-3xl bg-white p-6 dark:bg-gray-900 text-center">
                                            <h4 class="mb-2 text-xl font-semibold text-gray-800 dark:text-white/90">Hapus
                                                Riwayat?</h4>
                                            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">Yakin ingin menghapus
                                                catatan monitoring tanggal
                                                <strong>{{ \Carbon\Carbon::parse($monitor->tgl_monitoring)->format('d/m/Y') }}</strong>?
                                            </p>

                                            <form method="POST"
                                                action="{{ route('monitoring.delete', $monitor->id_monitoring) }}"
                                                class="flex justify-center gap-3">
                                                @csrf
                                                @method('DELETE')
                                                <button @click="modalHapus = false" type="button"
                                                    class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03] ">Batal</button>
                                                <button type="submit"
                                                    class="rounded-lg bg-red-500 px-6 py-2.5 text-sm font-medium text-white hover:bg-red-600">Ya,
                                                    Hapus!</button>
                                            </form>
                                        </div>
                                    </div>
                                </template>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-10 px-5 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        <p class="text-gray-500 dark:text-gray-400">Belum ada catatan monitoring yang sesuai.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($data_monitoring->hasPages())
                <div class="border-t border-gray-200 dark:border-gray-800 p-4">
                    {{ $data_monitoring->withQueryString()->links() }}
                </div>
            @endif
        </div>


    </div>
@endsection
