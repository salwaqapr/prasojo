@extends('layouts.main')

@section('content')
<div class="p-3">

    <div class="w-full max-w-full overflow-x-auto">

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
                        id="searchJudulDeskripsi"
                        placeholder="Cari Judul dan Deskripsi"
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

            {{-- Tambah --}}
            <button id="btnTambah"
                class="inline-flex items-center gap-2 bg-green-700 text-white justify-center px-2 py-1 rounded
                hover:bg-green-800 transition">
                {{-- ICON PLUS --}}
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah</span>
            </button>
        </div>

        {{-- Tabel --}}
        <div class="responsive mt-6">
            <div class="overflow-x-auto rounded-2xl">
            <table class="min-w-[1000px] w-full bg-white">
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
                        <th class="p-2 text-center">Judul</th>
                        <th class="p-2 text-center">Deskripsi</th>
                        <th class="p-2 text-center">Foto</th>
                        <th class="p-2 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-sm" id="kegiatanTable">
                    @foreach ($kegiatan as $index => $item)
                    <tr data-id="{{ $item->id }}" data-foto="{{ $item->foto }}"
                        class="border-b hover:bg-gray-50">

                        <td class="p-3 text-center whitespace-nowrap font-medium">
                            {{ $index + 1 }}
                        </td>

                        <td class="p-3 text-center whitespace-nowrap font-medium">
                            G-{{ str_pad($item->id,4,'0',STR_PAD_LEFT) }}
                        </td>

                        <td class="p-2 text-center whitespace-nowrap font-medium">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('j-m-Y') }}
                        </td>

                        <td class="p-3 judul font-medium">
                            {{ $item->judul }}
                        </td>

                        <td class="p-3 deskripsi font-medium">
                            {{ $item->deskripsi }}
                        </td>

                        <td class="p-3 text-center whitespace-nowrap font-medium">
                            @if($item->foto)
                                <img src="{{ asset('storage/'.$item->foto) }}"
                                    class="w-20 h-20 object-cover rounded mx-auto">
                            @else
                                -
                            @endif
                        </td>

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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

