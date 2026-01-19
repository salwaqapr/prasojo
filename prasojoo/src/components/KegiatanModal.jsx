import { useEffect, useState, useRef } from "react";
import { API_BASE } from "../lib/api";

const today = new Date().toISOString().split("T")[0];

export default function KegiatanModal({ data, onClose, onSave }) {
  const [form, setForm] = useState({
    judul: "",
    tanggal: "",
    deskripsi: "",
    foto: null,
  });
  const [fotoPreview, setFotoPreview] = useState(null);

  useEffect(() => {
    if (data) {
      setForm({
        judul: data.judul || "",
        tanggal: data.tanggal || "",
        deskripsi: data.deskripsi || "",
        foto: null,
      });
      // data.foto sudah "kegiatan/xxx.jpg"
      setFotoPreview(data.foto ? `${API_BASE}/storage/${data.foto}` : null);
    } else {
      setForm({ judul: "", tanggal: "", deskripsi: "", foto: null });
      setFotoPreview(null);
    }
  }, [data]);

  const handleChange = (e) => {
    const { name, value, files } = e.target;

    if (name === "foto") {
      const file = files?.[0] || null;
      console.log("FILE DIPILIH:", file);
      setForm((prev) => ({ ...prev, foto: file }));
      if (file) setFotoPreview(URL.createObjectURL(file));
      return;
    }

    setForm((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!form.judul || !form.tanggal || !form.deskripsi) {
      alert("Judul, Tanggal, dan Deskripsi wajib diisi");
      return;
    }
    if (!data && !form.foto) {
      alert("Foto wajib diisi");
      return;
    }

    const formData = new FormData();
    formData.append("judul", form.judul);
    formData.append("tanggal", form.tanggal);
    formData.append("deskripsi", form.deskripsi);
    if (form.foto) formData.append("foto", form.foto);
    if (data?.id) formData.append("_method", "PUT");

    await onSave(formData, data?.id);
    onClose();
  };

  const fileRef = useRef(null);

  return (
    <div className="fixed inset-0 bg-black/30 flex justify-center items-center z-50">
      <div className="bg-white p-6 rounded-lg shadow-xl w-96">
        <h2 className="text-xl font-bold mb-4">
          {data ? "Edit Kegiatan" : "Tambah Kegiatan"}
        </h2>

        <form onSubmit={handleSubmit} className="space-y-3">
          <div>
            <label className="text-sm">
              Judul <span className="text-red-500">*</span>
            </label>
            <input
              type="text"
              name="judul"
              value={form.judul}
              onChange={handleChange}
              className="w-full border border-gray-200 p-2 rounded"
              required
            />
          </div>

          <div>
            <label className="text-sm">
              Tanggal <span className="text-red-500">*</span>
            </label>
            <input
              type="date"
              name="tanggal"
              value={form.tanggal}
              max={today}
              onChange={handleChange}
              className="w-full border border-gray-200 p-2 rounded"
              required
            />
          </div>

          <div>
            <label className="text-sm">
              Deskripsi <span className="text-red-500">*</span>
            </label>
            <textarea
              name="deskripsi"
              value={form.deskripsi}
              onChange={handleChange}
              className="w-full border border-gray-200 p-2 rounded"
              required
            />
          </div>

          <div>
            <label className="text-sm">
              Foto {!data && <span className="text-red-500">*</span>}
            </label>

            {/* input asli disembunyikan */}
            <input
              ref={fileRef}
              type="file"
              name="foto"
              accept="image/*"
              onChange={handleChange}
              className="hidden"
            />

            {/* UI custom: tombol (ini yang jadi "Choose file" versi kamu) */}
            <div className="mt-1 flex items-center gap-2">
              <button
                type="button"
                onClick={() => fileRef.current?.click()}
                className="border border-gray-300 bg-gray-100 px-3 py-1 rounded text-sm hover:bg-gray-200"
              >
                Choose file
              </button>

              <span className="text-sm text-gray-600">
                {form.foto ? form.foto.name : "No file chosen"}
              </span>
            </div>

            {fotoPreview && (
              <div className="mt-2">
                <img
                  src={fotoPreview}
                  className="w-20 h-20 object-cover rounded"
                  alt="preview"
                />
              </div>
            )}
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
  );
}
