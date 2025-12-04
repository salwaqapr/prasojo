@extends('layouts.main')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-4">
        <button id="btnTambah" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Tambah
        </button>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">No</th>
                    <th class="border p-2">ID</th>
                    <th class="border p-2">Tanggal</th>
                    <th class="border p-2">Subjek</th>
                    <th class="border p-2">Pemasukan</th>
                    <th class="border p-2">Pengeluaran</th>
                    <th class="border p-2">Saldo</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="inventarisTable">
                @php $runningSaldo = 0; @endphp
                @foreach ($inventaris as $index => $item)
                    @php
                        $pemasukan = $item->pemasukan ?? 0;
                        $pengeluaran = $item->pengeluaran ?? 0;
                        $runningSaldo += $pemasukan - $pengeluaran;
                    @endphp
                    <tr data-id="{{ $item->id }}">
                        <td class="border p-2 text-center">{{ $index + 1 }}</td>
                        <td class="border p-2 text-center">I-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="border p-2 text-center">{{ $item->tanggal }}</td>
                        <td class="border p-2">{{ $item->subjek }}</td>
                        <td class="border p-2 text-right">{{ $pemasukan ? 'Rp. ' . number_format($pemasukan,0,',','.') : 'Rp. 0' }}</td>
                        <td class="border p-2 text-right">{{ $pengeluaran ? 'Rp. ' . number_format($pengeluaran,0,',','.') : 'Rp. 0' }}</td>
                        <td class="border p-2 text-right">{{ 'Rp. ' . number_format($runningSaldo,0,',','.') }}</td>
                        <td class="border p-2 text-center">
                            <button class="text-blue-600 hover:underline editBtn">Edit</button> |
                            <button class="text-red-600 hover:underline deleteBtn">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

