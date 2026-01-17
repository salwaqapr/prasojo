@extends('layouts.main')

@section('content')
@php
    $canManage = in_array(auth()->user()->nama, ['Admin', 'Sie Sosial']);

    $totalPemasukan = 0;
    $totalPengeluaran = 0;
    foreach ($sosial as $s) {
        $totalPemasukan += $s->pemasukan ?? 0;
        $totalPengeluaran += $s->pengeluaran ?? 0;
    }
    $saldoAkhir = $totalPemasukan - $totalPengeluaran;

@endphp

<div class="p-3">

    <div class="w-full max-w-full overflow-x-auto space-y-3">

        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

            {{-- SEARCH + FILTER --}}
            <div class="flex flex-col gap-2 sm:grid sm:grid-cols-2 lg:flex lg:flex-row lg:items-center lg:gap-2 w-full">

                <div class="relative w-full lg:w-60">
                    <!-- ICON SEARCH -->
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 text-gray-400 absolute left-2 top-1/2 -translate-y-1/2 pointer-events-none"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-4.35-4.35m1.6-4.65a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>

                    <!-- INPUT -->
                    <input
                        type="search"
                        id="searchSubjek"
                        placeholder="Cari Subjek"
                        class="border px-2 py-1 pl-8 rounded-lg text-sm w-full
                            focus:outline-none focus:ring-2 focus:ring-gray-300"
                    />
                </div>

                <select
                    id="filterBulan"
                    class="border px-2 py-1 rounded text-sm w-full lg:w-40">
                    <option value="">Semua Bulan</option>
                </select>
                <select
                    id="filterTahun"
                    class="border px-2 py-1 rounded-lg text-sm w-full lg:w-40">
                    <option value="">Semua Tahun</option>
                </select>
            </div>

            {{-- UNDUH + TAMBAH --}}
            @if($canManage)
            <div class="flex flex-col gap-2 sm:flex-row sm:justify-end lg:flex-row lg:items-center">
                <a href="{{ route('sosial.pdf') }}"
                    id="btnUnduhPdf"
                    class="inline-flex items-center justify-center gap-2
                          bg-blue-700 text-white px-2 py-1 rounded
                          hover:bg-blue-800 transition
                          w-full sm:w-auto">
                    {{-- ICON DOWNLOAD --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0 0l-4-4m4 4l4-4" />
                    </svg>
                    <span>Unduh</span>
                </a>

                <button
                    id="btnTambah"
                    class="inline-flex items-center justify-center gap-2
                           bg-green-700 text-white px-2 py-1 rounded
                           hover:bg-green-800 transition
                           w-full sm:w-auto">
                    {{-- ICON PLUS --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah</span>
                </button>
            </div>
            @endif
        </div>
    </div>
    
    {{-- Tabel --}}
    <div class="responsive mt-6">
        <div class="overflow-x-auto rounded-2xl">
        <table class="min-w-[900px] w-full bg-white">
            <thead class="bg-[#111827] text-white text-sm">
                <tr>
                    <th class="p-2 text-center">No</th>
                    <th class="p-2 text-center">ID</th>
                    <th class="p-2 text-center cursor-pointer select-none" id="sortTanggal">
                        <div class="flex items-center justify-center gap-1">
                            <span>Tanggal</span>
                            <span id="sortIcon" class="text-xs">⇅</span>
                        </div>
                    </th>
                    <th class="p-2 text-center">Subjek</th>
                    <th class="p-2 text-center">Pemasukan</th>
                    <th class="p-2 text-center">Pengeluaran</th>
                    @if($canManage)
                        <th class="p-2 text-center">Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody class="text-sm" id="sosialTable">
            @foreach ($sosial as $index => $item)
            <tr data-id="{{ $item->id }}" class="border-b hover:bg-gray-50">
                <td class="p-2 text-center whitespace-nowrap font-medium">
                    {{ $index + 1 }}
                </td>
                <td class="p-2 text-center whitespace-nowrap font-medium">
                    S-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="p-2 text-center whitespace-nowrap font-medium">
                    {{ \Carbon\Carbon::parse($item->tanggal)->format('j-m-Y') }}
                </td>
                <td class="p-2 font-medium">
                    {{ $item->subjek }}
                </td>
                <td class="p-2 text-right font-medium">
                    Rp {{ number_format($item->pemasukan ?? 0,0,',','.') }}
                </td>
                <td class="p-2 text-right font-medium">
                    Rp {{ number_format($item->pengeluaran ?? 0,0,',','.') }}
                </td>

                @if($canManage)
                <td class="p-2 text-center">
                    <div class="flex justify-center gap-1">
                        <!-- EDIT -->
                        <button
                            class="editBtn inline-flex items-center gap-1
                                bg-yellow-400 hover:bg-yellow-600
                                text-black px-2 py-1 rounded text-xs">
                            <!-- ICON PENSIL -->
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-3.5 h-3.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                            <span>Edit</span>
                        </button>

                        <!-- HAPUS -->
                        <button
                            class="deleteBtn inline-flex items-center gap-1
                                bg-red-600 hover:bg-red-700
                                text-white px-2 py-1 rounded text-xs">
                            <!-- ICON SAMPAH -->
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-3.5 h-3.5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-6 0V5a1 1 0 011-1h4a1 1 0 011 1v2" />
                            </svg>
                            <span>Hapus</span>
                        </button>
                    </div>
                </td>
                @endif
            </tr>
            @endforeach
            </tbody>
            <tfoot class="border-b">
                {{-- BARIS JUMLAH --}}
                <tr class="border-t-2">
                    <td colspan="3"></td>
                    <td class="p-2 text-right font-bold">Jumlah</td>
                    <td class="p-2 text-right text-green-700 font-bold">
                        Rp <span id="totalPemasukan">{{ number_format($totalPemasukan,0,',','.') }}</span>
                    </td>
                    <td class="p-2 text-right text-red-700 font-bold">
                        Rp <span id="totalPengeluaran">{{ number_format($totalPengeluaran,0,',','.') }}</span>
                    </td>
                    @if($canManage)
                        <td></td>
                    @endif
                </tr>

                {{-- BARIS SALDO --}}
                <tr>
                    <td colspan="3"></td>
                    <td class="p-2 text-right font-bold">Saldo</td>
                    <td colspan="2"
                        id="saldoAkhir"
                        class="p-2 text-right font-bold
                        {{ $saldoAkhir >= 0 ? 'text-blue-700' : 'text-red-700' }}">
                        Rp {{ number_format($saldoAkhir,0,',','.') }}
                    </td>
                    @if($canManage)
                        <td></td>
                    @endif
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH / EDIT --}}
@if($canManage)
<div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-96">

        <h2 id="modalTitle" class="text-xl font-bold mb-3">Tambah Sosial</h2>

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

{{-- MODAL NOTIF --}}
<div id="modalNotif"
     class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-80 text-center">
        <h2 id="notifTitle" class="text-lg font-bold mb-2 text-green-700">
            Berhasil
        </h2>
        <p id="notifMessage" class="text-gray-700">
            Data berhasil disimpan
        </p>
    </div>
</div>
@endif

<script>
@if($canManage)
const csrfToken = '{{ csrf_token() }}';
const modalTambah = document.getElementById("modalTambah");
const modalHapus = document.getElementById("modalHapus");
const errorBox = document.getElementById("errorBox");
const modalTitle = document.getElementById("modalTitle");
let editingId = null;
let rowToDelete = null;

const today = new Date();
document.getElementById("tanggal").setAttribute("max", today.toISOString().split("T")[0]);

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
   UNDUH PDF
========================= */
function updatePdfLink() {
    const keyword  = searchInput.value;
    const bulanVal = filterBulan.value;
    const tahunVal = filterTahun.value;

    const params = new URLSearchParams();

    if (keyword)  params.append("search", keyword);
    if (bulanVal !== "") params.append("bulan", bulanVal);
    if (tahunVal !== "") params.append("tahun", tahunVal);

    const baseUrl = document.getElementById("btnUnduhPdf").dataset.base
        || document.getElementById("btnUnduhPdf").href.split("?")[0];

    document.getElementById("btnUnduhPdf").href =
        params.toString()
            ? `${baseUrl}?${params.toString()}`
            : baseUrl;
}

/* =========================
   NOTIFIKASI
========================= */
function showNotif(message) {
    const modal = document.getElementById("modalNotif");
    const msgEl = document.getElementById("notifMessage");

    msgEl.innerText = message;
    modal.classList.remove("hidden");

    // ⏱ auto close
    const timer = setTimeout(closeNotif, 2500);

    // 🖱 klik di mana saja untuk tutup
    modal.onclick = () => {
        clearTimeout(timer);
        closeNotif();
    };
}
function closeNotif() {
    const modal = document.getElementById("modalNotif");
    modal.classList.add("hidden");
    localStorage.removeItem("toastMessage");
}
document.addEventListener("DOMContentLoaded", () => {
    const msg = localStorage.getItem("toastMessage");
    if (msg) showNotif(msg);
});


/* =========================
   MODE TAMBAH
========================= */
btnTambah.onclick = ()=>{
    editingId = null;
    modalTitle.innerText = "Tambah Data Sosial";
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

    if(!data.tanggal) return showError("Tanggal wajib diisi.");
    if(!data.subjek) return showError("Subjek wajib diisi.");
    if(!data.pemasukan && !data.pengeluaran) return showError("Pemasukan atau Pengluaran wajib diisi.");

    const url = editingId ? `/sosial/${editingId}` : "{{ route('sosial.store') }}";
    const method = editingId ? "PUT" : "POST";
    const res = await fetch(url,{
        method,
        headers:{
            "Content-Type":"application/json",
            "Accept":"application/json", // 🔥 INI KUNCI UTAMA
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify(data)
    });

    if (!res.ok) {
        const err = await res.text();
        console.error(err);
        showError("Gagal menyimpan data.");
        return;
    }

    localStorage.setItem(
        "toastMessage",
        editingId ? "Data berhasil diedit" : "Data berhasil ditambah"
    );

    if (editingId) {
        localStorage.setItem("toastMessage", "Data berhasil diedit");
    } else {
        localStorage.setItem("toastMessage", "Data berhasil ditambah");
    }

    location.reload();
};

/* =========================
   MODE EDIT & HAPUS
========================= */
document.addEventListener("click", e => {

    const editBtn = e.target.closest(".editBtn");
    const deleteBtn = e.target.closest(".deleteBtn");

    /* ===== EDIT ===== */
    if (editBtn) {
        const r = editBtn.closest("tr");
        editingId = r.dataset.id;
        modalTitle.innerText = "Edit Data Sosial";

        const tgl = r.children[2].innerText.split("-");
        const day   = tgl[0].padStart(2, '0');
        const month = tgl[1].padStart(2, '0');
        const year  = tgl[2];

        const masuk  = parseInt(r.children[4].innerText.replace(/\D/g,'')) || 0;
        const keluar = parseInt(r.children[5].innerText.replace(/\D/g,'')) || 0;

        tanggal.value = `${year}-${month}-${day}`;
        subjek.value = r.children[3].innerText;
        pemasukan.value = masuk ? formatRupiah(masuk.toString()) : "";
        pengeluaran.value = keluar ? formatRupiah(keluar.toString()) : "";

        pemasukan.disabled = false;
        pengeluaran.disabled = false;
        syncDisable();
        hideError();

        modalTambah.classList.remove("hidden");
    }

    /* ===== HAPUS ===== */
    if (deleteBtn) {
        rowToDelete = deleteBtn.closest("tr");
        modalHapus.classList.remove("hidden");
    }
});

/* =========================
   HAPUS
========================= */
hapusTidak.onclick = ()=> modalHapus.classList.add("hidden");
hapusYa.onclick = async ()=>{
    const res = await fetch(`/sosial/${rowToDelete.dataset.id}`,{
        method:"DELETE",
        headers:{ "X-CSRF-TOKEN": csrfToken }
    });

    if(res.ok){
        localStorage.setItem("toastMessage", "Data berhasil dihapus");
        location.reload();
    } else {
        alert("Gagal hapus data");
    }
};

@endif

/* =========================
   RESET NOMOR
========================= */
function resetNomor() {
    let no = 1;
    document.querySelectorAll("#sosialTable tr").forEach(row => {
        if (row.style.display === "none") return;
        row.children[0].innerText = no++;
    });
}

/* =========================
   SORT TANGGAL +ID
========================= */
let sortAsc = false;
function sortTanggalOnly() {
    const tbody = document.getElementById("sosialTable");
    const rows = Array.from(tbody.querySelectorAll("tr"));
    const icon = document.getElementById("sortIcon");

    rows.sort((a, b) => {
        const tglA = a.children[2].innerText.split("-").reverse().join("-");
        const tglB = b.children[2].innerText.split("-").reverse().join("-");

        const dateA = new Date(tglA);
        const dateB = new Date(tglB);

        const idA = parseInt(a.dataset.id);
        const idB = parseInt(b.dataset.id);

        if (dateA.getTime() !== dateB.getTime()) {
            return sortAsc ? dateA - dateB : dateB - dateA;
        }
        return sortAsc ? idA - idB : idB - idA;
    });

    tbody.innerHTML = "";
    rows.forEach((row, i) => {
        row.children[0].innerText = i + 1;
        tbody.appendChild(row);
    });
    icon.innerText = sortAsc ? "▲" : "▼";
}

document.getElementById("sortTanggal")
    .addEventListener("click", function () {
        sortAsc = !sortAsc;
        sortTanggalOnly();
    });

/* =========================
   SEARCH, BULAN, TAHUN
========================= */
const bulanNama = [
    "Januari","Februari","Maret","April","Mei","Juni",
    "Juli","Agustus","September","Oktober","November","Desember"
];

const searchInput = document.getElementById("searchSubjek");
const filterBulan = document.getElementById("filterBulan");
const filterTahun = document.getElementById("filterTahun");

let dataTanggal = [];

/* =========================
   KUMPULKAN DATA TANGGAL
========================= */
function collectTanggalData() {
    dataTanggal = [];
    const rows = document.querySelectorAll("#sosialTable tr");

    rows.forEach(row => {
        const teksTanggal = row.children[2]?.innerText.trim();
        if (!teksTanggal) return;

        const [dd, mm, yyyy] = teksTanggal.split("-");
        if (!dd || !mm || !yyyy) return;

        dataTanggal.push({
            bulan: parseInt(mm, 10) - 1,
            tahun: yyyy
        });
    });
}

function getFilteredDataForDropdown(mode) {
    const keyword  = searchInput.value.toLowerCase();
    const bulanVal = filterBulan.value;
    const tahunVal = filterTahun.value;

    const result = [];

    document.querySelectorAll("#sosialTable tr").forEach(row => {
        const subjek = row.children[3]?.innerText.toLowerCase() || "";
        if (keyword && !subjek.includes(keyword)) return;

        const teks = row.children[2]?.innerText.trim();
        if (!teks) return;

        const [, mm, yyyy] = teks.split("-");
        const bulan = parseInt(mm, 10) - 1;

        // ⚠️ ATURAN PENTING
        if (mode === "bulan" && tahunVal !== "" && yyyy != tahunVal) return;
        if (mode === "tahun" && bulanVal !== "" && bulan != bulanVal) return;

        result.push({ bulan, tahun: yyyy });
    });

    return result;
}


/* =========================
   RENDER DROPDOWN BULAN & TAHUN
========================= */
function renderFilters() {
    const bulanTerpilih = filterBulan.value;
    const tahunTerpilih = filterTahun.value;

    /* ===== DATA UNTUK DROPDOWN ===== */
    const dataBulan = getFilteredDataForDropdown("bulan");
    const dataTahun = getFilteredDataForDropdown("tahun");

    const bulanSet = new Set();
    const tahunSet = new Set();

    dataBulan.forEach(d => bulanSet.add(d.bulan));
    dataTahun.forEach(d => tahunSet.add(d.tahun));

    /* ===== BULAN ===== */
    filterBulan.innerHTML = "";

    const optSemuaBulan = document.createElement("option");
    optSemuaBulan.value = "";
    optSemuaBulan.textContent = "Semua Bulan";
    filterBulan.appendChild(optSemuaBulan);

    [...bulanSet].sort((a,b)=>a-b).forEach(b => {
        const opt = document.createElement("option");
        opt.value = b;
        opt.textContent = bulanNama[b];
        if (String(b) === String(bulanTerpilih)) opt.selected = true;
        filterBulan.appendChild(opt);
    });

    /* ===== TAHUN ===== */
    filterTahun.innerHTML = "";

    const optSemuaTahun = document.createElement("option");
    optSemuaTahun.value = "";
    optSemuaTahun.textContent = "Semua Tahun";
    filterTahun.appendChild(optSemuaTahun);

    [...tahunSet].sort().forEach(t => {
        const opt = document.createElement("option");
        opt.value = t;
        opt.textContent = t;
        if (String(t) === String(tahunTerpilih)) opt.selected = true;
        filterTahun.appendChild(opt);
    });
}


/* =========================
   FILTER UTAMA (FINAL)
========================= */
function applyAllFilter() {
    const keyword  = searchInput.value.toLowerCase();
    const bulanVal = filterBulan.value;
    const tahunVal = filterTahun.value;

    document.querySelectorAll("#sosialTable tr").forEach(row => {
        const subjek = row.children[3]?.innerText.toLowerCase() || "";
        const cocokSearch = keyword === "" || subjek.includes(keyword);

        const teks = row.children[2]?.innerText.trim();
        if (!teks) return row.style.display = "none";

        const [, mm, yyyy] = teks.split("-");
        const bulan = parseInt(mm, 10) - 1;

        const cocokBulan = bulanVal === "" || bulan == bulanVal;
        const cocokTahun = tahunVal === "" || yyyy == tahunVal;

        row.style.display =
            cocokSearch && cocokBulan && cocokTahun ? "" : "none";
    });

    resetNomor();
    hitungTotalVisible();
}

/* =========================
   EVENT
========================= */
searchInput.addEventListener("input", () => {
    renderFilters();
    applyAllFilter();
    updatePdfLink();
});
filterBulan.addEventListener("change", () => {
    renderFilters();
    applyAllFilter();
    updatePdfLink();
});
filterTahun.addEventListener("change", () => {
    renderFilters();
    applyAllFilter();
    updatePdfLink();
});

/* =========================
   INIT
========================= */
document.addEventListener("DOMContentLoaded", () => {
    collectTanggalData();
    renderFilters();
    applyAllFilter();
    updatePdfLink();
});

/*=============
HITUNG SALDO
==============*/
function hitungTotalVisible() {
    let totalMasuk = 0;
    let totalKeluar = 0;

    document.querySelectorAll("#sosialTable tr").forEach(row => {
        if (row.style.display === "none") return;

        const masuk = parseInt(
            row.children[4].innerText.replace(/\D/g, "")
        ) || 0;

        const keluar = parseInt(
            row.children[5].innerText.replace(/\D/g, "")
        ) || 0;

        totalMasuk += masuk;
        totalKeluar += keluar;
    });

    const saldo = totalMasuk - totalKeluar;

    document.getElementById("totalPemasukan").innerText =
        totalMasuk.toLocaleString("id-ID");

    document.getElementById("totalPengeluaran").innerText =
        totalKeluar.toLocaleString("id-ID");

    const saldoEl = document.getElementById("saldoAkhir");
    saldoEl.innerText = "Rp " + saldo.toLocaleString("id-ID");

    saldoEl.classList.remove("text-blue-700", "text-red-700");
    saldoEl.classList.add(saldo >= 0 ? "text-blue-700" : "text-red-700");
}

</script>

@endsection
