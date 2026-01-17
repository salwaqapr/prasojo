@extends('layouts.main')

@section('content')
<div class="p-3">

    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        {{-- Search --}}
       <div class="relative w-full lg:w-60">
            <!-- DECOY AUTOFILL (WAJIB, JANGAN DIHAPUS) -->
            <input
                type="text"
                name="fake-username"
                autocomplete="username"
                style="position:absolute; opacity:0; height:0; width:0;"
            >
            <input
                type="password"
                name="fake-password"
                autocomplete="current-password"
                style="position:absolute; opacity:0; height:0; width:0;"
            >

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

            <!-- INPUT SEARCH UTAMA -->
            <input
                type="search"
                id="searchNama"
                name="searchNamaDummy"
                placeholder="Cari Nama, Email, Username"
                autocomplete="new-password"
                autocorrect="off"
                autocapitalize="off"
                spellcheck="false"
                class="border px-2 py-1 pl-8 rounded-lg text-sm w-full
                    focus:outline-none focus:ring-2 focus:ring-gray-300"
            />
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
                    <th class="p-2 text-center">Nama</th>
                    <th class="p-2 text-center">Email</th>
                    <th class="p-2 text-center">Username</th>
                    <th class="p-2 text-center">Password</th>
                    <th class="p-2 text-center">Role</th>
                    <th class="p-2 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-sm" id="hakAksesTable">
                @foreach ($users as $i => $u)
                <tr data-id="{{ $u->id }}" class="border-b hover:bg-gray-50">

                    <td class="p-2 text-center whitespace-nowrap font-medium">
                        {{ $i + 1 }}
                    </td>

                    <td class="p-2 text-center whitespace-nowrap font-medium">
                        U-{{ str_pad($u->id,4,'0',STR_PAD_LEFT) }}
                    </td>

                    <td class="p-2 font-medium">{{ $u->nama }}</td>

                    <td class="p-2 whitespace-nowrap font-medium">{{ $u->email }}</td>

                    <td class="p-2 whitespace-nowrap font-medium">{{ $u->username }}</td>

                    <td class="p-2 text-center whitespace-nowrap passwordCell">********</td>

                    <td class="p-2 text-center whitespace-nowrap roleCell font-medium">{{ ucfirst($u->role) }}</td>

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
                <label class="text-sm">Password</label>

                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        placeholder="******"
                        class="w-full border p-2 rounded pr-10"
                    >

                    <button
                        type="button"
                        onclick="togglePassword()"
                        class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-black"
                    >
                        <!-- eye open -->
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5
                                     c4.478 0 8.268 2.943 9.542 7
                                     -1.274 4.057-5.064 7-9.542 7
                                     -4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>

                        <!-- eye close -->
                        <svg id="eyeClose" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 hidden"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19
                                    c-4.478 0-8.268-2.943-9.542-7
                                    a9.956 9.956 0 012.042-3.368"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6.223 6.223A9.956 9.956 0 0112 5
                                     c4.478 0 8.268 2.943 9.542 7
                                     a9.964 9.964 0 01-4.293 5.293"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3l18 18"/>
                        </svg>
                    </button>
                </div>

                <p id="passwordHint" class="text-xs text-gray-500 mt-1 hidden">
                    Kosongkan jika tidak ingin mengubah password
                </p>
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
const modalTambah = document.getElementById('modalTambah');
const modalHapus  = document.getElementById('modalHapus');
const modalTitle  = document.getElementById('modalTitle');
const errorBox    = document.getElementById('errorBox');
const btnSimpan = document.getElementById('btnSimpan');
let editingId = null;
let rowToDelete = null;

function showError(msg){
    errorBox.innerText = msg;
    errorBox.classList.remove('hidden');
}
function hideError(){
    errorBox.classList.add('hidden');
}
function resetForm(){
    ['nama','email','username','role'].forEach(id=>{
        document.getElementById(id).value = '';
    });

    password.value = '';
    password.placeholder = '******';
    passwordHint.classList.add('hidden');
}

