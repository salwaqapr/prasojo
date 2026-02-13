import { useEffect, useState } from "react";

export default function MemberModal({ data, onClose, onSave }) {
  const [nama, setNama] = useState("");

  useEffect(() => {
    setNama(data?.nama || "");
  }, [data]);

  const submit = (e) => {
    e.preventDefault();
    if (!nama.trim()) return alert("Nama wajib diisi");
    onSave({ nama: nama.trim() });
  };

  return (
    <div className="fixed inset-0 bg-black/30 z-50 flex items-center justify-center p-3">
      <div className="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
        <div className="p-5">
          <h2 className="text-xl font-bold mb-4">
            {data ? "Edit Anggota" : "Tambah Anggota"}
          </h2>

          <form onSubmit={submit} className="space-y-3">
            <div>
              <label className="text-sm">Nama</label>
              <input
                type="text"
                value={nama}
                onChange={(e) => setNama(e.target.value)}
                className="w-full border border-gray-200 p-2 rounded"
                placeholder="Nama anggota"
                required
              />
            </div>

            <div className="flex justify-end gap-2 mt-4">
              <button
                type="button"
                onClick={onClose}
                className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
              >
                Batal
              </button>
              <button
                type="submit"
                className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
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
