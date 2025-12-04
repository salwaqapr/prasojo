@extends('layouts.main')

@section('content')

<div class="space-y-10">

    <!-- KAS -->
    <div>
        <h3 class="text-xl font-semibold mb-3">Kas</h3>
        <div class="grid grid-cols-3 gap-4">
            <div class="p-4 bg-white shadow rounded-lg text-center">
                <p class="text-sm text-gray-600">Pemasukan</p>
                <p class="text-2xl font-bold">0</p>
            </div>
            <div class="p-4 bg-white shadow rounded-lg text-center">
                <p class="text-sm text-gray-600">Pengeluaran</p>
                <p class="text-2xl font-bold">0</p>
            </div>
            <div class="p-4 bg-white shadow rounded-lg text-center">
                <p class="text-sm text-gray-600">Saldo</p>
                <p class="text-2xl font-bold">0</p>
            </div>
        </div>
    </div>

    <!-- INVENTARIS -->
    <div>
        <h3 class="text-xl font-semibold mb-3">Inventaris</h3>
        <div class="grid grid-cols-3 gap-4">
            <div class="p-4 bg-white shadow rounded-lg text-center">
                <p class="text-sm text-gray-600">Pemasukan</p>
                <p class="text-2xl font-bold">0</p>
            </div>
            <div class="p-4 bg-white shadow rounded-lg text-center">
                <p class="text-sm text-gray-600">Pengeluaran</p>
                <p class="text-2xl font-bold">0</p>
            </div>
            <div class="p-4 bg-white shadow rounded-lg text-center">
                <p class="text-sm text-gray-600">Saldo</p>
                <p class="text-2xl font-bold">0</p>
            </div>
        </div>
    </div>

    <!-- SOSIAL -->
    <div>
        <h3 class="text-xl font-semibold mb-3">Sosial</h3>
        <div class="grid grid-cols-3 gap-4">
            <div class="p-4 bg-white shadow rounded-lg text-center">
                <p class="text-sm text-gray-600">Pemasukan</p>
                <p class="text-2xl font-bold">0</p>
            </div>
            <div class="p-4 bg-white shadow rounded-lg text-center">
                <p class="text-sm text-gray-600">Pengeluaran</p>
                <p class="text-2xl font-bold">0</p>
            </div>
            <div class="p-4 bg-white shadow rounded-lg text-center">
                <p class="text-sm text-gray-600">Saldo</p>
                <p class="text-2xl font-bold">0</p>
            </div>
        </div>
    </div>

</div>

@endsection
