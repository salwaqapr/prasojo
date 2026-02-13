import { useEffect, useState } from "react";
import { getMemberSummary } from "../../services/iuranApi";
import { rupiahView } from "../../utils/rupiah";

const bulanNama = [
  "", "Januari","Februari","Maret","April","Mei","Juni",
  "Juli","Agustus","September","Oktober","November","Desember"
];

export default function IuranDetailModal({ member, onClose }) {
  const [rows, setRows] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    (async () => {
      setLoading(true);
      const res = await getMemberSummary(member.id);
      setRows(res);
      setLoading(false);
    })();
  }, [member]);

  return (
    <div className="fixed inset-0 bg-black/30 z-50 flex items-center justify-center p-3">
      <div className="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden">
        <div className="p-5 max-h-[85dvh] overflow-y-auto">
          <h2 className="text-xl font-bold mb-1">Detail Iuran</h2>
          <p className="text-sm text-gray-600 mb-4">{member?.nama}</p>

          {loading ? (
            <p className="text-sm text-gray-500">Loading...</p>
          ) : rows.length === 0 ? (
            <p className="text-sm text-gray-500">Belum ada data iuran.</p>
          ) : (
            <div className="space-y-2">
              {rows.map((r, i) => (
                <div key={i} className="border rounded-lg p-3 flex justify-between">
                  <span className="text-sm">
                    {bulanNama[r.bulan]} {r.tahun}
                  </span>
                  <span className="text-sm font-semibold">{rupiahView(r.total)}</span>
                </div>
              ))}
            </div>
          )}

          <div className="flex justify-end mt-4">
            <button
              onClick={onClose}
              className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
