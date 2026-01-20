import { useEffect, useState } from "react";

export default function HakAksesModal({ data, onClose, onSave }) {
  const [form, setForm] = useState({
    nama: "",
    username: "",
    email: "",
    password: "",
    role: "",
  });

  // ✅ STATE TOGGLE PASSWORD
  const [showPassword, setShowPassword] = useState(false);
  const isEdit = Boolean(data);

  useEffect(() => {
    if (data) {
      // MODE EDIT
      setForm({
        nama: data.nama || "",
        username: data.username || "",
        email: data.email || "",
        password: "",
        role: data.role || "",
      });
    } else {
      // MODE TAMBAH (RESET TOTAL)
      setForm({
        nama: "",
        username: "",
        email: "",
        password: "",
        role: "",
      });
    }
  }, [data]);

  const submit = (e) => {
    e.preventDefault();

    if (!form.nama || !form.username || !form.email || !form.role) {
      alert("Semua field wajib diisi");
      return;
    }

    onSave(form);
  };

  return (
    <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-3 sm:p-6">
      <div className="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[85dvh] overflow-hidden">
        <div className="p-5 sm:p-6 overflow-y-auto max-h-[85dvh]">
          <h2 className="text-xl font-bold mb-4">
            {data ? "Edit Hak Akses" : "Tambah Hak Akses"}
          </h2>

          <form autoComplete="off" className="space-y-3">
            {/* NAMA */}
            <div>
              <label className="text-sm">
                Nama <span className="text-red-500">*</span>
              </label>
              <input
                type="text"
                value={form.nama}
                onChange={(e) => setForm({ ...form, nama: e.target.value })}
                className="w-full border border-gray-200 p-2 rounded"
              />
            </div>

            {/* USERNAME */}
            <div>
              <label className="text-sm">
                Username <span className="text-red-500">*</span>
              </label>
              <input
                type="text"
                value={form.username}
                autoComplete="off"
                onChange={(e) => setForm({ ...form, username: e.target.value })}
                className="w-full border border-gray-200 p-2 rounded"
              />
            </div>

            {/* EMAIL */}
            <div>
              <label className="text-sm">
                Email <span className="text-red-500">*</span>
              </label>
              <input
                type="email"
                value={form.email}
                onChange={(e) => setForm({ ...form, email: e.target.value })}
                className="w-full border border-gray-200 p-2 rounded"
              />
            </div>

            {/* PASSWORD + EYE */}
            <div>
              <label className="text-sm">
                Password {data ? "(opsional)" : <span className="text-red-500">*</span>}
              </label>

              <div className="relative">
                <input
                  type={showPassword ? "text" : "password"}
                  value={form.password}
                  autoComplete="new-password"
                  data-lpignore="true"
                  className="w-full border border-gray-200 p-2 rounded pr-10"
                  onChange={(e) => setForm({ ...form, password: e.target.value })}
                />

                {/* BUTTON TOGGLE */}
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-black"
                >
                  {/* EYE OPEN */}
                  {!showPassword && (
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      className="h-5 w-5"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                      />
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5
                        c4.478 0 8.268 2.943 9.542 7
                        -1.274 4.057-5.064 7-9.542 7
                        -4.477 0-8.268-2.943-9.542-7z"
                      />
                    </svg>
                  )}

                  {/* EYE CLOSE */}
                  {showPassword && (
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      className="h-5 w-5"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19
                        c-4.478 0-8.268-2.943-9.542-7
                        a9.956 9.956 0 012.042-3.368"
                      />
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        d="M6.223 6.223A9.956 9.956 0 0112 5
                        c4.478 0 8.268 2.943 9.542 7
                        a9.964 9.964 0 01-4.293 5.293"
                      />
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        d="M3 3l18 18"
                      />
                    </svg>
                  )}
                </button>
              </div>

              {isEdit && (
                <p className="text-xs text-gray-500 mt-1">
                  Kosongkan jika tidak ingin mengubah password
                </p>
              )}
            </div>

            {/* ROLE */}
            <div>
              <label className="text-sm">
                Role <span className="text-red-500">*</span>
              </label>
              <select
                value={form.role}
                onChange={(e) => setForm({ ...form, role: e.target.value })}
                className="w-full border border-gray-200 p-2 rounded"
              >
                <option value="">Pilih Role </option>
                <option value="admin">Admin</option>
                <option value="pengurus">Pengurus</option>
                <option value="anggota">Anggota</option>
              </select>
            </div>

            {/* BUTTON */}
            <div className="flex justify-end gap-2 pt-3">
              <button
                type="button"
                onClick={onClose}
                className="px-4 py-2 bg-gray-300 rounded"
              >
                Batal
              </button>
              <button
                type="submit"
                onClick={submit}
                className="px-4 py-2 bg-blue-600 text-white rounded"
              >
                Simpan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