{{-- MODAL TAMBAH / EDIT --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl w-96">

        <h2 id="modalTitle" class="text-xl font-bold mb-3">Tambah Kegiatan</h2>

        <div id="errorBox" class="hidden bg-red-100 text-red-700 p-2 mb-3 rounded text-sm"></div>

        <div class="space-y-3">
            <div>
                <label class="text-sm">Judul <span class="text-red-500">*</span></label>
                <input id="judul" type="text" class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="text-sm">Tanggal <span class="text-red-500">*</span></label>
                <input id="tanggal" type="date" class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="text-sm">Deskripsi <span class="text-red-500">*</span></label>
                <textarea id="deskripsi" class="w-full border p-2 rounded"></textarea>
            </div>
            <div>
                <label class="text-sm">Foto <span class="text-red-500">*</span></label>
                <input id="foto" type="file" accept="image/*" class="w-full">
                <div id="fotoPreview" class="mt-2"></div>
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

<script>
const csrfToken = '{{ csrf_token() }}';
const modalTambah = document.getElementById("modalTambah");
const modalHapus = document.getElementById("modalHapus");
const errorBox = document.getElementById("errorBox");
const fotoPreview = document.getElementById("fotoPreview");
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
    document.getElementById("judul").value = "";
    document.getElementById("tanggal").value = "";
    document.getElementById("deskripsi").value = "";
    document.getElementById("foto").value = "";
    fotoPreview.innerHTML = "";
}

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
   TAMBAH
========================= */
document.getElementById("btnTambah").onclick = ()=>{
    editingId = null;
    modalTitle.innerText = "Tambah Kegiatan";
    kosongkanForm();
    hideError();
    modalTambah.classList.remove("hidden");
};

/* =========================
   BATAL
========================= */
document.getElementById("btnBatal").onclick =
    () => modalTambah.classList.add("hidden");

/* =========================
   PREVIEW FOTO
========================= */
document.getElementById("foto").addEventListener("change", function(){
    fotoPreview.innerHTML = "";
    const file = this.files[0];
    if(file){
        const img = document.createElement("img");
        img.src = URL.createObjectURL(file);
        img.className = "w-20 h-20 object-cover rounded";
        fotoPreview.appendChild(img);
    }
});

/* =========================
   SIMPAN EDIT
========================= */
document.getElementById("btnSimpan").onclick = async () => {
    hideError();

    const judul = document.getElementById("judul").value.trim();
    const tanggal = document.getElementById("tanggal").value;
    const deskripsi = document.getElementById("deskripsi").value.trim();
    const fotoInput = document.getElementById("foto");

    if (!judul) return showError("Judul wajib diisi.");
    if (!tanggal) return showError("Tanggal wajib diisi.");
    if (!deskripsi) return showError("Deskripsi wajib diisi.");
    if (!editingId && fotoInput.files.length === 0)
        return showError("Foto wajib diisi.");

    const formData = new FormData();
    formData.append("judul", judul);
    formData.append("tanggal", tanggal);
    formData.append("deskripsi", deskripsi);

    if (fotoInput.files[0]) {
        formData.append("foto", fotoInput.files[0]);
    }

    if (editingId) {
        formData.append("_method", "PUT"); // Laravel
    }

    const url = editingId
        ? `/kegiatan/${editingId}`
        : "{{ route('kegiatan.store') }}";

    const res = await fetch(url, {
        method: "POST", // ⚠️ POST SELALU
        headers: {
            "Accept": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: formData
    });

    if (!res.ok) {
        console.error(await res.text());
        return showError("Gagal menyimpan data.");
    }

    localStorage.setItem(
        "toastMessage",
        editingId ? "Data berhasil diedit" : "Data berhasil ditambah"
    );

    location.reload();
};

/* =========================
   EDIT
========================= */
document.addEventListener("click", function (e) {
    const editBtn = e.target.closest(".editBtn");
    const deleteBtn = e.target.closest(".deleteBtn");

    if (editBtn) {
        const row = editBtn.closest("tr");
        editingId = row.dataset.id;

        document.getElementById("modalTitle").innerText = "Edit Kegiatan";

        document.getElementById("judul").value =
            row.querySelector(".judul").innerText;

        document.getElementById("deskripsi").value =
            row.querySelector(".deskripsi").innerText;

        // 🔥 FORMAT TANGGAL dd-mm-yyyy → yyyy-mm-dd
        const tglText = row.children[2].innerText.trim(); // kolom tanggal
        const [dd, mm, yyyy] = tglText.split("-");
        document.getElementById("tanggal").value = `${yyyy}-${mm.padStart(2,"0")}-${dd.padStart(2,"0")}`;

        // FOTO PREVIEW
        fotoPreview.innerHTML = "";
        if (row.dataset.foto) {
            const img = document.createElement("img");
            img.src = "/storage/" + row.dataset.foto;
            img.className = "w-20 h-20 object-cover rounded";
            fotoPreview.appendChild(img);
        }

        hideError();
        modalTambah.classList.remove("hidden");
    }

    if (deleteBtn) {
        rowToDelete = deleteBtn.closest("tr");
        modalHapus.classList.remove("hidden");
    }
});

/* =========================
   HAPUS
========================= */
hapusTidak.onclick = ()=> modalHapus.classList.add("hidden");
hapusYa.onclick = async ()=> {
    const res = await fetch(`/kegiatan/${rowToDelete.dataset.id}`, {
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

/* =========================
   RESET NOMOR
========================= */
function resetNomor() {
    let no = 1;
    document.querySelectorAll("#kegiatanTable tr").forEach(row => {
        if (row.style.display === "none") return;
        row.children[0].innerText = no++;
    });
}


/* =========================
   SORT TANGGAL + ID
========================= */
let sortAsc = false;
function sortTanggalOnly() {
    const tbody = document.getElementById("kegiatanTable");
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
SEARCH
========================= */
document.getElementById("searchJudulDeskripsi").addEventListener("input", function () {
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll("tbody tr");
    
    rows.forEach(row => {
        const Judul = row.children[3]?.innerText.toLowerCase() || "";
        const Deskripsi = row.children[4]?.innerText.toLowerCase() || "";
        const gabungan = Judul + " " + Deskripsi;
        row.style.display = gabungan.includes(keyword) ? "" : "none";
    });

    resetNomor();
    hitungTotalVisible();
});

/* =========================
   FILTER BULAN & TAHUN
========================= */
const bulanNama = [
    "Januari","Februari","Maret","April","Mei","Juni",
    "Juli","Agustus","September","Oktober","November","Desember"
];

const filterBulan = document.getElementById("filterBulan");
const filterTahun = document.getElementById("filterTahun");

let dataTanggal = []; // { bulan, tahun }
function collectTanggalData() {
    dataTanggal = [];
    const rows = document.querySelectorAll("#kegiatanTable tr");

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

function renderFilters() {
    const bulanTerpilih = filterBulan.value;
    const tahunTerpilih = filterTahun.value;

    const bulanSet = new Set();
    const tahunSet = new Set();

    dataTanggal.forEach(d => {
        if (tahunTerpilih === "" || d.tahun == tahunTerpilih) {
            bulanSet.add(d.bulan);
        }
        if (bulanTerpilih === "" || d.bulan == bulanTerpilih) {
            tahunSet.add(d.tahun);
        }
    });

    // ===== BULAN =====
    filterBulan.innerHTML = "";

    const optSemuaBulan = document.createElement("option");
    optSemuaBulan.value = "";
    optSemuaBulan.textContent = "Semua Bulan";
    optSemuaBulan.selected = bulanTerpilih === "";
    filterBulan.appendChild(optSemuaBulan);

    [...bulanSet].sort((a,b)=>a-b).forEach(b => {
        const opt = document.createElement("option");
        opt.value = b;
        opt.textContent = bulanNama[b];
        opt.selected = String(b) === String(bulanTerpilih);
        filterBulan.appendChild(opt);
    });

    // ===== TAHUN =====
    filterTahun.innerHTML = "";

    const optSemuaTahun = document.createElement("option");
    optSemuaTahun.value = "";
    optSemuaTahun.textContent = "Semua Tahun";
    optSemuaTahun.selected = tahunTerpilih === "";
    filterTahun.appendChild(optSemuaTahun);

    [...tahunSet].sort().forEach(t => {
        const opt = document.createElement("option");
        opt.value = t;
        opt.textContent = t;
        opt.selected = String(t) === String(tahunTerpilih);
        filterTahun.appendChild(opt);
    });
}

function applyFilter() {
    const rows = document.querySelectorAll("#kegiatanTable tr");
    const bulanVal = filterBulan.value;
    const tahunVal = filterTahun.value;

    rows.forEach(row => {
        const teksTanggal = row.children[2]?.innerText.trim();
        if (!teksTanggal) return;

        const [, mm, yyyy] = teksTanggal.split("-");
        const bulan = parseInt(mm, 10) - 1;

        const cocokBulan = bulanVal === "" || bulan == bulanVal;
        const cocokTahun = tahunVal === "" || yyyy == tahunVal;

        row.style.display = cocokBulan && cocokTahun ? "" : "none";
    });

    resetNomor();
    hitungTotalVisible();
}

filterBulan.addEventListener("change", () => {
    renderFilters();
    applyFilter();
});
filterTahun.addEventListener("change", () => {
    renderFilters();
    applyFilter();
});

document.addEventListener("DOMContentLoaded", () => {
    collectTanggalData();
    renderFilters();

    filterBulan.value = "";   // ← paksa semua bulan
    filterTahun.value = "";

    applyFilter();
});

</script>
@endsection
