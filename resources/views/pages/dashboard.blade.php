@extends('layouts.main')

@section('content')

<div class="space-y-10 mt-12">

    {{-- KAS --}}
    <div class="relative">
        <h3 class="text-xl font-bold text-[#111827] mb-3">Kas</h3>
    <div class="overflow-x-auto rounded-2xl">
        <table class="min-w-[1100px] w-full bg-white">
            <thead class="bg-[#111827] text-white text-sm">
                <tr>
                    <th class="p-2 text-center">Pemasukan</th>
                    <th class="p-2 text-center">Pengeluaran</th>
                    <th class="p-2 text-center">Saldo</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <tr
                    class="border-b hover:bg-gray-50">
                    <td class="p-3 text-xl font-bold text-center whitespace-nowrap text-green-600">
                        Rp {{ number_format($kasPemasukan, 0, ',', '.') }}
                    </td>
                    <td class="p-3 text-xl font-bold text-center whitespace-nowrap text-red-600">
                        Rp {{ number_format($kasPengeluaran, 0, ',', '.') }}
                    </td>
                    <td class="p-3 text-xl font-bold text-center whitespace-nowrap text-blue-600">
                        Rp {{ number_format($kasSaldo, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>    

    {{-- INVENTARIS --}}
    <div class="relative">
        <h3 class="text-xl font-bold text-[#111827] mb-3">Inventaris</h3>
    <div class="overflow-x-auto rounded-2xl">
        <table class="min-w-[1100px] w-full bg-white">
            <thead class="bg-[#111827] text-white text-sm">
                <tr>
                    <th class="p-2 text-center">Pemasukan</th>
                    <th class="p-2 text-center">Pengeluaran</th>
                    <th class="p-2 text-center">Saldo</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <tr
                    class="border-b hover:bg-gray-50">
                    <td class="p-3 text-xl font-bold text-center whitespace-nowrap text-green-600">
                        Rp {{ number_format($inventarisPemasukan, 0, ',', '.') }}
                    </td>
                    <td class="p-3 text-xl font-bold text-center whitespace-nowrap text-red-600">
                        Rp {{ number_format($inventarisPengeluaran, 0, ',', '.') }}
                    </td>
                    <td class="p-3 text-xl font-bold text-center whitespace-nowrap text-blue-600">
                        Rp {{ number_format($inventarisSaldo, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>  

    {{-- SOSIAL --}}
    <div class="relative">
        <h3 class="text-xl font-bold text-[#111827] mb-3">Sosial</h3>
    <div class="overflow-x-auto rounded-2xl">
        <table class="min-w-[1100px] w-full bg-white">
            <thead class="bg-[#111827] text-white text-sm">
                <tr>
                    <th class="p-2 text-center">Pemasukan</th>
                    <th class="p-2 text-center">Pengeluaran</th>
                    <th class="p-2 text-center">Saldo</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <tr
                    class="border-b hover:bg-gray-50">
                    <td class="p-3 text-xl font-bold text-center whitespace-nowrap text-green-600">
                        Rp {{ number_format($sosialPemasukan, 0, ',', '.') }}
                    </td>
                    <td class="p-3 text-xl font-bold text-center whitespace-nowrap text-red-600">
                        Rp {{ number_format($sosialPengeluaran, 0, ',', '.') }}
                    </td>
                    <td class="p-3 text-xl font-bold text-center whitespace-nowrap text-blue-600">
                        Rp {{ number_format($sosialSaldo, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    </div> 

 
@endsection