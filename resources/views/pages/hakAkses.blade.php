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
        <table class="min-w-[1000px] w-full bg-white">
            <thead class="bg-[#111827] text-white text-sm">
                <tr>
                    <th class="p-2 text-center">No</th>
                    <th class="p-2 text-center">ID</th>
                    <th class="p-2 text-center">Nama</th>
                    <th class="p-2 text-center">Email</th>
                    <th class="p-2 text-center">Username</th>
                    <th class="p-2 text-center">Password</th>
                    <th class="p-2 text-center">Role</th>
                    <th class="p-2 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-sm">
                @foreach ($users as $i => $u)
                <tr data-id="{{ $u->id }}" class="border-b hover:bg-gray-50">

                    <td class="p-2 text-center whitespace-nowrap font-medium">
                        {{ $i + 1 }}
                    </td>

                    <td class="p-2 text-center whitespace-nowrap font-medium">
                        U-{{ str_pad($u->id,4,'0',STR_PAD_LEFT) }}
                    </td>

                    <td class="p-2 font-medium">
                        {{ $u->nama }}
                    </td>

                    <td class="p-2 whitespace-nowrap font-medium">
                        {{ $u->email }}
                    </td>

                    <td class="p-2 whitespace-nowrap font-medium">
                        {{ $u->username }}
                    </td>

                    <td class="p-2 text-center whitespace-nowrap passwordCell">
                        ********
                    </td>

                    <td class="p-2 text-center whitespace-nowrap roleCell font-medium">
                        {{ ucfirst($u->role) }}
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
    <div class="bg-white p-6 rounded-lg w-96">

        <h2 id="modalTitle" class="font-bold text-lg mb-3">Tambah User</h2>

        <div id="errorBox" class="hidden bg-red-100 text-red-700 p-2 mb-3 rounded text-sm"></div>

        <div class="space-y-3">
            <div>
                <label class="text-sm">Nama <span class="text-red-500">*</span></label>
                <input id="nama" class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="text-sm">Email <span class="text-red-500">*</span></label>
                <input id="email" class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="text-sm">Username <span class="text-red-500">*</span></label>
                <input id="username" class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="text-sm">Password <span class="text-red-500">*</span></label>
                <input id="password" type="text" class="w-full border p-2 rounded">
            </div>

            <div>
                <label class="text-sm">Role <span class="text-red-500">*</span></label>
                <input id="role" class="w-full border p-2 rounded">
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <button id="btnBatal" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
            <button id="btnSimpan" class="px-4 py-2 bg-blue-600 text-white rounded">
                Simpan
            </button>
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
let editingId = null;
let rowToDelete = null;

const modalTambah = document.getElementById('modalTambah');
const modalHapus  = document.getElementById('modalHapus');
const errorBox    = document.getElementById('errorBox');

function showError(msg){
    errorBox.innerText = msg;
    errorBox.classList.remove('hidden');
}
function hideError(){
    errorBox.classList.add('hidden');
}
function resetForm(){
    ['nama','email','username','password','role'].forEach(id=>{
        document.getElementById(id).value = '';
    });
}

// TAMBAH
document.getElementById('btnTambah').onclick = () => {
    editingId = null;
    resetForm();
    hideError();
    modalTambah.classList.remove('hidden');
};

document.getElementById('btnBatal').onclick = () => modalTambah.classList.add('hidden');

// SIMPAN
document.getElementById('btnSimpan').onclick = async () => {
    hideError();

    const payload = {
        nama: nama.value.trim(),
        email: email.value.trim(),
        username: username.value.trim(),
        password: password.value.trim(),
        role: role.value.trim()
    };

    if(!payload.nama || !payload.email || !payload.username || !payload.password || !payload.role){
        return showError('Semua field wajib diisi');
    }

    const url = editingId
        ? `/hak-akses/${editingId}`
        : `{{ route('hakAkses.store') }}`;

    const method = editingId ? 'PUT' : 'POST';

    const res = await fetch(url,{
        method,
        headers:{
            'Content-Type':'application/json',
            'Accept':'application/json',
            'X-CSRF-TOKEN':csrfToken,
            'X-Requested-With': 'XMLHttpRequest' // ✅ FIX UTAMA
        },
        body: JSON.stringify(payload)
    });


    if(res.ok){
        location.reload();
    }else{
        const data = await res.json();
        if(data.errors){
            showError(Object.values(data.errors)[0][0]);
        }else{
            showError('Gagal menyimpan data');
        }
    }
};

// EDIT
document.addEventListener('click', e=>{
    if(e.target.classList.contains('editBtn')){
        const row = e.target.closest('tr');
        editingId = row.dataset.id;

        nama.value     = row.children[2].innerText;
        email.value    = row.children[3].innerText;
        username.value = row.children[4].innerText;
        password.value = row.children[5].innerText;
        role.value     = row.children[6].innerText.toLowerCase();

        modalTambah.classList.remove('hidden');
    }
});

// HAPUS
document.addEventListener('click', e=>{
    if(e.target.classList.contains('deleteBtn')){
        rowToDelete = e.target.closest('tr');
        modalHapus.classList.remove('hidden');
    }
});

hapusTidak.onclick = () => modalHapus.classList.add('hidden');
hapusYa.onclick = async () => {
    const id = rowToDelete.dataset.id;
    await fetch(`/hak-akses/${id}`,{
        method:'DELETE',
        headers:{'X-CSRF-TOKEN':csrfToken}
    });
    location.reload();
};
</script>
@endsection
