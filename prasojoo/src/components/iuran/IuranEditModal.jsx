import { useEffect, useMemo, useState } from "react";
import { cleanNumber, formatRupiah } from "../../utils/rupiah";
import {
  deletePayment,
  getMemberPayments,
  updatePayment,
  updateIuranMember,
  createMemberPayment,
} from "../../services/iuranApi";

export default function IuranEditModal({ member, onClose, onUpdated }) {
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);

  const [rows, setRows] = useState([]); // rows yang tampil (yang belum ditandai hapus)
  const [deletedIds, setDeletedIds] = useState([]); // paymentId yang ditandai hapus
  const [nama, setNama] = useState(member?.nama || "");

  useEffect(() => {
    setNama(member?.nama || "");
  }, [member]);

  useEffect(() => {
    (async () => {
      setLoading(true);
      const res = await getMemberPayments(member.id);
      setRows(
        res.map((r) => ({
          ...r,
          bulanStr: String(r.bulan ?? ""),
          tahunStr: String(r.tahun ?? ""),
          nominalStr: String(r.nominal ?? ""),
          originalBulan: Number(r.bulan),
          originalTahun: Number(r.tahun),
        }))
      );
      setDeletedIds([]); // reset setiap buka modal
      setLoading(false);
    })();
  }, [member]);

  const namaTrim = useMemo(() => (nama || "").trim(), [nama]);
  const namaChanged = useMemo(() => {
    return namaTrim !== String(member?.nama || "").trim();
  }, [namaTrim, member]);

  const disableSaveAll = useMemo(() => {
    if (!namaTrim) return true;
    return saving;
  }, [namaTrim, saving]);

  const updateRow = (idx, patch) => {
    setRows((prev) => {
      const copy = [...prev];
      copy[idx] = { ...copy[idx], ...patch };
      return copy;
    });
  };

  // ✅ Simpan perubahan baris:
  // - jika bulan/tahun tetap => updatePayment
  // - jika bulan/tahun berubah => deletePayment lama + createMemberPayment baru
  // Catatan: baris yang sudah ditandai hapus tidak ikut diproses karena sudah hilang dari rows.
  const saveRow = async (r) => {
    const bulanNum = Number(r.bulanStr);
    const tahunNum = Number(r.tahunStr);

    if (!bulanNum || bulanNum < 1 || bulanNum > 12) {
      throw new Error("Bulan harus 1-12");
    }
    if (!tahunNum || tahunNum < 2000 || tahunNum > 2100) {
      throw new Error("Tahun harus 2000-2100");
    }

    const nominalNum = Number(cleanNumber(r.nominalStr)) || 0;

    const changedMonthYear =
      bulanNum !== Number(r.originalBulan) || tahunNum !== Number(r.originalTahun);

    if (!changedMonthYear) {
      await updatePayment(r.id, {
        tanggal: r.tanggal,
        nominal: nominalNum,
      });
      return;
    }

    // pindah bulan/tahun:
    await deletePayment(r.id);
    await createMemberPayment(member.id, {
      tanggal: r.tanggal,
      bulan: bulanNum,
      tahun: tahunNum,
      nominal: nominalNum,
    });
  };

  const handleSaveAll = async () => {
    if (!namaTrim) return alert("Nama wajib diisi");
    setSaving(true);

    try {
      // 1) simpan nama kalau berubah
      if (namaChanged) {
        await updateIuranMember(member.id, { nama: namaTrim });
      }

      // 2) eksekusi hapus yang ditandai (baru ke DB pas Simpan)
      if (deletedIds.length > 0) {
        for (const id of deletedIds) {
          await deletePayment(id);
        }
      }

      // 3) simpan semua baris yang tersisa
      for (const r of rows) {
        await saveRow(r);
      }

      // 4) update UI parent + notif hanya setelah simpan
      const hadDelete = deletedIds.length > 0;
      onUpdated?.({
        memberId: member.id,
        nama: namaTrim,
        type: hadDelete ? "delete" : "update",
      });

      onClose();
    } catch (err) {
      alert(err?.message || "Gagal menyimpan perubahan");
    } finally {
      setSaving(false);
    }
  };

  // ✅ Hapus baris: hanya hapus dari tampilan + tandai, JANGAN panggil API & JANGAN notif
  const handleDelete = (paymentId) => {
    const ok = confirm("Hapus iuran ini?");
    if (!ok) return;

    setDeletedIds((prev) => (prev.includes(paymentId) ? prev : [...prev, paymentId]));
    setRows((prev) => prev.filter((x) => x.id !== paymentId));
  };

  return (
    <div className="fixed inset-0 bg-black/30 z-50 flex items-center justify-center p-3">
      <div className="bg-white rounded-lg shadow-xl w-full max-w-2xl overflow-hidden">
        <div className="p-5 max-h-[85dvh] overflow-y-auto">
          <h2 className="text-xl font-bold mb-1">Edit Iuran</h2>
          <p className="text-sm text-gray-600 mb-4">{namaTrim || "-"}</p>

          {/* Edit nama */}
          <div className="border rounded-lg p-3 mb-4 bg-gray-50">
            <label className="text-xs text-gray-600">Nama Anggota</label>
            <input
              type="text"
              value={nama}
              onChange={(e) => setNama(e.target.value)}
              className="w-full border border-gray-200 p-2 rounded bg-white mt-1"
              placeholder="Nama anggota"
            />
            <p className="text-xs text-gray-500 mt-1">
              Nama ikut tersimpan saat klik tombol <b>Simpan</b> di bawah.
            </p>

            {deletedIds.length > 0 && (
              <p className="text-xs text-red-600 mt-2">
                {deletedIds.length} baris ditandai untuk dihapus. Data baru benar-benar terhapus setelah klik <b>Simpan</b>.
              </p>
            )}
          </div>

          {loading ? (
            <p className="text-sm text-gray-500">Loading...</p>
          ) : rows.length === 0 ? (
            <p className="text-sm text-gray-500">
              {deletedIds.length > 0
                ? "Semua baris sedang ditandai hapus. Klik Simpan untuk menghapus permanen, atau klik Batal untuk membatalkan."
                : "Belum ada iuran."}
            </p>
          ) : (
            <div className="space-y-2">
              {rows.map((r, idx) => (
                <div key={r.id} className="border rounded-lg p-3">
                  <div className="grid grid-cols-4 gap-2 items-end">
                    <div>
                      <label className="text-xs text-gray-600">Bulan</label>
                      <input
                        type="number"
                        min={1}
                        max={12}
                        value={r.bulanStr}
                        onChange={(e) =>
                          updateRow(idx, { bulanStr: cleanNumber(e.target.value) })
                        }
                        className="w-full border border-gray-200 p-2 rounded"
                      />
                    </div>

                    <div>
                      <label className="text-xs text-gray-600">Tahun</label>
                      <input
                        type="number"
                        min={2000}
                        max={2100}
                        value={r.tahunStr}
                        onChange={(e) =>
                          updateRow(idx, { tahunStr: cleanNumber(e.target.value) })
                        }
                        className="w-full border border-gray-200 p-2 rounded"
                      />
                    </div>

                    <div>
                      <label className="text-xs text-gray-600">Tanggal</label>
                      <input
                        type="date"
                        value={r.tanggal}
                        onChange={(e) => updateRow(idx, { tanggal: e.target.value })}
                        className="w-full border border-gray-200 p-2 rounded"
                      />
                    </div>

                    <div>
                      <label className="text-xs text-gray-600">Nominal</label>
                      <input
                        type="text"
                        value={formatRupiah(r.nominalStr)}
                        onChange={(e) =>
                          updateRow(idx, { nominalStr: cleanNumber(e.target.value) })
                        }
                        className="w-full border border-gray-200 p-2 rounded"
                      />
                    </div>
                  </div>

                  <div className="flex justify-end mt-2">
                    <button
                      onClick={() => handleDelete(r.id)}
                      className="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700"
                    >
                      Hapus Baris
                    </button>
                  </div>
                </div>
              ))}
            </div>
          )}

          <div className="flex justify-end gap-2 mt-4">
            <button
              onClick={onClose}
              disabled={saving}
              className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 disabled:opacity-50"
            >
              Batal
            </button>

            <button
              onClick={handleSaveAll}
              disabled={disableSaveAll}
              className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
            >
              {saving ? "Menyimpan..." : "Simpan"}
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
