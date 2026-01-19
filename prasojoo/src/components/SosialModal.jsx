import { useEffect, useState } from "react";

/* ======================
   HELPER
====================== */
const today = new Date().toISOString().split("T")[0];

const formatRupiah = (value) => {
  if (!value) return "";
  return "Rp " + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

const cleanNumber = (value) => {
  return value.replace(/\D/g, "").replace(/^0+/, "");
};

export default function SosialModal({ data, onClose, onSave }) {
  const [form, setForm] = useState({
    tanggal: "",
    subjek: "",
    pemasukan: "",
    pengeluaran: "",
  });

  useEffect(() => {
    if (data) {
      setForm({
        tanggal: data.tanggal || "",
        subjek: data.subjek || "",
        pemasukan: data.pemasukan ? data.pemasukan.toString() : "",
        pengeluaran: data.pengeluaran ? data.pengeluaran.toString() : "",
      });
    }
  }, [data]);

  const handleCurrencyChange = (e) => {
    const { name, value } = e.target;
    const cleaned = cleanNumber(value);

    setForm((prev) => ({
      ...prev,
      [name]: cleaned,
      ...(name === "pemasukan" && cleaned ? { pengeluaran: "" } : {}),
      ...(name === "pengeluaran" && cleaned ? { pemasukan: "" } : {}),
    }));
  };

  const submit = (e) => {
    e.preventDefault();

    if (!form.tanggal || !form.subjek) {
      alert("Tanggal dan Subjek wajib diisi");
      return;
    }

    if (!form.pemasukan && !form.pengeluaran) {
      alert("Isi pemasukan atau pengeluaran");
      return;
    }

    onSave({
      tanggal: form.tanggal,
      subjek: form.subjek,
      pemasukan: Number(form.pemasukan) || 0,
      pengeluaran: Number(form.pengeluaran) || 0,
    });
  };

  return (
    <div className="fixed inset-0 bg-black/30 flex justify-center items-center z-50">
      <div className="bg-white p-6 rounded-lg shadow-xl w-96">

        {/* TITLE */}
        <h2 className="text-xl font-bold mb-4">
          {data ? "Edit Sosial" : "Tambah Sosial"}
        </h2>

        {/* FORM */}
        <form onSubmit={submit} className="space-y-3">

          <div>
            <label className="text-sm">
              Tanggal <span className="text-red-500">*</span>
            </label>
            <input
              type="date"
              value={form.tanggal}
              max={today}
              onChange={(e) =>
                setForm({ ...form, tanggal: e.target.value })
              }
              className="w-full border border-gray-200 p-2 rounded"
              required
            />
          </div>

          <div>
            <label className="text-sm">
              Subjek <span className="text-red-500">*</span>
            </label>
            <input
              type="text"
              value={form.subjek}
              onChange={(e) =>
                setForm({ ...form, subjek: e.target.value })
              }
              className="w-full border border-gray-200 p-2 rounded"
              required
            />
          </div>

          <div>
            <label className="text-sm">Pemasukan</label>
            <input
              type="text"
              name="pemasukan"
              value={formatRupiah(form.pemasukan)}
              onChange={handleCurrencyChange}
              className="w-full border border-gray-200 p-2 rounded"
            />
          </div>

          <div>
            <label className="text-sm">Pengeluaran</label>
            <input
              type="text"
              name="pengeluaran"
              value={formatRupiah(form.pengeluaran)}
              onChange={handleCurrencyChange}
              className="w-full border border-gray-200 p-2 rounded"
            />
          </div>

          {/* ACTION */}
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
