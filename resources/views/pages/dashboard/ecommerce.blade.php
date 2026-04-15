@extends('layouts.app')

@section('content')
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> --}}

    @php
        // Data Ternak Utama
        $total_ternak = \App\Models\ternakModel::count() ?? 0;
        $ternak_sehat = \App\Models\ternakModel::where('status_ternak', 'sehat')->count() ?? 0;
        $ternak_sakit = \App\Models\ternakModel::where('status_ternak', 'sakit')->count() ?? 0;

        // Data Penjualan Ternak
        $jual_siap = \App\Models\ternakModel::where('status_jual', 'siap jual')->count() ?? 0;
        $jual_booking = \App\Models\ternakModel::where('status_jual', 'booking')->count() ?? 0;
        $jual_terjual = \App\Models\ternakModel::where('status_jual', 'terjual')->count() ?? 0;
        $jual_tidak = \App\Models\ternakModel::where('status_jual', 'tidak dijual')->count() ?? 0;
    @endphp

    {{-- <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6"> --}}
    {{-- <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6"> --}}
        {{-- <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"> --}}
        {{-- <div>
                <h2 class="text-2xl md:text-3xl font-bold text-black dark:text-white">
                    Dashboard
                </h2>
            </div> --}}
        <div class="grid grid-cols-12 gap-4 md:gap-6">
            <div class="col-span-12">
                {{-- <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4"> --}}
                {{-- <div class="grid grid-cols-4 gap-4 md:gap-6"> --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4">

                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Populasi Ternak</span>
                        <div class="mt-2.5 flex items-end justify-between">
                            <h3 class="text-2xl lg:text-3xl font-bold text-black dark:text-white">{{ $total_ternak }}</h3>
                            <span
                                class="flex items-center gap-1 text-[10px] lg:text-xs font-semibold text-brand-600 bg-brand-100 dark:bg-brand-500/20 dark:text-brand-400 px-2 py-0.5 rounded">
                                Ekor
                            </span>
                        </div>
                    </div>

                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Kondisi Sehat</span>
                        <div class="mt-2.5 flex items-end justify-between">
                            <h3 class="text-2xl lg:text-3xl font-bold text-black dark:text-white">{{ $ternak_sehat }}</h3>
                            <span
                                class="flex items-center gap-1 text-[10px] lg:text-xs font-semibold text-green-600 bg-green-100 dark:bg-green-500/20 dark:text-green-400 px-2 py-0.5 rounded">
                                Ekor
                            </span>
                        </div>
                    </div>

                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Kondisi Sakit</span>
                        <div class="mt-2.5 flex items-end justify-between">
                            <h3 class="text-2xl lg:text-3xl font-bold text-black dark:text-white">{{ $ternak_sakit }}</h3>
                            <span
                                class="flex items-center gap-1 text-[10px] lg:text-xs font-semibold text-red-600 bg-red-100 dark:bg-red-500/20 dark:text-red-400 px-2 py-0.5 rounded">
                                Ekor
                            </span>
                        </div>
                    </div>

                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Telah Terjual</span>
                        <div class="mt-2.5 flex items-end justify-between">
                            <h3 class="text-2xl lg:text-3xl font-bold text-black dark:text-white">{{ $jual_terjual }}</h3>
                            <span
                                class="flex items-center gap-1 text-[10px] lg:text-xs font-semibold text-purple-600 bg-purple-100 dark:bg-purple-500/20 dark:text-purple-400 px-2 py-0.5 rounded">
                                Ekor
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-span-12">
                <h1 class="text-2xl lg:text-3xl font-bold text-black dark:text-white">SEK DURUNG MARI</h1>
            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- 1. KARTU METRIK UTAMA (DIPAKSA 4 KOLOM & DIPERKECIL) --}}
        {{-- ========================================================= --}}


        {{-- ========================================================= --}}
        {{-- 2. AREA GRAFIK (2 KOLOM) --}}
        {{-- ========================================================= --}}
        {{-- <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:gap-6">

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="mb-6">
                <h4 class="text-xl font-bold text-black dark:text-white">Distribusi Status Penjualan</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pemetaan ketersediaan ternak di kandang</p>
            </div>

            <div class="mb-2 relative">
                <div id="chartPenjualan" class="mx-auto flex justify-center w-full h-[300px] z-10"></div>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-y-3 gap-x-5 mt-4">
                <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400"><span class="w-3 h-3 rounded-full bg-[#10B981]"></span> Siap Jual ({{ $jual_siap }})</div>
                <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400"><span class="w-3 h-3 rounded-full bg-[#3B82F6]"></span> Terjual ({{ $jual_terjual }})</div>
                <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400"><span class="w-3 h-3 rounded-full bg-[#F59E0B]"></span> Booking ({{ $jual_booking }})</div>
                <div class="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-gray-400"><span class="w-3 h-3 rounded-full bg-[#6B7280]"></span> Tidak Dijual ({{ $jual_tidak }})</div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900 relative overflow-hidden">
            <div class="mb-6">
                <h4 class="text-xl font-bold text-black dark:text-white">Statistik Pendapatan</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Rekapitulasi transaksi per bulan</p>
            </div>

            <div class="opacity-30 blur-sm pointer-events-none grayscale-[50%]">
                <div id="dummyChartTransaksi" class="w-full h-[300px]"></div>
            </div>

            <div class="absolute inset-0 flex flex-col items-center justify-center bg-white/40 dark:bg-gray-900/40 z-20">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xl text-center border border-gray-100 dark:border-gray-700 max-w-xs transform hover:scale-105 transition-transform duration-300">
                    <div class="w-14 h-14 bg-brand-100 dark:bg-brand-500/20 text-brand-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-laptop-code text-2xl"></i>
                    </div>
                    <h5 class="text-lg font-bold text-black dark:text-white mb-2">Dalam Pengembangan</h5>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Modul manajemen transaksi sedang dibangun oleh tim developer kami.</p>
                </div>
            </div>
        </div>

    </div> --}}
    </div>

    {{-- Panggil Library ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. KONFIGURASI CHART DONUT (STATUS PENJUALAN)
            var dataPenjualan = [{{ $jual_siap }}, {{ $jual_terjual }}, {{ $jual_booking }},
                {{ $jual_tidak }}
            ];

            var optionsPenjualan = {
                series: dataPenjualan,
                chart: {
                    type: 'donut',
                    height: 320,
                    fontFamily: 'Inter, sans-serif'
                },
                colors: ['#10B981', '#3B82F6', '#F59E0B', '#6B7280'], // Hijau, Biru, Kuning, Abu-abu
                labels: ['Siap Jual', 'Terjual', 'Booking', 'Tidak Dijual'],
                legend: {
                    show: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            background: 'transparent',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '14px',
                                    fontWeight: 500,
                                    color: '#9CA3AF'
                                },
                                value: {
                                    show: true,
                                    fontSize: '28px',
                                    fontWeight: 700,
                                    color: document.documentElement.classList.contains('dark') ? '#fff' :
                                        '#111827',
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Total Ternak',
                                    fontSize: '14px',
                                    fontWeight: 500,
                                    color: '#9CA3AF',
                                    formatter: function(w) {
                                        return w.globals.seriesTotals.reduce((a, b) => {
                                            return a + b
                                        }, 0)
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    colors: document.documentElement.classList.contains('dark') ? ['#1f2937'] : ['#ffffff'],
                    width: 3
                }
            };

            var chartPenjualan = new ApexCharts(document.querySelector("#chartPenjualan"), optionsPenjualan);
            chartPenjualan.render();


            // 2. KONFIGURASI DUMMY CHART (DIPERBAIKI)
            var optionsDummy = {
                series: [{
                    name: 'Pendapatan',
                    data:
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#3B82F6'],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '50%'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
                    labels: {
                        style: {
                            colors: '#9CA3AF'
                        }
                    }
                },
                yaxis: {
                    show: false
                },
                grid: {
                    show: false
                }
            };

            var chartDummy = new ApexCharts(document.querySelector("#dummyChartTransaksi"), optionsDummy);
            chartDummy.render();
        });
    </script>

    <style>
        /* Mengakali tooltip ApexCharts agar tidak transparan di Tailadmin */
        .apexcharts-tooltip {
            color: #1f2937 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        }

        .dark .apexcharts-tooltip {
            color: #f9fafb !important;
            background-color: #1f2937 !important;
            border-color: #374151 !important;
        }
    </style>
@endsection
