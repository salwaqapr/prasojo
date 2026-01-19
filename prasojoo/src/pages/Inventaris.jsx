import { useEffect, useMemo, useState, useRef } from "react";
import InventarisTable from "../components/InventarisTable";
import InventarisModal from "../components/InventarisModal";
import {
  getInventaris,
  createInventaris,
  updateInventaris,
  deleteInventaris,
} from "../services/inventarisApi";

const bulanNama = [
  "Januari","Februari","Maret","April","Mei","Juni",
  "Juli","Agustus","September","Oktober","November","Desember"
];

export default function Inventaris({ userNama }) {
  const [inventaris, setInventaris] = useState([]);
  const [search, setSearch] = useState("");
  const [bulan, setBulan] = useState("");
  const [tahun, setTahun] = useState("");
  const [sortBy, setSortBy] = useState("id");
  const [sortDir, setSortDir] = useState("desc");

  const [modalOpen, setModalOpen] = useState(false);
  const [editing, setEditing] = useState(null);

  /* =====================
     AKSES (FINAL)
     hanya "admin" & "sie inventaris"
  ===================== */
  const normalizedNama = String(userNama ?? "").trim().toLowerCase();
  const canManageInventaris =
    normalizedNama === "admin" || normalizedNama === "sie inventaris";

  const loadData = async () => {
    const res = await getInventaris();
    setInventaris(res);
  };

  useEffect(() => {
    loadData();
  }, []);

  const filteredData = useMemo(() => {
    return inventaris.filter((k) => {
      const cocokSearch =
        search === "" ||
        (k.subjek ?? "").toLowerCase().includes(search.toLowerCase());

      const tgl = new Date(k.tanggal);

      const cocokBulan = bulan === "" || tgl.getMonth() + 1 === Number(bulan);
      const cocokTahun = tahun === "" || tgl.getFullYear() === Number(tahun);

      return cocokSearch && cocokBulan && cocokTahun;
    });
  }, [inventaris, search, bulan, tahun]);

  const handleToggleSortTanggal = () => {
    if (sortBy !== "tanggal") {
      setSortBy("tanggal");
      setSortDir("asc");
    } else {
      setSortDir((prev) => (prev === "asc" ? "desc" : "asc"));
    }
  };

  const sortedData = useMemo(() => {
    const copy = [...filteredData];

    copy.sort((a, b) => {
      const valA = sortBy === "id" ? a.id : new Date(a.tanggal);
      const valB = sortBy === "id" ? b.id : new Date(b.tanggal);

      return sortDir === "asc" ? valA - valB : valB - valA;
    });

    return copy;
  }, [filteredData, sortBy, sortDir]);

  const dropdownBulanData = useMemo(() => {
    return inventaris.filter((k) => {
      const cocokSearch =
        search === "" ||
        (k.subjek ?? "").toLowerCase().includes(search.toLowerCase());

      const tgl = new Date(k.tanggal);
      const cocokTahun = tahun === "" || tgl.getFullYear() === Number(tahun);

      return cocokSearch && cocokTahun;
    });
  }, [inventaris, search, tahun]);

  const months = useMemo(() => {
    const set = new Set();
    dropdownBulanData.forEach((k) => {
      set.add(new Date(k.tanggal).getMonth() + 1);
    });
    return [...set].sort((a, b) => a - b);
  }, [dropdownBulanData]);

  const dropdownTahunData = useMemo(() => {
    return inventaris.filter((k) => {
      const cocokSearch =
        search === "" ||
        (k.subjek ?? "").toLowerCase().includes(search.toLowerCase());

      const tgl = new Date(k.tanggal);
      const cocokBulan = bulan === "" || tgl.getMonth() + 1 === Number(bulan);

      return cocokSearch && cocokBulan;
    });
  }, [inventaris, search, bulan]);

  const years = useMemo(() => {
    const set = new Set();
    dropdownTahunData.forEach((k) => {
      set.add(new Date(k.tanggal).getFullYear());
    });
    return [...set].sort((a, b) => a - b);
  }, [dropdownTahunData]);

  const [notif, setNotif] = useState("");
  const notifTimer = useRef(null);

  const [hapusId, setHapusId] = useState(null);
  const [showHapusModal, setShowHapusModal] = useState(false);

  const showNotif = (message) => {
    setNotif(message);
    notifTimer.current = setTimeout(() => {
      closeNotif();
    }, 2500);
  };

  const closeNotif = () => {
    setNotif("");
    if (notifTimer.current) {
      clearTimeout(notifTimer.current);
      notifTimer.current = null;
    }
  };

  useEffect(() => {
    return () => {
      if (notifTimer.current) clearTimeout(notifTimer.current);
    };
  }, []);

  const handleSave = async (data) => {
    if (editing) {
      await updateInventaris(editing.id, data);
      showNotif("Data berhasil diperbarui");
    } else {
      await createInventaris(data);
      showNotif("Data berhasil ditambahkan");

      setSortBy("id");
      setSortDir("desc");

      if (search || bulan || tahun) {
        setSearch("");
        setBulan("");
        setTahun("");
        await loadData();
      } else {
        setInventaris((prev) => {
          const newData = [...prev, data];
          newData.sort((a, b) => b.id - a.id);
          return newData;
        });
      }
    }

    setEditing(null);
    setModalOpen(false);
    loadData();
  };

  const handleDeleteClick = (id) => {
    setHapusId(id);
    setShowHapusModal(true);
  };

  const handleDeleteConfirm = async () => {
    await deleteInventaris(hapusId);
    setShowHapusModal(false);
    setHapusId(null);

    showNotif("Data berhasil dihapus");
    loadData();
  };

  const { totalPemasukan, totalPengeluaran, saldoAkhir } = useMemo(() => {
    let masuk = 0;
    let keluar = 0;

    filteredData.forEach((k) => {
      masuk += Number(k.pemasukan ?? 0);
      keluar += Number(k.pengeluaran ?? 0);
    });

    return {
      totalPemasukan: masuk,
      totalPengeluaran: keluar,
      saldoAkhir: masuk - keluar,
    };
  }, [filteredData]);

  const downloadPdf = () => {
    const params = new URLSearchParams();

    if (search) params.append("search", search);
    if (bulan !== "") params.append("bulan", bulan);
    if (tahun !== "") params.append("tahun", tahun);

    const baseUrl = "http://127.0.0.1:8000/api/inventaris/pdf";
    const url = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;

    window.location.href = url;
  };

  return (
    <div className="space-y-4 bg-gray-100 min-h-screen">
      <div className="w-full max-w-full overflow-x-auto space-y-3">
        <div className="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
          {/* FILTER */}
          <div className="flex flex-col gap-2 sm:grid sm:grid-cols-3 lg:flex lg:flex-row lg:items-center lg:gap-2 w-full">
            <div className="relative w-full lg:w-60">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                className="w-4 h-4 text-gray-400 absolute left-2 top-1/2 -translate-y-1/2 pointer-events-none"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth="2"
                  d="M21 21l-4.35-4.35m1.6-4.65a7 7 0 11-14 0 7 7 0 0114 0z"
                />
              </svg>

              <input
                type="search"
                placeholder="Cari Subjek"
                className="border border-gray-200 bg-white px-2 py-1 pl-8 rounded-lg text-sm w-full
                           focus:outline-none focus:ring-2 focus:ring-gray-300"
                value={search}
                onChange={(e) => setSearch(e.target.value)}
              />
            </div>

            <select
              className="border border-gray-300 bg-white px-2 py-1 rounded text-sm w-full lg:w-40"
              value={bulan}
              onChange={(e) => setBulan(e.target.value)}
            >
              <option value="">Semua Bulan</option>
              {months.map((m) => (
                <option key={m} value={m}>
                  {bulanNama[m - 1]}
                </option>
              ))}
            </select>

            <select
              className="border border-gray-300 bg-white px-2 py-1 rounded text-sm w-full lg:w-40"
              value={tahun}
              onChange={(e) => setTahun(e.target.value)}
            >
              <option value="">Semua Tahun</option>
              {years.map((y) => (
                <option key={y} value={y}>
                  {y}
                </option>
              ))}
            </select>
          </div>

          {/* ✅ tombol hanya admin / sie inventaris */}
          {canManageInventaris && (
            <div className="flex flex-col gap-2 sm:flex-row sm:justify-end lg:items-center">
              <button
                onClick={downloadPdf}
                className="inline-flex items-center justify-center gap-2
                          bg-blue-700 text-white px-2 py-1 rounded
                          hover:bg-blue-800 transition
                          w-full sm:w-auto"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  className="w-4 h-4"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0 0l-4-4m4 4l4-4"
                  />
                </svg>
                <span>Unduh</span>
              </button>

              <button
                onClick={() => setModalOpen(true)}
                className="inline-flex items-center justify-center gap-2
                           bg-green-700 text-white px-2 py-1 rounded
                           hover:bg-green-800 transition
                           w-full sm:w-auto"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  className="w-4 h-4"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth="2"
                    d="M12 4v16m8-8H4"
                  />
                </svg>
                <span>Tambah</span>
              </button>
            </div>
          )}
        </div>
      </div>

      {/* TABLE */}
      <InventarisTable
        data={sortedData}
        sortBy={sortBy}
        sortDir={sortDir}
        onToggleSortTanggal={handleToggleSortTanggal}
        onEdit={setEditing}
        onDelete={handleDeleteClick}
        totalPemasukan={totalPemasukan}
        totalPengeluaran={totalPengeluaran}
        saldoAkhir={saldoAkhir}
        canManage={canManageInventaris}
      />

      {(modalOpen || editing) && (
        <InventarisModal
          data={editing}
          onClose={() => {
            setModalOpen(false);
            setEditing(null);
          }}
          onSave={handleSave}
        />
      )}

      {showHapusModal && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
          <div className="bg-white p-6 rounded-lg shadow-xl w-80 text-center">
            <h2 className="text-lg font-bold mb-4">Hapus Data?</h2>
            <p className="mb-4">Apakah Anda yakin menghapus data ini?</p>

            <div className="flex justify-center gap-3">
              <button
                onClick={handleDeleteConfirm}
                className="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
              >
                Ya
              </button>

              <button
                onClick={() => setShowHapusModal(false)}
                className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
              >
                Tidak
              </button>
            </div>
          </div>
        </div>
      )}

      {notif && (
        <div
          className="fixed inset-0 bg-black/50 flex justify-center items-center z-50"
          onClick={closeNotif}
        >
          <div
            className="bg-white p-6 rounded-lg shadow-xl w-80 text-center"
            onClick={(e) => e.stopPropagation()}
          >
            <h2 className="text-lg font-bold mb-2 text-green-700">Berhasil</h2>
            <p className="text-gray-700">{notif}</p>
          </div>
        </div>
      )}
    </div>
  );
}
