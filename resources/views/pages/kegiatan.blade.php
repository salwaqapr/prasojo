@extends('layouts.main')

@section('content')
<div class="p-2">

    {{-- Tambah --}}
    <div class="flex justify-end h-8 gap-2">
            <button id="btnTambah"
                class="bg-green-700 text-white px-3 py-1 rounded hover:bg-green-800 transition">
                Tambah
            </button>
    </div>

    {{-- Tabel --}}
    <div class="relative mt-6">
        <div class="overflow-x-auto rounded-2xl">
        <table class="min-w-[1100px] w-full bg-white">
            <thead class="bg-[#111827] text-white text-sm">
                <tr>
                    <th class="p-2 text-center">No</th>
                    <th class="p-2 text-center">ID</th>
                    <th class="p-2 text-center">Tanggal</th>
                    <th class="p-2 text-center">Judul</th>
                    <th class="p-2 text-center">Deskripsi</th>
                    <th class="p-2 text-center">Foto</th>
                    <th class="p-2 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-sm">
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

function showError(msg){ errorBox.innerText = msg; errorBox.classList.remove("hidden"); }
function hideError(){ errorBox.classList.add("hidden"); }
function kosongkanForm(){
    document.getElementById("judul").value = "";
    document.getElementById("tanggal").value = "";
    document.getElementById("deskripsi").value = "";
    document.getElementById("foto").value = "";
    fotoPreview.innerHTML = "";
}

document.getElementById("btnTambah").onclick = ()=>{
    editingId = null;
    kosongkanForm();
    hideError();
    document.getElementById("modalTitle").innerText = "Tambah Kegiatan";
    modalTambah.classList.remove("hidden");
};
document.getElementById("btnBatal").onclick = ()=> modalTambah.classList.add("hidden");

// Preview Foto
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

// SIMPAN / EDIT
document.getElementById("btnSimpan").onclick = async ()=>{
    hideError();
    const judul = document.getElementById("judul").value;
    const tanggal = document.getElementById("tanggal").value;
    const deskripsi = document.getElementById("deskripsi").value;
    const fotoInput = document.getElementById("foto");
    
    if(!judul) return showError("Judul wajib diisi.");
    if(!tanggal) return showError("Tanggal wajib diisi.");
    if(!deskripsi) return showError("Deskripsi wajib diisi.");
    if(!editingId && fotoInput.files.length === 0) return showError("Foto wajib diisi.");

    const formData = new FormData();
    formData.append("judul", judul);
    formData.append("tanggal", tanggal);
    formData.append("deskripsi", deskripsi);
    if(fotoInput.files[0]) formData.append("foto", fotoInput.files[0]);
    if(editingId) formData.append("_method", "PUT");

    const url = editingId ? `/kegiatan/${editingId}` : "{{ route('kegiatan.store') }}";
    const res = await fetch(url, {
        method: "POST",
        body: formData,
        headers: {"X-CSRF-TOKEN": csrfToken}
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
        document.getElementById("modalTitle").innerText = "Edit Kegiatan";
        document.getElementById("judul").value = row.querySelector(".judul").innerText;
        document.getElementById("tanggal").value = row.querySelector(".tanggal").innerText;
        document.getElementById("deskripsi").value = row.querySelector(".deskripsi").innerText;

        fotoPreview.innerHTML = "";
        const fotoName = row.dataset.foto;
        if(fotoName){
            const img = document.createElement("img");
            img.src = "/storage/" + fotoName;
            img.className = "w-20 h-20 object-cover rounded";
            fotoPreview.appendChild(img);
        }

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
    const res = await fetch(`/kegiatan/${id}`,{
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
