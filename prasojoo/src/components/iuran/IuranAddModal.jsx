import { useEffect, useMemo, useState } from "react";
import { cleanNumber, formatRupiah } from "../../utils/rupiah";
import { getMemberPayments } from "../../services/iuranApi";

const now = new Date();
const today = now.toISOString().split("T")[0];
const currentMonth = now.getMonth() + 1; // 1-12
const currentYear = now.getFullYear();

const bulanNama = [
  "", "Januari", "Februari", "Maret", "April", "Mei", "Juni",
  "Juli", "Agustus", "September", "Oktober", "November", "Desember",
];

export default function IuranAddModal({ member, onClose, onSave }) {
  const [form, setForm] = useState({
    tanggal: today,
    bulan: String(currentMonth),
    tahun: String(currentYear),
    nominal: "",
  });

  const [checkingDup, setCheckingDup] = useState(false);
  const [isDuplicate, setIsDuplicate] = useState(false);

  const selectedYear = useMemo(
    () => Number(form.tahun) || currentYear,
    [form.tahun]
  );

  // bulan yang boleh dipilih:
  // - kalau tahun sekarang: mulai dari bulan sekarang
  // - kalau tahun depan/lebih: 1..12
  const monthOptions = useMemo(() => {
    const startMonth = selectedYear === currentYear ? currentMonth : 1;
    return Array.from({ length: 12 - startMonth + 1 }, (_, idx) => startMonth + idx);
  }, [selectedYear]);

  // kalau tahun berubah dan bulan jadi tidak valid -> geser ke bulan valid pertama
  useEffect(() => {
    const bulanNum = Number(form.bulan);
    const minAllowed = selectedYear === currentYear ? currentMonth : 1;

    if (!bulanNum || bulanNum < minAllowed) {
      setForm((p) => ({ ...p, bulan: String(minAllowed) }));
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [selectedYear]);

  // ✅ cek duplikasi: kalau iuran bulan+tahun sudah ada untuk member ini
  useEffect(() => {
    if (!member?.id) return;

    const bulanNum = Number(form.bulan);
    const tahunNum = Number(form.tahun);
    if (!bulanNum || !tahunNum) return;

    let alive = true;

    (async () => {
      try {
        setCheckingDup(true);
        const payments = await getMemberPayments(member.id);

        const dup = (payments || []).some(
          (p) => Number(p.bulan) === bulanNum && Number(p.tahun) === tahunNum
        );

        if (alive) setIsDuplicate(dup);
      } catch {
        // kalau gagal cek, jangan blok user (anggap tidak duplicate)
        if (alive) setIsDuplicate(false);
      } finally {
        if (alive) setCheckingDup(false);
      }
    })();

    return () => {
      alive = false;
    };
  }, [member?.id, form.bulan, form.tahun]);

  const disableSave = useMemo(() => {
    if (!form.tanggal || !form.bulan || !form.tahun || !cleanNumber(form.nominal)) return true;
    if (checkingDup) return true;
    if (isDuplicate) return true;
    return false;
  }, [form, checkingDup, isDuplicate]);

  const handleNominal = (e) => {
    const cleaned = cleanNumber(e.target.value);
    setForm((p) => ({ ...p, nominal: cleaned }));
  };

  const submit = (e) => {
    e.preventDefault();
    if (disableSave) return;

    onSave({
      tanggal: form.tanggal,
      bulan: Number(form.bulan),
      tahun: Number(form.tahun),
      nominal: Number(form.nominal) || 0,
    });
  };

  const bulanLabel = bulanNama[Number(form.bulan)] || form.bulan;

  return (
    <div className="fixed inset-0 bg-black/30 z-50 flex items-center justify-center p-3">
      <div className="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
        <div className="p-5">
          <h2 className="text-xl font-bold mb-1">Tambah Iuran</h2>
          <p className="text-sm text-gray-600 mb-4">{member?.nama}</p>

          {isDuplicate && (
            <div className="mb-3 rounded-lg border border-yellow-300 bg-yellow-50 p-3 text-sm text-yellow-800">
              Iuran bulan <b>{bulanLabel}</b> tahun <b>{form.tahun}</b> sudah ada.
              Ubah pada menu <b>Edit</b>.
            </div>
          )}

          <form onSubmit={submit} className="space-y-3">
            <div>
              <label className="text-sm">Tanggal</label>
              <input
                type="date"
                value={form.tanggal}
                max={today}
                onChange={(e) => setForm((p) => ({ ...p, tanggal: e.target.value }))}
                className="w-full border border-gray-200 p-2 rounded"
              />
            </div>

            <div className="grid grid-cols-2 gap-2">
              <div>
                <label className="text-sm">Bulan</label>
                <select
                  value={form.bulan}
                  onChange={(e) => setForm((p) => ({ ...p, bulan: e.target.value }))}
                  className="w-full border border-gray-200 p-2 rounded"
                >
                  {monthOptions.map((m) => (
                    <option key={m} value={m}>
                      {bulanNama[m]}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="text-sm">Tahun</label>
                <input
                  type="number"
                  value={form.tahun}
                  min={currentYear}
                  onChange={(e) => setForm((p) => ({ ...p, tahun: e.target.value }))}
                  className="w-full border border-gray-200 p-2 rounded"
                />
              </div>
            </div>

            <div>
              <label className="text-sm">Iuran</label>
              <input
                type="text"
                value={formatRupiah(form.nominal)}
                onChange={handleNominal}
                className="w-full border border-gray-200 p-2 rounded"
                placeholder=""
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
                disabled={disableSave}
                className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
              >
                {checkingDup ? "Cek..." : "Simpan"}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