function togglePassword() {
    const input = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClose = document.getElementById('eyeClose');

    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClose.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClose.classList.add('hidden');
    }
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
   TAMBAH
========================= */
document.getElementById('btnTambah').onclick = () => {
    editingId = null;
    resetForm();
    hideError();
    modalTitle.innerText = 'Tambah User';
    modalTambah.classList.remove('hidden');
};

/* =========================
   BATAL
========================= */
document.getElementById('btnBatal').onclick = () => modalTambah.classList.add('hidden');

/* =========================
   SIMPAN (TAMBAH / EDIT)
========================= */
btnSimpan.onclick = async () => {
    hideError();

    const payload = {
        nama: nama.value.trim(),
        email: email.value.trim(),
        username: username.value.trim(),
        role: role.value.trim()
    };

    if (!payload.nama) return showError("Nama wajib diisi.");
    if (!payload.email) return showError("Email wajib diisi.");
    if (!payload.username) return showError("Username wajib diisi.");
    if (!payload.role) return showError("Role wajib diisi.");

    if (password.value.trim() !== '') {
        payload.password = password.value.trim();
    }

    const url = editingId
        ? `/hak-akses/${editingId}`
        : `{{ route('hakAkses.store') }}`;

    const method = editingId ? 'PUT' : 'POST';

    const res = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(payload)
    });

    if (!res.ok) {
        showError("Gagal menyimpan data.");
        return;
    }

    localStorage.setItem(
        "toastMessage",
        editingId ? "Data berhasil diedit" : "Data berhasil ditambah"
    );

    location.reload();
};

/* =========================
   MODE EDIT & HAPUS
========================= */
document.addEventListener('click', e => {

    const editBtn   = e.target.closest('.editBtn');
    const deleteBtn = e.target.closest('.deleteBtn');

    /* ===== EDIT ===== */
    if (editBtn) {
        const row = editBtn.closest('tr');
        editingId = row.dataset.id;

        nama.value     = row.children[2].innerText;
        email.value    = row.children[3].innerText;
        username.value = row.children[4].innerText;
        role.value     = row.children[6].innerText.toLowerCase();

        // password reset
        password.value = '';
        password.type = 'password';
        password.placeholder = '******';

        // reset icon mata
        eyeOpen.classList.remove('hidden');
        eyeClose.classList.add('hidden');

        passwordHint.classList.remove('hidden');

        modalTitle.innerText = 'Edit User';
        modalTambah.classList.remove('hidden');
        return;
    }

    /* ===== HAPUS ===== */
    if (deleteBtn) {
        rowToDelete = deleteBtn.closest('tr');
        modalHapus.classList.remove('hidden');
        return;
    }
});

/* =========================
   HAPUS
========================= */
hapusTidak.onclick = () => {
    modalHapus.classList.add('hidden');
};

hapusYa.onclick = async () => {
    if (!rowToDelete) return;

    const id = rowToDelete.dataset.id;

    const res = await fetch(`/hak-akses/${id}`,{
        method:'DELETE',
        headers:{ 'X-CSRF-TOKEN':csrfToken }
    });

    if (res.ok) {
        localStorage.setItem('toastMessage','User berhasil dihapus');
        location.reload();
    } else {
        alert('Gagal menghapus data');
    }
};

/* =========================
   RESET NOMOR
========================= */
function resetNomor() {
    let no = 1;
    document.querySelectorAll("#hakAksesTable tr").forEach(row => {
        if (row.style.display === "none") return;
        row.children[0].innerText = no++;
    });
}

document.getElementById("searchNama").addEventListener("input", function () {
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll("tbody tr");

    rows.forEach(row => {
        const kolomNama = row.children[2]?.innerText.toLowerCase() || "";
        const kolomEmail = row.children[3]?.innerText.toLowerCase() || "";
        const kolomUsername = row.children[4]?.innerText.toLowerCase() || "";

        const gabungan = kolomNama + " " + kolomEmail + " " + kolomUsername;
        row.style.display = gabungan.includes(keyword) ? "" : "none";
    });

    resetNomor(); // ✅ WAJIB
});



</script>
@endsection
