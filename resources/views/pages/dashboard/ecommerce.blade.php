@extends('layouts.app')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">

    {{-- ===== ROW 1: METRIC CARDS ===== --}}
    <div class="col-span-12">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4">

            {{-- Card: Total Populasi --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-brand-100 dark:bg-brand-500/20">
                        <svg class="w-5 h-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    </div>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Populasi</span>
                </div>
                <div class="flex items-end justify-between">
                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">{{ $total_ternak }}</h3>
                    <span class="text-[10px] lg:text-xs font-semibold text-brand-600 bg-brand-50 dark:bg-brand-500/20 dark:text-brand-400 px-2 py-0.5 rounded-full">Ekor</span>
                </div>
            </div>

            {{-- Card: Transaksi Baru --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-500/20">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Pesanan Baru</span>
                </div>
                <div class="flex items-end justify-between">
                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">{{ $transaksi_baru }}</h3>
                    <span class="text-[10px] lg:text-xs font-semibold text-amber-600 bg-amber-50 dark:bg-amber-500/20 dark:text-amber-400 px-2 py-0.5 rounded-full">Pending</span>
                </div>
            </div>

            {{-- Card: Pendapatan Bulan Ini --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-green-100 dark:bg-green-500/20">
                        <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Pendapatan Bulan Ini</span>
                </div>
                <div class="flex items-end justify-between">
                    <h3 class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($pendapatan_bulan_ini, 0, ',', '.') }}</h3>
                </div>
            </div>

            {{-- Card: Monitoring Reminder --}}
            <div class="rounded-2xl border {{ $total_belum_monitor > 0 ? 'border-red-200 dark:border-red-800/50' : 'border-green-200 dark:border-green-800/50' }} bg-white p-5 dark:bg-white/[0.03] transition-all hover:shadow-md">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl {{ $total_belum_monitor > 0 ? 'bg-red-100 dark:bg-red-500/20' : 'bg-green-100 dark:bg-green-500/20' }}">
                        <svg class="w-5 h-5 {{ $total_belum_monitor > 0 ? 'text-red-500' : 'text-green-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Monitoring</span>
                </div>
                <div class="flex items-end justify-between">
                    <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">{{ $persen_sudah_monitor }}%</h3>
                    <span class="text-[10px] lg:text-xs font-semibold {{ $total_belum_monitor > 0 ? 'text-red-600 bg-red-50 dark:bg-red-500/20 dark:text-red-400' : 'text-green-600 bg-green-50 dark:bg-green-500/20 dark:text-green-400' }} px-2 py-0.5 rounded-full">
                        {{ $total_belum_monitor > 0 ? $total_belum_monitor . ' Belum' : 'Tuntas' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Ringkasan Kesehatan Ternak</h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="text-center p-4 rounded-xl bg-green-50 dark:bg-green-500/10">
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $ternak_sehat }}</p>
                    <p class="text-xs text-green-700 dark:text-green-300 mt-1">Sehat</p>
                </div>
                <div class="text-center p-4 rounded-xl bg-red-50 dark:bg-red-500/10">
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $ternak_sakit }}</p>
                    <p class="text-xs text-red-700 dark:text-red-300 mt-1">Sakit</p>
                </div>
                <div class="text-center p-4 rounded-xl bg-blue-50 dark:bg-blue-500/10">
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $jual_siap }}</p>
                    <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">Siap Jual</p>
                </div>
                <div class="text-center p-4 rounded-xl bg-purple-50 dark:bg-purple-500/10">
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $jual_terjual }}</p>
                    <p class="text-xs text-purple-700 dark:text-purple-300 mt-1">Terjual</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ROW 2: CHARTS ===== --}}
    <div class="col-span-12 xl:col-span-5">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03] h-full">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Status Penjualan</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Distribusi ketersediaan ternak</p>
            <div id="chartPenjualan" class="mx-auto w-full" style="min-height:280px"></div>
            <div class="flex flex-wrap items-center justify-center gap-y-2 gap-x-4 mt-3">
                <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400"><span class="w-2.5 h-2.5 rounded-full bg-[#10B981]"></span>Siap ({{ $jual_siap }})</div>
                <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400"><span class="w-2.5 h-2.5 rounded-full bg-[#3B82F6]"></span>Terjual ({{ $jual_terjual }})</div>
                <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400"><span class="w-2.5 h-2.5 rounded-full bg-[#F59E0B]"></span>Booking ({{ $jual_booking }})</div>
                <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400"><span class="w-2.5 h-2.5 rounded-full bg-[#6B7280]"></span>Tidak Dijual ({{ $jual_tidak }})</div>
            </div>
        </div>
    </div>

    <div class="col-span-12 xl:col-span-7">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03] h-full">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Tren Pendapatan</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Rekapitulasi 6 bulan terakhir</p>
            <div id="chartPendapatan" class="w-full" style="min-height:280px"></div>
        </div>
    </div>

    {{-- ===== ROW 3: MONITORING REMINDER ===== --}}
    @if($total_belum_monitor > 0)
    <div class="col-span-12">
        <div class="rounded-2xl border border-red-200 dark:border-red-800/50 bg-red-50/50 dark:bg-red-500/5 p-5 md:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-red-100 dark:bg-red-500/20 animate-pulse">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-base font-semibold text-red-800 dark:text-red-300">Reminder Monitoring Bulanan</h4>
                        <p class="text-xs text-red-600 dark:text-red-400">{{ $total_belum_monitor }} dari {{ $total_aktif }} ekor ternak belum dimonitor bulan ini</p>
                    </div>
                </div>
                <a href="{{ route('monitoring.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    Buka Monitoring
                </a>
            </div>

            {{-- Progress Bar --}}
            <div class="w-full bg-red-100 dark:bg-red-900/30 rounded-full h-2 mb-4">
                <div class="bg-gradient-to-r from-green-400 to-green-500 h-2 rounded-full transition-all duration-500" style="width: {{ $persen_sudah_monitor }}%"></div>
            </div>

            {{-- Table: top 5 --}}
            <div class="overflow-x-auto rounded-xl border border-red-100 dark:border-red-800/30">
                <table class="w-full text-xs">
                    <thead class="bg-red-100/60 dark:bg-red-900/20">
                        <tr>
                            <th class="px-4 py-2.5 text-left font-semibold text-red-800 dark:text-red-300">ID</th>
                            <th class="px-4 py-2.5 text-left font-semibold text-red-800 dark:text-red-300">Jenis</th>
                            <th class="px-4 py-2.5 text-left font-semibold text-red-800 dark:text-red-300 hidden sm:table-cell">Lokasi</th>
                            <th class="px-4 py-2.5 text-left font-semibold text-red-800 dark:text-red-300">Terakhir Monitor</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/60 dark:bg-gray-900/30">
                        @foreach($ternak_belum_monitor->take(5) as $t)
                        <tr class="border-t border-red-100 dark:border-red-800/20">
                            <td class="px-4 py-2.5 font-medium text-gray-900 dark:text-white">#{{ $t->id_ternak }}</td>
                            <td class="capitalize px-4 py-2.5 text-gray-600 dark:text-gray-400">{{ $t->jenis_ternak->jenis_ternak ?? '-' }}</td>
                            <td class="px-4 py-2.5 text-gray-600 dark:text-gray-400 hidden sm:table-cell">
                                @if($t->kamar && $t->kamar->kandang)
                                    Kandang {{ $t->kamar->kandang->nomor_kandang }}- Kamar {{ $t->kamar->nomor_kamar }}
                                @else - @endif
                            </td>
                            <td class="px-4 py-2.5">
                                @if($t->last_monitor)
                                    <span class="text-amber-600 dark:text-amber-400">{{ \Carbon\Carbon::parse($t->last_monitor)->locale('id')->translatedFormat('d M Y') }}</span>
                                @else
                                    <span class="text-red-500 font-semibold">Belum pernah</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($total_belum_monitor > 5)
                <p class="text-xs text-red-500 dark:text-red-400 mt-2 text-center">...dan {{ $total_belum_monitor - 5 }} ternak lainnya</p>
            @endif
        </div>
    </div>
    @endif

    {{-- ===== ROW 4: TABLES ===== --}}
    {{-- Pesanan Butuh Assign --}}
    <div class="col-span-12 xl:col-span-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03] h-full">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-base font-semibold text-gray-900 dark:text-white">Pesanan Butuh Penugasan</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Belum ada ternak yang ditugaskan</p>
                </div>
                <a href="{{ route('transaksi.index') }}" class="text-xs font-medium text-brand-500 hover:text-brand-600 transition-colors">Lihat Semua →</a>
            </div>
            @if($pesanan_butuh_assign->isEmpty())
                <div class="flex flex-col items-center justify-center py-8 text-gray-400 dark:text-gray-600">
                    <svg class="w-12 h-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm">Semua pesanan sudah ditugaskan</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($pesanan_butuh_assign as $trx)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700/50">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $trx->akun->nama ?? 'N/A' }}</p>
                            <p class="capitalize text-xs text-gray-500 dark:text-gray-400">{{ $trx->jenisTernak->jenis_ternak ?? '-' }} · {{ $trx->detail_transaksi_count }}/{{ $trx->total_jumlah }} ekor</p>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400 whitespace-nowrap">{{ ucfirst($trx->status) }}</span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Kunjungan Mendatang --}}
    <div class="col-span-12 xl:col-span-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6 dark:border-gray-800 dark:bg-white/[0.03] h-full">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-base font-semibold text-gray-900 dark:text-white">Kunjungan Mendatang</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Jadwal survei yang akan datang</p>
                </div>
                <a href="{{ route('survei.index') }}" class="text-xs font-medium text-brand-500 hover:text-brand-600 transition-colors">Lihat Semua →</a>
            </div>
            @if($kunjungan_mendatang->isEmpty())
                <div class="flex flex-col items-center justify-center py-8 text-gray-400 dark:text-gray-600">
                    <svg class="w-12 h-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm">Tidak ada jadwal kunjungan</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($kunjungan_mendatang as $sv)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700/50">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $sv->akun->nama ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($sv->tgl_survei)->locale('id')->translatedFormat('l, d M Y') }}</p>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-1 rounded-full whitespace-nowrap
                            {{ $sv->status === 'disetujui' ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400' }}">
                            {{ ucfirst($sv->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ===== ROW 5: KESEHATAN TERNAK SUMMARY ===== --}}


</div>

{{-- ApexCharts --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#9CA3AF' : '#6B7280';
    const bgStroke = isDark ? '#1f2937' : '#ffffff';

    // Donut Chart
    new ApexCharts(document.querySelector("#chartPenjualan"), {
        series: [{{ $jual_siap }}, {{ $jual_terjual }}, {{ $jual_booking }}, {{ $jual_tidak }}],
        chart: { type: 'donut', height: 300, fontFamily: 'Inter, sans-serif' },
        colors: ['#10B981', '#3B82F6', '#F59E0B', '#6B7280'],
        labels: ['Siap Jual', 'Terjual', 'Booking', 'Tidak Dijual'],
        legend: { show: false },
        plotOptions: {
            pie: { donut: { size: '72%', labels: { show: true,
                name: { show: true, fontSize: '13px', color: textColor },
                value: { show: true, fontSize: '26px', fontWeight: 700, color: isDark ? '#fff' : '#111827' },
                total: { show: true, showAlways: true, label: 'Total', fontSize: '13px', color: textColor,
                    formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                }
            }}}
        },
        dataLabels: { enabled: false },
        stroke: { show: true, colors: [bgStroke], width: 3 }
    }).render();

    // Bar Chart
    new ApexCharts(document.querySelector("#chartPendapatan"), {
        series: [{ name: 'Pendapatan', data: @json($tren_data) }],
        chart: { type: 'bar', height: 300, fontFamily: 'Inter, sans-serif', toolbar: { show: false } },
        colors: ['#465FFF'],
        plotOptions: { bar: { borderRadius: 6, columnWidth: '45%' } },
        dataLabels: { enabled: false },
        xaxis: {
            categories: @json($tren_labels),
            labels: { style: { colors: textColor, fontSize: '11px' } },
            axisBorder: { show: false }, axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                style: { colors: textColor, fontSize: '11px' },
                formatter: v => { if(v >= 1e6) return (v/1e6).toFixed(1)+'jt'; if(v >= 1e3) return (v/1e3).toFixed(0)+'rb'; return v; }
            }
        },
        grid: { borderColor: isDark ? '#374151' : '#F3F4F6', strokeDashArray: 4 },
        tooltip: {
            y: { formatter: v => 'Rp ' + v.toLocaleString('id-ID') }
        }
    }).render();
});
</script>

<style>
    .apexcharts-tooltip { color: #1f2937 !important; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1) !important; }
    .dark .apexcharts-tooltip { color: #f9fafb !important; background-color: #1f2937 !important; border-color: #374151 !important; }
</style>
@endsection
