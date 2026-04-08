@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl">
    {{-- Header & Tombol Kembali --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('ternak.index') }}" class="inline-flex items-center justify-center rounded-full bg-gray-100 p-2 text-gray-500 hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Analisis Pertumbuhan #ID-{{ $ternak->id_ternak }}
            </h2>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-3 2xl:gap-7.5">

        {{-- KOTAK 1: IDENTITAS & STATUS SAAT INI (Ambil 1 Kolom) --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-default dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-bold text-black dark:text-white">Profil Ternak</h4>
                <span class="rounded-md bg-brand-500/10 px-3 py-1 text-xs font-medium text-brand-500 capitalize">{{ $ternak->jenis_ternak->jenis_ternak ?? 'Tidak Diketahui' }}</span>
            </div>

            <div class="flex flex-col gap-3">
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Jenis Kelamin</span>
                    <span class="font-medium text-black dark:text-white capitalize">{{ $ternak->jenis_kelamin }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Lokasi</span>
                    <span class="font-medium text-black dark:text-white">Kandang {{ $ternak->kamar->kandang->nomor_kandang ?? '-' }}, Kamar {{ $ternak->kamar->nomor_kamar ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Usia Saat Ini</span>
                    <span class="font-bold text-brand-500">{{ $ternak->usia }} Bulan</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Berat Saat Ini</span>
                    <span class="font-bold text-brand-500">{{ $ternak->berat }} Kg</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Kesehatan</span>
                    @if(strtolower($ternak->status_ternak) == 'sehat')
                        <span class="text-green-600 font-medium">Sehat</span>
                    @elseif(strtolower($ternak->status_ternak) == 'sakit')
                        <span class="text-red-500 font-medium">Sakit</span>
                    @else
                        <span class="text-purple-500 font-medium">Hamil</span>
                    @endif
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2 dark:border-gray-800 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Status Pasar</span>
                    <span class="font-medium text-gray-800 dark:text-gray-300 uppercase">{{ $ternak->status_jual }}</span>
                </div>
                <div class="flex justify-between text-sm pt-1">
                    <span class="text-gray-500 dark:text-gray-400">Pembaruan Terakhir</span>
                    <span class="font-medium text-black dark:text-white">{{ $ternak->last_monitor ? \Carbon\Carbon::parse($ternak->last_monitor)->format('d/m/Y') : '-' }}</span>
                </div>
            </div>
        </div>

        {{-- KOTAK 2: RIWAYAT MEDIS & PENYAKIT (Ambil 2 Kolom di Layar Besar) --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-default dark:border-gray-800 dark:bg-gray-900 xl:col-span-2 flex flex-col h-[350px]">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-bold text-black dark:text-white">Catatan Riwayat Medis (Penyakit)</h4>
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100 text-red-500 dark:bg-red-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            {{-- Wrapper agar bisa di-scroll jika datanya banyak --}}
            <div class="overflow-y-auto pr-2 custom-scrollbar flex-1">
                @forelse($riwayat_penyakit as $sakit)
                    <div class="relative border-l-2 border-red-400 pl-4 py-2 mb-4 dark:border-red-500/50">
                        <div class="absolute -left-[9px] top-4 h-4 w-4 rounded-full bg-white border-4 border-red-400 dark:bg-gray-900 dark:border-red-500/80"></div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mb-1">{{ \Carbon\Carbon::parse($sakit->tgl_monitoring)->translatedFormat('d F Y') }} (Usia: {{ $sakit->usia }} Bln)</p>
                        <h5 class="text-sm font-semibold text-gray-800 dark:text-white">{{ $sakit->penyakit }}</h5>
                        <p class="text-xs mt-1 text-gray-500">Berat saat sakit: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $sakit->berat }} Kg</span></p>
                    </div>
                @empty
                    <div class="flex h-full flex-col items-center justify-center opacity-70">
                        <svg class="w-12 h-12 text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Hewan ini tidak memiliki riwayat penyakit.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

{{-- KOTAK 3: GRAFIK KOMPARASI (Lebar Penuh) --}}
    <div class="mt-4 md:mt-6 rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 shadow-default dark:border-gray-800 dark:bg-gray-900"
         x-data="{ filterAktif: 'semua' }">

        <div class="mb-4 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h4 class="text-xl font-bold text-black dark:text-white">Grafik Pertumbuhan Ideal vs Aktual</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Garis <span class="text-gray-500 dark:text-gray-400 font-bold">Abu-abu</span> = Ideal, Garis <span class="text-brand-500 font-bold">Biru</span> = Aktual.</p>
            </div>

            {{-- Tombol Filter Rentang Usia --}}
            <div class="inline-flex rounded-lg border border-gray-200 bg-gray-50 p-1 dark:border-gray-700 dark:bg-gray-800 self-start sm:self-auto w-full sm:w-auto overflow-x-auto">
                <button @click="filterAktif = '0-6'; filterChart(0, 6)"
                        :class="filterAktif === '0-6' ? 'bg-white shadow-sm text-brand-500 dark:bg-gray-700 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                        class="px-3 py-1.5 text-xs font-medium rounded-md whitespace-nowrap transition-all">
                    0-6 Bln
                </button>
                <button @click="filterAktif = '6-12'; filterChart(6, 12)"
                        :class="filterAktif === '6-12' ? 'bg-white shadow-sm text-brand-500 dark:bg-gray-700 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                        class="px-3 py-1.5 text-xs font-medium rounded-md whitespace-nowrap transition-all">
                    6-12 Bln
                </button>
                <button @click="filterAktif = '12-24'; filterChart(12, 24)"
                        :class="filterAktif === '12-24' ? 'bg-white shadow-sm text-brand-500 dark:bg-gray-700 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                        class="px-3 py-1.5 text-xs font-medium rounded-md whitespace-nowrap transition-all">
                    12-24 Bln
                </button>
                <button @click="filterAktif = 'semua'; filterChart(0, 24)"
                        :class="filterAktif === 'semua' ? 'bg-brand-500 shadow-sm text-white' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                        class="px-3 py-1.5 text-xs font-medium rounded-md whitespace-nowrap transition-all">
                    Semua (0-24)
                </button>
            </div>
        </div>

        <div id="chartPertumbuhan" class="w-full h-[320px] sm:h-[400px]"></div>
    </div>
</div>

{{-- Panggil Library ApexCharts --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Simpan Data Penuh ke dalam Variabel JS
        const fullLabels = @json($chartLabels);
        const fullAktual = @json($chartAktual);
        const fullIdeal = @json($chartIdeal);

        // 2. Konfigurasi Dasar ApexCharts
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
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#3B82F6', '#6B7280'], // Biru & Abu-abu Gelap
            stroke: {
                curve: 'straight',
                width: 3,
                dashArray: [0, 5] // Garis aktual solid (0), garis ideal putus-putus (5)
            },
            markers: {
                size: 5, // Titik hanya di garis aktual
                hover: { size: 8 }
            },
            xaxis: {
                categories: fullLabels,
                labels: { style: { colors: '#9CA3AF' } },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                title: { text: 'Berat (Kg)', style: { color: '#9CA3AF', fontWeight: 500 } },
                labels: { style: { colors: '#9CA3AF' } }
            },
            grid: {
                borderColor: '#E5E7EB',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                labels: {
                    colors: document.documentElement.classList.contains('dark') ? '#F3F4F6' : '#374151'
                }
            },
            tooltip: {
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                y: {
                    formatter: function (val) {
                        return val !== null ? val + " Kg" : "Data Kosong";
                    }
                }
            },
            // 3. PENGATURAN RESPONSIF UNTUK MOBILE
            responsive: [{
                breakpoint: 640, // Jika layar di bawah 640px (Mobile)
                options: {
                    chart: {
                        height: 320 // Tinggi grafik diperkecil
                    },
                    legend: {
                        position: 'bottom', // Legenda pindah ke bawah agar tidak sempit
                        horizontalAlign: 'center'
                    },
                    markers: {
                        size: 3 // Titik diperkecil sedikit
                    }
                }
            }]
        };

        // Render Grafik Pertama Kali
        window.chartTernak = new ApexCharts(document.querySelector("#chartPertumbuhan"), options);
        window.chartTernak.render();

        // 4. FUNGSI FILTER GRAFIK
        window.filterChart = function(startMonth, endMonth) {
            // Memotong array data sesuai bulan yang diminta (misal: 0 sampai 6)
            // ditambah 1 karena fungsi slice() tidak memasukkan index terakhir
            const slicedLabels = fullLabels.slice(startMonth, endMonth + 1);
            const slicedAktual = fullAktual.slice(startMonth, endMonth + 1);
            const slicedIdeal = fullIdeal.slice(startMonth, endMonth + 1);

            // Update Sumbu X (Label Bulan)
            window.chartTernak.updateOptions({
                xaxis: {
                    categories: slicedLabels
                }
            });

            // Update Garis Data (Berat Aktual & Ideal)
            window.chartTernak.updateSeries([
                { name: 'Berat Aktual (Lapangan)', data: slicedAktual },
                { name: 'Standar Ideal ({{ $ternak->jenis_ternak->jenis_ternak ?? "Domba" }} {{ $ternak->jenis_kelamin }})', data: slicedIdeal }
            ]);
        };
    });
</script>

<style>
    /* CSS untuk scrollbar area riwayat penyakit */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E5E7EB; border-radius: 10px; }

    /* CSS Paksaan (Override) untuk Tooltip ApexCharts */
    .apexcharts-tooltip {
        color: #1f2937 !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
    }
    .apexcharts-tooltip-title {
        color: #111827 !important;
        background-color: #f3f4f6 !important;
        border-bottom: 1px solid #e5e7eb !important;
        font-weight: 600 !important;
    }

    /* Adaptasi untuk Dark Mode */
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
