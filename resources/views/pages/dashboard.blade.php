@extends('layouts.main')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@php
    $namaBulan = [
        1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
        5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
        9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
    ];

    $kasMasuk = array_sum($kasChart['pemasukan']);
    $kasKeluar = array_sum($kasChart['pengeluaran']);
    $kasSaldo = $kasMasuk - $kasKeluar;

    $invMasuk = array_sum($inventarisChart['pemasukan']);
    $invKeluar = array_sum($inventarisChart['pengeluaran']);
    $invSaldo = $invMasuk - $invKeluar;

    $sosMasuk = array_sum($sosialChart['pemasukan']);
    $sosKeluar = array_sum($sosialChart['pengeluaran']);
    $sosSaldo = $sosMasuk - $sosKeluar;
@endphp

<div class="p-3">

    {{-- FILTER GLOBAL --}}
    <form id="filterForm" method="GET" class="flex gap-2 mb-6">
        <input type="hidden" name="open[]" id="openSection">
        <select name="bulan" id="filterBulan" class="border px-2 py-1 rounded text-sm w-full lg:w-40">
            <option value="">Semua Bulan</option>
            @foreach ($availableBulan as $b)
            <option value="{{ $b }}" {{ request('bulan')==$b ? 'selected' : '' }}>
                    {{ $namaBulan[$b] }}
                </option>
            @endforeach
        </select>

        {{-- TAHUN --}}
        <select name="tahun" id="filterTahun" class="border px-2 py-1 rounded text-sm w-full lg:w-40">
            <option value="">Semua Tahun</option>
            @foreach ($availableTahun as $t)
                <option value="{{ $t }}" {{ request('tahun')==$t ? 'selected' : '' }}>
                    {{ $t }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="grid grid-cols-1 gap-6">

    {{-- ======================
    KAS
    ====================== --}}
    <div class="bg-white rounded-2xl p-5 shadow">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold">KAS</h3>

            <button
                onclick="toggleSection('kasSection', true)"
                class="transition-transform duration-300"
            >
                ▼
            </button>

        </div>

        <div id="kasSection" class="hidden mt-4">
            <div class="grid grid-cols-1 gap-4">
                <div class="relative h-64 overflow-x-auto overflow-y-hidden lg:overflow-x-hidden">
                    <div class="min-w-[1000px] lg:min-w-0 h-full">
                        <p class="text-sm font-semibold mb-1">Pemasukan</p>
                        <canvas id="kasMasukChart"></canvas>
                    </div>
                </div>

                <div class="relative h-64 overflow-x-auto overflow-y-hidden lg:overflow-x-hidden">
                    <div class="min-w-[1000px] lg:min-w-0 h-full">
                        <p class="text-sm font-semibold mb-1">Pengeluaran</p>
                        <canvas id="kasKeluarChart"></canvas>
                    </div>
                </div>

                <div class="relative h-64 overflow-x-auto overflow-y-hidden lg:overflow-x-hidden">
                    <div class="min-w-[1000px] lg:min-w-0 h-full">
                        <p class="text-sm font-semibold mb-1">Pemasukan dan Pengeluaran</p>
                        <canvas id="kasBandingChart"></canvas>
                    </div>
                </div>
            </div>

            <table class="w-full text-sm mt-4">
                <tr>
                    <td class="text-green-600 font-semibold">Pemasukan</td>
                    <td class="text-right text-green-600 font-semibold">
                        Rp {{ number_format($kasMasuk,0,',','.') }}
                    </td>
                </tr>
                <tr>
                    <td class="text-red-600 font-semibold">Pengeluaran</td>
                    <td class="text-right text-red-600 font-semibold">
                        Rp {{ number_format($kasKeluar,0,',','.') }}
                    </td>
                </tr>
                <tr class="border-t">
                    <td class="text-blue-600 font-bold">Saldo</td>
                    <td class="text-right text-blue-600 font-bold">
                        Rp {{ number_format($kasSaldo,0,',','.') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ======================
    INVENTARIS
    ====================== --}}
    <div class="bg-white rounded-2xl p-5 shadow">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold">INVENTARIS</h3>

            <button
                onclick="toggleSection('inventarisSection', true)"
                class="transition-transform duration-300"
            >
                ▼
            </button>
        </div>

        <div id="inventarisSection" class="hidden mt-4">
            <div class="grid grid-cols-1 gap-4">
                <div class="relative h-64 overflow-x-auto overflow-y-hidden lg:overflow-x-hidden">
                    <div class="min-w-[1000px] lg:min-w-0 h-full">
                        <p class="text-sm font-semibold mb-1">Pemasukan</p>
                        <canvas id="invMasukChart"></canvas>
                    </div>
                </div>

                <div class="relative h-64 overflow-x-auto overflow-y-hidden lg:overflow-x-hidden">
                    <div class="min-w-[1000px] lg:min-w-0 h-full">
                        <p class="text-sm font-semibold mb-1">Pengeluaran</p>
                        <canvas id="invKeluarChart"></canvas>
                    </div>
                </div>

                <div class="relative h-64 overflow-x-auto overflow-y-hidden lg:overflow-x-hidden">
                    <div class="min-w-[1000px] lg:min-w-0 h-full">
                        <p class="text-sm font-semibold mb-1">Pemasukan dan Pengeluaran</p>
                        <canvas id="invBandingChart"></canvas>
                    </div>
                </div>
            </div>

            <table class="w-full text-sm mt-4">
                <tr>
                    <td class="text-green-600 font-semibold">Pemasukan</td>
                    <td class="text-right text-green-600 font-semibold">
                        Rp {{ number_format($invMasuk,0,',','.') }}
                    </td>
                </tr>
                <tr>
                    <td class="text-red-600 font-semibold">Pengeluaran</td>
                    <td class="text-right text-red-600 font-semibold">
                        Rp {{ number_format($invKeluar,0,',','.') }}
                    </td>
                </tr>
                <tr class="border-t">
                    <td class="text-blue-600 font-bold">Saldo</td>
                    <td class="text-right text-blue-600 font-bold">
                        Rp {{ number_format($invSaldo,0,',','.') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ======================
    SOSIAL
    ====================== --}}
    <div class="bg-white rounded-2xl p-5 shadow">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold">SOSIAL</h3>

            <button
                onclick="toggleSection('sosialSection', true)"
                class="transition-transform duration-300"
            >
                ▼
            </button>
        </div>

        <div id="sosialSection" class="hidden mt-4">
            <div class="grid grid-cols-1 gap-4">
                <div class="relative h-64 overflow-x-auto overflow-y-hidden lg:overflow-x-hidden">
                    <div class="min-w-[1000px] lg:min-w-0 h-full">
                        <p class="text-sm font-semibold mb-1">Pemasukan</p>
                        <canvas id="sosialMasukChart"></canvas>
                    </div>
                </div>

                <div class="relative h-64 overflow-x-auto overflow-y-hidden lg:overflow-x-hidden">
                    <div class="min-w-[1000px] lg:min-w-0 h-full">
                        <p class="text-sm font-semibold mb-1">Pengeluaran</p>
                        <canvas id="sosialKeluarChart"></canvas>
                    </div>
                </div>

                <div class="relative h-64 overflow-x-auto overflow-y-hidden lg:overflow-x-hidden">
                    <div class="min-w-[1000px] lg:min-w-0 h-full">
                        <p class="text-sm font-semibold mb-1">Pemasukan dan Pengeluaran</p>
                        <canvas id="sosialBandingChart"></canvas>
                    </div>
                </div>
            </div>

            <table class="w-full text-sm mt-4">
                <tr>
                    <td class="text-green-600 font-semibold">Pemasukan</td>
                    <td class="text-right text-green-600 font-semibold">
                        Rp {{ number_format($sosMasuk,0,',','.') }}
                    </td>
                </tr>
                <tr>
                    <td class="text-red-600 font-semibold">Pengeluaran</td>
                    <td class="text-right text-red-600 font-semibold">
                        Rp {{ number_format($sosKeluar,0,',','.') }}
                    </td>
                </tr>
                <tr class="border-t">
                    <td class="text-blue-600 font-bold">Saldo</td>
                    <td class="text-right text-blue-600 font-bold">
                        Rp {{ number_format($sosSaldo,0,',','.') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

    <script>
        const form = document.getElementById('filterForm');
        const bulanSelect = document.getElementById('filterBulan');
        const tahunSelect = document.getElementById('filterTahun');

        if (bulanSelect) {
            bulanSelect.addEventListener('change', () => {
                form.submit();
            });
        }

        if (tahunSelect) {
            tahunSelect.addEventListener('change', () => {
                form.submit();
            });
        }

        const chartLineSingle = (id, label, labels, data, color, years = null) => {
            const chart = new Chart(document.getElementById(id), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: color,
                        tension: 0.4
                    }],
                    years: years
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            bottom: 30
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: v => 'Rp ' + v.toLocaleString('id-ID')
                            }
                        }
                    }
                },
                plugins: years ? [yearLabelPlugin] : []
            });

            return chart;
        };

        const chartLineDouble = (id, labels, masuk, keluar, years = null) => {
            new Chart(document.getElementById(id), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: masuk,
                            borderColor: '#16a34a',
                            tension: 0.4
                        },
                        {
                            label: 'Pengeluaran',
                            data: keluar,
                            borderColor: '#dc2626',
                            tension: 0.4
                        }
                    ],
                    years: years
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            bottom: 30
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: v => 'Rp ' + v.toLocaleString('id-ID')
                            }
                        }
                    }
                },
                plugins: years ? [yearLabelPlugin] : []
            });
        };

        function renderCharts(sectionId) {

            if (sectionId === 'kasSection') {
                const kas = @json($kasChart);

                chartLineSingle(
                    'kasMasukChart',
                    'Pemasukan',
                    kas.labels,
                    kas.pemasukan,
                    '#16a34a',
                    kas.tahunMarker ?? null
                );

                chartLineSingle(
                    'kasKeluarChart',
                    'Pengeluaran',
                    kas.labels,
                    kas.pengeluaran,
                    '#dc2626',
                    kas.tahunMarker ?? null
                );

                chartLineDouble(
                    'kasBandingChart',
                    kas.labels,
                    kas.pemasukan,
                    kas.pengeluaran,
                    kas.tahunMarker ?? null
                );
            }

            if (sectionId === 'inventarisSection') {
                const inv = @json($inventarisChart);

                chartLineSingle(
                    'invMasukChart',
                    'Pemasukan',
                    inv.labels,
                    inv.pemasukan,
                    '#16a34a',
                    inv.tahunMarker ?? null
                );

                chartLineSingle(
                    'invKeluarChart',
                    'Pengeluaran',
                    inv.labels,
                    inv.pengeluaran,
                    '#dc2626',
                    inv.tahunMarker ?? null
                );

                chartLineDouble(
                    'invBandingChart',
                    inv.labels,
                    inv.pemasukan,
                    inv.pengeluaran,
                    inv.tahunMarker ?? null
                );
            }

            if (sectionId === 'sosialSection') {
                const sosial = @json($sosialChart);

                chartLineSingle(
                    'sosialMasukChart',
                    'Pemasukan',
                    sosial.labels,
                    sosial.pemasukan,
                    '#16a34a',
                    sosial.tahunMarker ?? null
                );

                chartLineSingle(
                    'sosialKeluarChart',
                    'Pengeluaran',
                    sosial.labels,
                    sosial.pengeluaran,
                    '#dc2626',
                    sosial.tahunMarker ?? null
                );

                chartLineDouble(
                    'sosialBandingChart',
                    sosial.labels,
                    sosial.pemasukan,
                    sosial.pengeluaran,
                    sosial.tahunMarker ?? null
                );
            }
        }
        
        const chartRendered = {};
        let openedSections = [];

        function toggleSection(id) {
            const section = document.getElementById(id);

            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');

                if (!chartRendered[id]) {
                    renderCharts(id);
                    chartRendered[id] = true;
                }

                if (!openedSections.includes(id)) {
                    openedSections.push(id);
                }

            } else {
                section.classList.add('hidden');
                openedSections = openedSections.filter(s => s !== id);
            }

            document.getElementById('openSection').value = openedSections.join(',');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const open = @json(request('open', []));

            if (open.length) {
                open.forEach(value => {
                    value.split(',').forEach(id => {
                        toggleSection(id);
                    });
                });
            }
        });

        const yearLabelPlugin = {
            id: 'yearLabel',
            afterDraw(chart) {
                const years = chart.data.years;
                if (!years) return;

                const ctx = chart.ctx;
                const meta = chart.getDatasetMeta(0);
                const bottom = chart.chartArea.bottom;

                ctx.save();
                ctx.font = '12px sans-serif';
                ctx.fillStyle = '#555';
                ctx.textAlign = 'center';

                let lastYear = years[0];
                let startX = meta.data[0].x;

                meta.data.forEach((point, i) => {
                    if (years[i] !== lastYear) {
                        const centerX = (startX + meta.data[i - 1].x) / 2;
                        ctx.fillText(lastYear, centerX, bottom + 30);

                        startX = point.x;
                        lastYear = years[i];
                    }
                });

                // tahun terakhir
                const lastPoint = meta.data[meta.data.length - 1];
                const centerX = (startX + lastPoint.x) / 2;
                ctx.fillText(lastYear, centerX, bottom + 30);

                ctx.restore();
            }
        };

    </script>
</div>

@endsection
