<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 14px;
            text-align: center;
        }

        .header-table {
            width: 100%;
            margin-bottom: 30px;
        }

        .header-table td {
            border: none;
        }

        .header-text {
            text-align: center;
        }

        .header-text h3 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            text-align: left;
        }

        th, td {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            border-left: none;
            border-right: none;
            padding: 6px;
        }

        th {
            background: #111827;
            color: #fff;
            text-align: center;
            font-weight: bold;
            border-bottom: 2px solid #000;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<table class="header-table">
    <tr>
        <td class="header-text">
            <h3>
                Laporan {{ ucfirst($jenis) }}
            </h3>
            <h3>Paguyuban Pemuda Prasojo</h3>
            <h3>Desa Juwiring, Kecamatan Juwiring, Kabupaten Klaten</h3>
        </td>
    </tr>
</table>

<!-- TABEL -->
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Subjek</th>
            <th>Pemasukan</th>
            <th>Pengeluaran</th>
        </tr>
    </thead>

    <tbody>
        @php
            $totalPemasukan = 0;
            $totalPengeluaran = 0;
        @endphp

        @foreach($data as $i => $item)
            @php
                $pemasukan = $item->pemasukan ?? 0;
                $pengeluaran = $item->pengeluaran ?? 0;

                $totalPemasukan += $pemasukan;
                $totalPengeluaran += $pengeluaran;
            @endphp

            <tr>
                <td style="text-align:center">{{ $i + 1 }}</td>
                <td style="text-align:center">
                    {{ strtoupper(substr($jenis,0,1)) }}-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td style="text-align:center">
                    {{ \Carbon\Carbon::parse($item->tanggal)->format('j-m-Y') }}
                </td>
                <td>{{ $item->subjek }}</td>
                <td style="text-align:right">
                    Rp {{ number_format($pemasukan,0,',','.') }}
                </td>
                <td style="text-align:right">
                    Rp {{ number_format($pengeluaran,0,',','.') }}
                </td>
            </tr>
        @endforeach

        <!-- TOTAL PEMASUKAN & PENGELUARAN -->
        <tr style="font-weight:bold;">
            <td colspan="4" style="text-align:center;">TOTAL</td>
            <td style="text-align:right;">
                Rp {{ number_format($totalPemasukan,0,',','.') }}
            </td>
            <td style="text-align:right;">
                Rp {{ number_format($totalPengeluaran,0,',','.') }}
            </td>
        </tr>

        <!-- SALDO AKHIR -->
        <tr style="font-weight:bold;">
            <td colspan="4" style="text-align:center;">SALDO</td>
            <td colspan="2" style="text-align:right;">
                Rp {{ number_format($totalPemasukan - $totalPengeluaran,0,',','.') }}
            </td>
        </tr>
    </tbody>
</table>

<br><br>

<table style="width:100%; border:none; margin-top:30px;">
    <tr>
        <td style="width:60%; border:none;"></td>
        <td style="width:40%; text-align:center; border:none;">
            <p style="margin:0;">
                Klaten, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('j F Y') }}
            </p>
            <br>
            <p style="margin:0; font-weight:bold;">
                Paguyuban Pemuda Prasojo
            </p>
        </td>
    </tr>
</table>

</body>
</html>
