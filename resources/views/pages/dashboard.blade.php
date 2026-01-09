@extends('layouts.main')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@php
    $namaBulan = [
        1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
        5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
        9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
    ];
@endphp

<div class="p-3">

    {{-- FILTER GLOBAL --}}
    <form id="filterForm" method="GET" class="flex gap-2 mb-6">
        <select name="bulan" id="filterBulan" class="border px-2 py-1 rounded text-sm w-full lg:w-40">
            <option value="">Semua Bulan</option>
            @foreach ($bulanList as $b)
            <option value="{{ $b }}" {{ request('bulan')==$b ? 'selected' : '' }}>
                    {{ $namaBulan[$b] }}
                </option>
            @endforeach
        </select>

        {{-- TAHUN --}}
        <select name="tahun" id="filterTahun" class="border px-2 py-1 rounded text-sm w-full lg:w-40">
            <option value="">Semua Tahun</option>
            @foreach ($tahunList as $t)
                <option value="{{ $t }}" {{ request('tahun')==$t ? 'selected' : '' }}>
                    {{ $t }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- ======================
    KAS
    ====================== --}}
    <div class="bg-white rounded-2xl p-5 shadow">
        <h3 class="text-xl font-bold mb-3">KAS</h3>
        <div class="relative h-80 flex items-center justify-center">
            <canvas id="kasChart"></canvas>
        </div>

        <table class="w-full text-sm mt-4">
            <tr>
                <td class="text-green-600 font-semibold">Pemasukan</td>
                <td class="text-right text-green-600 font-semibold">
                    Rp {{ number_format($kasPemasukan,0,',','.') }}
                </td>
            </tr>
            <tr>
                <td class="text-red-600 font-semibold">Pengeluaran</td>
                <td class="text-right text-red-600 font-semibold">
                    Rp {{ number_format($kasPengeluaran,0,',','.') }}
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

    {{-- ======================
    INVENTARIS
    ====================== --}}
    <div class="bg-white rounded-2xl p-5 shadow">
        <h3 class="text-xl font-bold mb-3">INVENTARIS</h3>
        <div class="relative h-80 flex items-center justify-center">
            <canvas id="inventarisChart"></canvas>
        </div>

        <table class="w-full text-sm mt-4">
            <tr>
                <td class="text-green-600 font-semibold">Pemasukan</td>
                <td class="text-right text-green-600 font-semibold">
                    Rp {{ number_format($inventarisPemasukan,0,',','.') }}
                </td>
            </tr>
            <tr>
                <td class="text-red-600 font-semibold">Pengeluaran</td>
                <td class="text-right text-red-600 font-semibold">
                    Rp {{ number_format($inventarisPengeluaran,0,',','.') }}
                </td>
            </tr>
            <tr class="border-t">
                <td class="text-blue-600 font-bold">Saldo</td>
                <td class="text-right text-blue-600 font-bold">
                    Rp {{ number_format($inventarisSaldo,0,',','.') }}
                </td>
            </tr>
        </table>
    </div>

    {{-- ======================
    SOSIAL
    ====================== --}}
    <div class="bg-white rounded-2xl p-5 shadow">
        <h3 class="text-xl font-bold mb-3">SOSIAL</h3>
        <div class="relative h-80 flex items-center justify-center">
            <canvas id="sosialChart"></canvas>
        </div>

        <table class="w-full text-sm mt-4">
            <tr>
                <td class="text-green-600 font-semibold">Pemasukan</td>
                <td class="text-right text-green-600 font-semibold">
                    Rp {{ number_format($sosialPemasukan,0,',','.') }}
                </td>
            </tr>
            <tr>
                <td class="text-red-600 font-semibold">Pengeluaran</td>
                <td class="text-right text-red-600 font-semibold">
                    Rp {{ number_format($sosialPengeluaran,0,',','.') }}
                </td>
            </tr>
            <tr class="border-t">
                <td class="text-blue-600 font-bold">Saldo</td>
                <td class="text-right text-blue-600 font-bold">
                    Rp {{ number_format($sosialSaldo,0,',','.') }}
                </td>
            </tr>
        </table>
    </div>

    </div>

    <script>
        /* AUTO SUBMIT */
        const form = document.getElementById('filterForm');
        const bulanSelect = document.getElementById('filterBulan');
        const tahunSelect = document.getElementById('filterTahun');

        bulanSelect.addEventListener('change', () => form.submit());
        tahunSelect.addEventListener('change', () => form.submit());

        /* CHART */
        const pie = (canvasId, masuk, keluar) => {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return;

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Pemasukan', 'Pengeluaran'],
                    datasets: [{
                        data: [masuk, keluar],
                        backgroundColor: ['#16a34a', '#dc2626']
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        };

        pie('kasChart', {{ $kasPemasukan }}, {{ $kasPengeluaran }});
        pie('inventarisChart', {{ $inventarisPemasukan }}, {{ $inventarisPengeluaran }});
        pie('sosialChart', {{ $sosialPemasukan }}, {{ $sosialPengeluaran }});
    </script>

</div>

@endsection