{{-- MODAL TAMBAH / EDIT --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-96">

        <h2 id="modalTitle" class="text-xl font-bold mb-3">Tambah Inventaris</h2>

        <div id="errorBox" class="hidden bg-red-100 text-red-700 p-2 mb-3 rounded text-sm"></div>

        <div class="space-y-3">
            <div>
                <label class="text-sm">Tanggal <span class="text-red-500">*</span></label>
                <input id="tanggal" type="date" class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="text-sm">Subjek <span class="text-red-500">*</span></label>
                <input id="subjek" type="text" class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="text-sm">Pemasukan</label>
                <input id="pemasukan" type="text" class="rupiah-only w-full border p-2 rounded">
            </div>

            <div>
                <label class="text-sm">Pengeluaran</label>
                <input id="pengeluaran" type="text" class="rupiah-only w-full border p-2 rounded">
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <button id="btnBatal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
            <button id="btnSimpan" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
        </div>

    </div>
</div>

{{-- MODAL HAPUS --}}
<div id="modalHapus" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-80 text-center">
        <h2 class="text-lg font-bold mb-4">Hapus Data?</h2>
        <p class="mb-4">Apakah Anda yakin menghapus data ini?</p>

        <div class="flex justify-center gap-3">
            <button id="hapusYa" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Ya</button>
            <button id="hapusTidak" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tidak</button>
        </div>
    </div>
</div>

<script>
const csrfToken = '{{ csrf_token() }}';
const modalTambah = document.getElementById("modalTambah");
const modalHapus = document.getElementById("modalHapus");
const errorBox = document.getElementById("errorBox");
let editingId = null;
let rowToDelete = null;

const today = new Date();
document.getElementById("tanggal").setAttribute("max", today.toISOString().split("T")[0]);

function showError(msg) { errorBox.innerText = msg; errorBox.classList.remove("hidden"); }
function hideError() { errorBox.classList.add("hidden"); }
function kosongkanForm() {
    document.getElementById("tanggal").value = "";
    document.getElementById("subjek").value = "";
    document.getElementById("pemasukan").value = "";
    document.getElementById("pengeluaran").value = "";
    document.getElementById("pemasukan").disabled = false;
    document.getElementById("pengeluaran").disabled = false;
}

function formatRupiah(number){
    let isNegative = number.toString().includes("-");
    number = number.toString().replace(/[^0-9]/g, "").replace(/^0+/, ""); 
    if(!number) return "Rp. 0";
    let formatted = number.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return (isNegative ? "- Rp. " : "Rp. ") + formatted;
}

document.querySelectorAll('.rupiah-only').forEach(input => {
    input.addEventListener('input', function () {
        this.value = formatRupiah(this.value);
        if(this.id==="pemasukan") document.getElementById("pengeluaran").disabled = this.value && this.value!=="Rp. 0";
        if(this.id==="pengeluaran") document.getElementById("pemasukan").disabled = this.value && this.value!=="Rp. 0";
    });
});

document.getElementById("btnTambah").onclick = ()=>{
    editingId = null;
    kosongkanForm();
    hideError();
    document.getElementById("modalTitle").innerText = "Tambah Inventaris";
    modalTambah.classList.remove("hidden");
};
document.getElementById("btnBatal").onclick = ()=> modalTambah.classList.add("hidden");

// SIMPAN / EDIT
document.getElementById("btnSimpan").onclick = async ()=>{
    hideError();
    const tanggal = document.getElementById("tanggal").value;
    const subjek = document.getElementById("subjek").value;
    const pemasukan = parseInt(document.getElementById("pemasukan").value.replace(/[^0-9]/g,''))||0;
    const pengeluaran = parseInt(document.getElementById("pengeluaran").value.replace(/[^0-9]/g,''))||0;

    if(!tanggal) return showError("Tanggal wajib diisi.");
    if(!subjek) return showError("Subjek wajib diisi.");
    if(pemasukan===0 && pengeluaran===0) return showError("Isi salah satu: pemasukan atau pengeluaran.");

    const url = editingId ? `/inventaris/${editingId}` : "{{ route('inventaris.store') }}";
    const method = editingId ? "PUT" : "POST";

    const res = await fetch(url, {
        method: method,
        headers: {
            "Content-Type":"application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify({tanggal, subjek, pemasukan, pengeluaran})
    });
    if(res.ok){
        location.reload();
    }else{
        showError("Gagal menyimpan data.");
    }
};

// EDIT
document.addEventListener("click", function(e){
    if(e.target.classList.contains("editBtn")){
        const row = e.target.closest("tr");
        editingId = row.dataset.id;
        document.getElementById("modalTitle").innerText = "Edit Inventaris";
        document.getElementById("tanggal").value = row.children[2].innerText;
        document.getElementById("subjek").value = row.children[3].innerText;
        document.getElementById("pemasukan").value = row.children[4].innerText!=="Rp. 0"?row.children[4].innerText:"";
        document.getElementById("pengeluaran").value = row.children[5].innerText!=="Rp. 0"?row.children[5].innerText:"";
        document.getElementById("pemasukan").disabled = document.getElementById("pengeluaran").value!=="" && document.getElementById("pengeluaran").value!=="Rp. 0";
        document.getElementById("pengeluaran").disabled = document.getElementById("pemasukan").value!=="" && document.getElementById("pemasukan").value!=="Rp. 0";
        hideError();
        modalTambah.classList.remove("hidden");
    }
});

// HAPUS
document.addEventListener("click", function(e){
    if(e.target.classList.contains("deleteBtn")){
        rowToDelete = e.target.closest("tr");
        modalHapus.classList.remove("hidden");
    }
});
document.getElementById("hapusTidak").onclick = ()=> modalHapus.classList.add("hidden");
document.getElementById("hapusYa").onclick = async ()=>{
    if(!rowToDelete) return;
    const id = rowToDelete.dataset.id;
    const res = await fetch(`/inventaris/${id}`,{
        method:"DELETE",
        headers: {"X-CSRF-TOKEN":csrfToken}
    });
    if(res.ok){
        location.reload();
    }else{
        alert("Gagal menghapus data.");
    }
};
</script>
@endsection
