@extends('layouts.main') 

@section('content')
@php
    $canManage = in_array(auth()->user()->nama, ['Admin', 'Bendahara']);
@endphp

<div class="p-2">

    {{-- Tambah --}}
    <div class="flex justify-end h-8 gap-2">
        @if($canManage)
            <a href="{{ route('kas.pdf') }}"
                class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800 transition">
                Unduh
            </a>
            <button id="btnTambah"
                class="bg-green-700 text-white px-3 py-1 rounded hover:bg-green-800 transition">
                Tambah
            </button>
        @endif
    </div>


    {{-- Tabel --}}
    <div class="relative mt-6">
        <div class="overflow-x-auto rounded-2xl">
        <table class="min-w-[900px] w-full bg-white">
            <thead class="bg-[#111827] text-white text-sm">
                <tr>
                    <th class="p-2 text-center">No</th>
                    <th class="p-2 text-center">ID</th>
                    <th class="p-2 text-center">Tanggal</th>
                    <th class="p-2 text-center">Subjek</th>
                    <th class="p-2 text-center">Pemasukan</th>
                    <th class="p-2 text-center">Pengeluaran</th>
                    <th class="p-2 text-center">Saldo</th>

                    @if($canManage)
                        <th class="p-2 text-center">Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody class="text-sm">
                @php $runningSaldo = 0; @endphp
                @foreach ($kas as $index => $item)
                    @php
                        $pemasukan = $item->pemasukan ?? 0;
                        $pengeluaran = $item->pengeluaran ?? 0;
                        $runningSaldo += $pemasukan - $pengeluaran;
                    @endphp
                    <tr class="border-b hover:bg-gray-50" data-id="{{ $item->id }}">
                        <td class="p-2 text-center whitespace-nowrap  font-medium">
                            {{ $index + 1 }}</td>

                        <td class="p-2 text-center whitespace-nowrap  font-medium">
                            K-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                        </td>

                        <td class="p-2 text-center whitespace-nowrap font-medium">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('j-m-Y') }}
                        </td>

                        <td class="p-2  font-medium">
                            {{ $item->subjek }}</td>

                        <td class="p-2 text-right whitespace-nowrap  font-medium">
                            {{ $pemasukan ? 'Rp. '.number_format($pemasukan,0,',','.') : 'Rp. 0' }}
                        </td>

                        <td class="p-2 text-right whitespace-nowrap  font-medium">
                            {{ $pengeluaran ? 'Rp. '.number_format($pengeluaran,0,',','.') : 'Rp. 0' }}
                        </td>

                        <td class="p-2 text-right whitespace-nowrap font-medium">
                            {{ 'Rp. '.number_format($runningSaldo,0,',','.') }}
                        </td>

                        @if($canManage)
                        <td class="p-2 text-center whitespace-nowrap">
                            <div class="flex justify-center gap-1">
                                <button class="editBtn bg-yellow-400 hover:bg-yellow-600 text-black px-2 py-1 rounded text-xs">
                                    Edit
                                </button>
                                <button class="deleteBtn bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs">
                                    Hapus
                                </button>
                            </div>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH / EDIT --}}
@if($canManage)
<div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-96">

        <h2 id="modalTitle" class="text-xl font-bold mb-3">Tambah Kas</h2>

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
@endif

@if($canManage)
<script>
const csrfToken = '{{ csrf_token() }}';
const modalTambah = document.getElementById("modalTambah");
const modalHapus = document.getElementById("modalHapus");
const errorBox = document.getElementById("errorBox");
const modalTitle = document.getElementById("modalTitle");

let editingId = null;
let rowToDelete = null;

const today = new Date();
tanggal.max = today.toISOString().split("T")[0];

function showError(msg){
    errorBox.innerText = msg;
    errorBox.classList.remove("hidden");
}
function hideError(){
    errorBox.classList.add("hidden");
}

function kosongkanForm(){
    tanggal.value = "";
    subjek.value = "";
    pemasukan.value = "";
    pengeluaran.value = "";
    pemasukan.disabled = false;
    pengeluaran.disabled = false;
}

function formatRupiah(val){
    val = val.replace(/[^0-9]/g,'');
    val = val.replace(/^0+/, ''); // ❌ nol di depan
    return val ? "Rp. " + val.replace(/\B(?=(\d{3})+(?!\d))/g,'.') : "";
}

function syncDisable(){
    pemasukan.disabled = pengeluaran.value !== "";
    pengeluaran.disabled = pemasukan.value !== "";
}

document.querySelectorAll('.rupiah-only').forEach(i=>{
    i.addEventListener('input',function(){
        this.value = formatRupiah(this.value);
        syncDisable();
    });
});

/* =========================
   MODE TAMBAH
========================= */
btnTambah.onclick = ()=>{
    editingId = null;
    modalTitle.innerText = "Tambah Data Kas";
    kosongkanForm();
    hideError();
    modalTambah.classList.remove("hidden");
};

/* =========================
   BATAL
========================= */
btnBatal.onclick = ()=> modalTambah.classList.add("hidden");

/* =========================
   SIMPAN (TAMBAH / EDIT)
========================= */
btnSimpan.onclick = async ()=>{
    const data = {
        tanggal: tanggal.value,
        subjek: subjek.value,
        pemasukan: parseInt(pemasukan.value.replace(/\D/g,'')) || 0,
        pengeluaran: parseInt(pengeluaran.value.replace(/\D/g,'')) || 0
    };

    if(!data.tanggal || !data.subjek || (!data.pemasukan && !data.pengeluaran)){
        showError("Data belum lengkap.");
        return;
    }

    const url = editingId ? `/kas/${editingId}` : "{{ route('kas.store') }}";
    const method = editingId ? "PUT" : "POST";

    const res = await fetch(url,{
        method,
        headers:{
            "Content-Type":"application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify(data)
    });

    res.ok ? location.reload() : showError("Gagal menyimpan data.");
};

/* =========================
   MODE EDIT & HAPUS
========================= */
document.addEventListener("click",e=>{
    if(e.target.classList.contains("editBtn")){
        const r = e.target.closest("tr");
        editingId = r.dataset.id;

        modalTitle.innerText = "Edit Data Kas";

        // Format tanggal ke YYYY-MM-DD
        const tgl = r.children[2].innerText.split("-");
        const day   = tgl[0].padStart(2, '0');
        const month = tgl[1].padStart(2, '0');
        const year  = tgl[2];
        tanggal.value = `${year}-${month}-${day}`;

        subjek.value = r.children[3].innerText;
        pemasukan.value = r.children[4].innerText !== "Rp. 0" ? r.children[4].innerText : "";
        pengeluaran.value = r.children[5].innerText !== "Rp. 0" ? r.children[5].innerText : "";

        pemasukan.disabled = false;
        pengeluaran.disabled = false;
        syncDisable();

        hideError();
        modalTambah.classList.remove("hidden");
    }

    if(e.target.classList.contains("deleteBtn")){
        rowToDelete = e.target.closest("tr");
        modalHapus.classList.remove("hidden");
    }
});

/* =========================
   HAPUS
========================= */
hapusTidak.onclick = ()=> modalHapus.classList.add("hidden");

hapusYa.onclick = async ()=>{
    const res = await fetch(`/kas/${rowToDelete.dataset.id}`,{
        method:"DELETE",
        headers:{ "X-CSRF-TOKEN": csrfToken }
    });

    res.ok ? location.reload() : alert("Gagal hapus data");
};
</script>
@endif
@endsection
