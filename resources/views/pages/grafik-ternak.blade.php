@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl">
    {{-- Header & Tombol Kembali --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('ternak.index') }}" class="inline-flex items-center justify-center rounded-full bg-gray-100 p-2.5 text-gray-500 hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                    Analisis Pertumbuhan #ID-{{ $ternak->id_ternak }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Monitoring berat badan dan riwayat kesehatan ternak</p>
            </div>
        </div>
    </div>

    {{-- SUMMARY METRIC CARDS --}}
    <div class="grid grid-cols-2 gap-3 md:grid-cols-4 md:gap-4 mb-6">
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Berat Saat Ini</p>
            <h4 class="mt-1.5 text-2xl font-bold text-gray-800 dark:text-white">{{ $ternak->berat }} <span class="text-sm font-medium text-gray-400">Kg</span></h4>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Usia Saat Ini</p>
            <h4 class="mt-1.5 text-2xl font-bold text-brand-600 dark:text-brand-400">{{ $ternak->usia }} <span class="text-sm font-medium opacity-70">Bulan</span></h4>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Kesehatan</p>
            <div class="mt-1.5">
                @if(strtolower($ternak->status_ternak) == 'sehat')
                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800 dark:bg-green-500/20 dark:text-green-300">
                        <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-green-500"></span>Sehat
                    </span>
                @elseif(strtolower($ternak->status_ternak) == 'sakit')
                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-800 dark:bg-red-500/20 dark:text-red-300">
                        <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-red-500"></span>Sakit
                    </span>
                @elseif(strtolower($ternak->status_ternak) == 'hamil')
                    <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-1 text-xs font-medium text-purple-800 dark:bg-purple-500/20 dark:text-purple-300">
                        <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-purple-500"></span>Hamil
                    </span>
                @else
                    <span class="inline-flex items-center rounded-full bg-gray-200 px-2.5 py-1 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                        <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-gray-500"></span>Mati
                    </span>
                @endif
            </div>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Status Pasar</p>
            <h4 class="mt-1.5 text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wide">{{ $ternak->status_jual }}</h4>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:gap-6 xl:grid-cols-3">

        {{-- KOTAK 1: IDENTITAS & PROFIL --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 md:p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between mb-5">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Profil Ternak</h4>
                <span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-1 text-xs font-medium text-brand-600 ring-1 ring-inset ring-brand-500/10 dark:bg-brand-500/10 dark:text-brand-400 dark:ring-brand-500/20 capitalize">{{ $ternak->jenis_ternak->jenis_ternak ?? 'Tidak Diketahui' }}</span>
            </div>

            <div class="flex flex-col gap-3.5">
                <div class="flex justify-between items-center border-b border-gray-100 pb-3 dark:border-gray-800 text-sm">
                    <span class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Jenis Kelamin
                    </span>
                    <span class="font-medium text-gray-800 dark:text-white capitalize">{{ $ternak->jenis_kelamin }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-3 dark:border-gray-800 text-sm">
                    <span class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Lokasi
                    </span>
                    <span class="font-medium text-gray-800 dark:text-white text-right">Kandang {{ $ternak->kamar->kandang->nomor_kandang ?? '-' }}, Kamar {{ $ternak->kamar->nomor_kamar ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-3 dark:border-gray-800 text-sm">
                    <span class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Usia
                    </span>
                    <span class="font-bold text-brand-500">{{ $ternak->usia }} Bulan</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-3 dark:border-gray-800 text-sm">
                    <span class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                        Berat
                    </span>
                    <span class="font-bold text-brand-500">{{ $ternak->berat }} Kg</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-100 pb-3 dark:border-gray-800 text-sm">
                    <span class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Harga Jual
                    </span>
                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">Rp {{ number_format($ternak->harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-sm pt-0.5">
                    <span class="text-gray-500 dark:text-gray-400 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Pembaruan Terakhir
                    </span>
                    <span class="font-medium text-gray-800 dark:text-white">{{ $ternak->last_monitor ? \Carbon\Carbon::parse($ternak->last_monitor)->format('d/m/Y') : '-' }}</span>
                </div>
            </div>
        </div>

        {{-- KOTAK 2: RIWAYAT MEDIS (Ambil 2 Kolom di Layar Besar) --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5 md:p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900 xl:col-span-2 flex flex-col min-h-[300px] max-h-[420px]">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Catatan Riwayat Medis</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Timeline penyakit yang pernah diderita</p>
                </div>
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-500 dark:bg-red-500/10">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="overflow-y-auto pr-2 custom-scrollbar flex-1">
                @forelse($riwayat_penyakit as $sakit)
                    <div class="relative border-l-2 border-red-300 pl-5 py-2.5 mb-3 dark:border-red-500/40 last:mb-0">
                        <div class="absolute -left-[7px] top-4 h-3 w-3 rounded-full bg-white border-[3px] border-red-400 dark:bg-gray-900 dark:border-red-500/80"></div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-1">{{ \Carbon\Carbon::parse($sakit->tgl_monitoring)->translatedFormat('d F Y') }} (Usia: {{ $sakit->usia }} Bln)</p>
                        <h5 class="text-sm font-semibold text-gray-800 dark:text-white">{{ $sakit->penyakit }}</h5>
                        <p class="text-xs mt-1 text-gray-500">Berat saat sakit: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $sakit->berat }} Kg</span></p>
                    </div>
                @empty
                    <div class="flex h-full flex-col items-center justify-center opacity-70">
                        <svg class="w-12 h-12 text-green-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Hewan ini tidak memiliki riwayat penyakit.</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Kondisi kesehatan sangat baik!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- KOTAK 3: GRAFIK KOMPARASI (Lebar Penuh) --}}
    <div class="mt-4 md:mt-6 rounded-xl border border-gray-200 bg-white p-4 sm:p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900"
         x-data="{ filterAktif: 'semua' }">

        <div class="mb-4 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Grafik Pertumbuhan Ideal vs Aktual</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Garis <span class="text-gray-500 dark:text-gray-400 font-bold">Abu-abu</span> = Ideal, Garis <span class="text-brand-500 font-bold">Biru</span> = Aktual.</p>
            </div>

            {{-- Tombol Filter Rentang Usia --}}
            <div class="inline-flex rounded-lg border border-gray-200 bg-gray-50 p-1 dark:border-gray-700 dark:bg-gray-800 self-start sm:self-auto w-full sm:w-auto overflow-x-auto">
                <button @click="filterAktif = '0-6'; filterChart(0, 6)"
                        :class="filterAktif === '0-6' ? 'bg-white shadow-sm text-brand-500 dark:bg-gray-700 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                        class="flex-1 sm:flex-none px-3 py-2 sm:py-1.5 text-xs font-medium rounded-md whitespace-nowrap transition-all">
                    0-6 Bln
                </button>
                <button @click="filterAktif = '6-12'; filterChart(6, 12)"
                        :class="filterAktif === '6-12' ? 'bg-white shadow-sm text-brand-500 dark:bg-gray-700 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                        class="flex-1 sm:flex-none px-3 py-2 sm:py-1.5 text-xs font-medium rounded-md whitespace-nowrap transition-all">
                    6-12 Bln
                </button>
                <button @click="filterAktif = '12-24'; filterChart(12, 24)"
                        :class="filterAktif === '12-24' ? 'bg-white shadow-sm text-brand-500 dark:bg-gray-700 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                        class="flex-1 sm:flex-none px-3 py-2 sm:py-1.5 text-xs font-medium rounded-md whitespace-nowrap transition-all">
                    12-24 Bln
                </button>
                <button @click="filterAktif = 'semua'; filterChart(0, 24)"
                        :class="filterAktif === 'semua' ? 'bg-brand-500 shadow-sm text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                        class="flex-1 sm:flex-none px-3 py-2 sm:py-1.5 text-xs font-medium rounded-md whitespace-nowrap transition-all">
                    Semua (0-24)
                </button>
            </div>
        </div>

        <div id="chartPertumbuhan" class="w-full h-[300px] sm:h-[400px]"></div>
    </div>
</div>

{{-- Panggil Library ApexCharts --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fullLabels = @json($chartLabels);
        const fullAktual = @json($chartAktual);
        const fullIdeal = @json($chartIdeal);

        function isDark() {
            return document.documentElement.classList.contains('dark');
        }

        function getThemeOptions() {
            const dark = isDark();
            return {
                xaxis: {
                    labels: { style: { colors: dark ? '#9CA3AF' : '#6B7280' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    title: { text: 'Berat (Kg)', style: { color: dark ? '#9CA3AF' : '#6B7280', fontWeight: 500 } },
                    labels: { style: { colors: dark ? '#9CA3AF' : '#6B7280' } }
                },
                grid: {
                    borderColor: dark ? '#374151' : '#E5E7EB',
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                legend: {
                    labels: { colors: dark ? '#D1D5DB' : '#374151' }
                },
                tooltip: {
                    theme: dark ? 'dark' : 'light'
                }
            };
        }

        var options = {
            series: [{
                name: 'Berat Aktual (Lapangan)',
                data: fullAktual
            }, {
                name: 'Standar Ideal ({{ $ternak->jenis_ternak->jenis_ternak ?? "Domba" }} {{ $ternak->jenis_kelamin }})',
                data: fullIdeal
            }],
            chart: {
                height: 400,
                type: 'line',
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                background: 'transparent'
            },
            colors: ['#3B82F6', '#64748B'],
            stroke: {
                curve: 'straight',
                width: 3,
                dashArray: [0, 5]
            },
            markers: {
                size: 5,
                strokeWidth: 0,
                hover: { size: 8 }
            },
            xaxis: {
                categories: fullLabels,
                ...getThemeOptions().xaxis
            },
            yaxis: getThemeOptions().yaxis,
            grid: getThemeOptions().grid,
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                ...getThemeOptions().legend
            },
            tooltip: {
                ...getThemeOptions().tooltip,
                y: {
                    formatter: function (val) {
                        return val !== null ? val + " Kg" : "Data Kosong";
                    }
                }
            },
            responsive: [{
                breakpoint: 640,
                options: {
                    chart: { height: 300 },
                    legend: { position: 'bottom', horizontalAlign: 'center' },
                    markers: { size: 3 }
                }
            }]
        };

        window.chartTernak = new ApexCharts(document.querySelector("#chartPertumbuhan"), options);
        window.chartTernak.render();

        // MutationObserver untuk real-time dark mode sync
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    window.chartTernak.updateOptions(getThemeOptions(), false, false);
                }
            });
        });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

        // Fungsi Filter Grafik
        window.filterChart = function(startMonth, endMonth) {
            const slicedLabels = fullLabels.slice(startMonth, endMonth + 1);
            const slicedAktual = fullAktual.slice(startMonth, endMonth + 1);
            const slicedIdeal = fullIdeal.slice(startMonth, endMonth + 1);

            window.chartTernak.updateOptions({
                xaxis: { categories: slicedLabels }
            });

            window.chartTernak.updateSeries([
                { name: 'Berat Aktual (Lapangan)', data: slicedAktual },
                { name: 'Standar Ideal ({{ $ternak->jenis_ternak->jenis_ternak ?? "Domba" }} {{ $ternak->jenis_kelamin }})', data: slicedIdeal }
            ]);
        };
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E5E7EB; border-radius: 10px; }

    .apexcharts-tooltip {
        color: #1f2937 !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        border-radius: 8px !important;
    }
    .apexcharts-tooltip-title {
        color: #111827 !important;
        background-color: #f3f4f6 !important;
        border-bottom: 1px solid #e5e7eb !important;
        font-weight: 600 !important;
    }

    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #374151; }
    .dark .apexcharts-tooltip {
        color: #f9fafb !important;
        background-color: #1f2937 !important;
        border-color: #374151 !important;
    }
    .dark .apexcharts-tooltip-title {
        color: #f9fafb !important;
        background-color: #374151 !important;
        border-bottom: 1px solid #4b5563 !important;
    }
    .dark .apexcharts-tooltip-series-group {
        background-color: #1f2937 !important;
    }
</style>
@endsection
