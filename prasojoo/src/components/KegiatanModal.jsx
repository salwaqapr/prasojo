import { useEffect, useState, useRef } from "react";
import { API_BASE } from "../lib/api";

const today = new Date().toISOString().split("T")[0];

// ambil nama file dari path "kegiatan/beranda.png" => "beranda.png"
const getBaseName = (path = "") => {
  if (!path) return "";
  const clean = String(path).split("?")[0]; // jaga-jaga kalau ada querystring
  const parts = clean.split("/");
  return parts[parts.length - 1] || "";
};

export default function KegiatanModal({ data, onClose, onSave }) {
  const [form, setForm] = useState({
    judul: "",
    tanggal: "",
    deskripsi: "",
    foto: null, // File baru (kalau user pilih)
  });

  const [fotoPreview, setFotoPreview] = useState(null);

  // simpan URL object biar bisa di-revoke
  const objectUrlRef = useRef(null);
  const fileRef = useRef(null);

  useEffect(() => {
    // bersihin object URL lama kalau ada
    if (objectUrlRef.current) {
      URL.revokeObjectURL(objectUrlRef.current);
      objectUrlRef.current = null;
    }

    if (data) {
      setForm({
        judul: data.judul || "",
        tanggal: data.tanggal || "",
        deskripsi: data.deskripsi || "",
        foto: null,
      });

      // preview dari foto lama (string path)
      setFotoPreview(data.foto ? `${API_BASE}/storage/${data.foto}` : null);

      // reset input file (biar bersih)
      if (fileRef.current) fileRef.current.value = "";
    } else {
      setForm({ judul: "", tanggal: "", deskripsi: "", foto: null });
      setFotoPreview(null);
      if (fileRef.current) fileRef.current.value = "";
    }

    // cleanup saat unmount
    return () => {
      if (objectUrlRef.current) {
        URL.revokeObjectURL(objectUrlRef.current);
        objectUrlRef.current = null;
      }
    };
  }, [data]);

  const handleChange = (e) => {
    const { name, value, files } = e.target;

    if (name === "foto") {
      const file = files?.[0] || null;

      // revoke object URL sebelumnya
      if (objectUrlRef.current) {
        URL.revokeObjectURL(objectUrlRef.current);
        objectUrlRef.current = null;
      }

      setForm((prev) => ({ ...prev, foto: file }));

      if (file) {
        const url = URL.createObjectURL(file);
        objectUrlRef.current = url;
        setFotoPreview(url);
      } else {
        // kalau batal pilih file, balik ke foto lama (kalau ada)
        setFotoPreview(data?.foto ? `${API_BASE}/storage/${data.foto}` : null);
      }
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

  // ✅ nama file yang ditampilkan:
  // - kalau user pilih file baru => form.foto.name
  // - kalau edit & belum pilih file => ambil dari data.foto
  const displayedFileName =
    form.foto?.name ||
    data?.foto_original ||
    (data?.foto ? getBaseName(data.foto) : "");

  return (
    <div className="fixed inset-0 bg-black/30 z-50 flex items-center justify-center p-3 sm:p-6">
      {/* ✅ panel modal responsive + bisa scroll */}
      <div className="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[85dvh] overflow-hidden">
        <div className="p-5 sm:p-6 overflow-y-auto max-h-[85dvh]">
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

              <input
                ref={fileRef}
                type="file"
                name="foto"
                accept="image/*"
                onChange={handleChange}
                className="hidden"
              />

              {/* ✅ mobile: biar tidak kepotong, nama file bisa turun */}
              <div className="mt-1 flex flex-col sm:flex-row sm:items-center gap-2">
                <button
                  type="button"
                  onClick={() => fileRef.current?.click()}
                  className="border border-gray-300 bg-gray-100 px-3 py-1 rounded text-sm hover:bg-gray-200 w-fit"
                >
                  Choose file
                </button>

                <span className="text-sm text-gray-600 break-all">
                  {displayedFileName || "No file chosen"}
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
    </div>
  );
}
