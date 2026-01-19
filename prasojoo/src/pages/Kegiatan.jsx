import { useEffect, useMemo, useRef, useState } from "react";
import KegiatanTable from "../components/KegiatanTable";
import KegiatanModal from "../components/KegiatanModal";
import { getKegiatan, createKegiatan, updateKegiatan, deleteKegiatan } from "../services/kegiatanApi";

const bulanNama = [
  "Januari","Februari","Maret","April","Mei","Juni",
  "Juli","Agustus","September","Oktober","November","Desember"
];

export default function Kegiatan() {
  const [kegiatan, setKegiatan] = useState([]);
  const [search, setSearch] = useState("");
  const [bulan, setBulan] = useState("");
  const [tahun, setTahun] = useState("");
  const [sortBy, setSortBy] = useState("id");
  const [sortDir, setSortDir] = useState("desc");

  const [modalOpen, setModalOpen] = useState(false);
  const [editing, setEditing] = useState(null);

  const [notif, setNotif] = useState("");
  const notifTimer = useRef(null);

  const [hapusId, setHapusId] = useState(null);
  const [showHapusModal, setShowHapusModal] = useState(false);

  const loadData = async () => {
    const res = await getKegiatan();
    setKegiatan(res);
  };

  useEffect(() => {
    loadData();
  }, []);

  const filteredData = useMemo(() => {
    return kegiatan.filter((k) => {
      const cocokSearch =
        search === "" ||
        k.judul.toLowerCase().includes(search.toLowerCase()) ||
        k.deskripsi.toLowerCase().includes(search.toLowerCase());

      const tgl = new Date(k.tanggal);
      const cocokBulan = bulan === "" || tgl.getMonth() + 1 === Number(bulan);
      const cocokTahun = tahun === "" || tgl.getFullYear() === Number(tahun);

      return cocokSearch && cocokBulan && cocokTahun;
    });
  }, [kegiatan, search, bulan, tahun]);

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
      const valA = sortBy === "id" ? a.id : new Date(a.tanggal).getTime();
      const valB = sortBy === "id" ? b.id : new Date(b.tanggal).getTime();
      return sortDir === "asc" ? valA - valB : valB - valA;
    });
    return copy;
  }, [filteredData, sortBy, sortDir]);

  const dropdownBulanData = useMemo(() => {
    return kegiatan.filter((k) => {
      const cocokSearch =
        search === "" ||
        k.judul.toLowerCase().includes(search.toLowerCase()) ||
        k.deskripsi.toLowerCase().includes(search.toLowerCase());

      const tgl = new Date(k.tanggal);
      const cocokTahun = tahun === "" || tgl.getFullYear() === Number(tahun);

      return cocokSearch && cocokTahun;
    });
  }, [kegiatan, search, tahun]);

  const months = useMemo(() => {
    const set = new Set();
    dropdownBulanData.forEach((k) => set.add(new Date(k.tanggal).getMonth() + 1));
    return [...set].sort((a, b) => a - b);
  }, [dropdownBulanData]);

  const dropdownTahunData = useMemo(() => {
    return kegiatan.filter((k) => {
      const cocokSearch =
        search === "" ||
        k.judul.toLowerCase().includes(search.toLowerCase()) ||
        k.deskripsi.toLowerCase().includes(search.toLowerCase());

      const tgl = new Date(k.tanggal);
      const cocokBulan = bulan === "" || tgl.getMonth() + 1 === Number(bulan);

      return cocokSearch && cocokBulan;
    });
  }, [kegiatan, search, bulan]);

  const years = useMemo(() => {
    const set = new Set();
    dropdownTahunData.forEach((k) => set.add(new Date(k.tanggal).getFullYear()));
    return [...set].sort((a, b) => a - b);
  }, [dropdownTahunData]);

  const showNotif = (message) => {
    setNotif(message);
    if (notifTimer.current) clearTimeout(notifTimer.current);
    notifTimer.current = setTimeout(() => setNotif(""), 2500);
  };

  useEffect(() => {
    return () => {
      if (notifTimer.current) clearTimeout(notifTimer.current);
    };
  }, []);

  const handleSave = async (formData, id) => {
    try {
      if (id) {
        await updateKegiatan(id, formData);
        showNotif("Data berhasil diperbarui");
      } else {
        await createKegiatan(formData);
        showNotif("Data berhasil ditambahkan");

        // reset sort & filter seperti punyamu
        setSortBy("id");
        setSortDir("desc");
        setSearch("");
        setBulan("");
        setTahun("");
      }

      setEditing(null);
      setModalOpen(false);
      await loadData();
    } catch (err) {
      console.error(err);

      const msg =
        err?.response?.data?.message ||
        (err?.response?.data?.errors
          ? Object.values(err.response.data.errors).flat().join("\n")
          : "Gagal menyimpan data");

      alert(msg);
    }
  };

  const handleDeleteClick = (id) => {
    setHapusId(id);
    setShowHapusModal(true);
  };

  const handleDeleteConfirm = async () => {
    await deleteKegiatan(hapusId);
    setShowHapusModal(false);
    setHapusId(null);
    showNotif("Data berhasil dihapus");
    await loadData();
  };

  return (
    <div className="space-y-4 bg-gray-100 min-h-screen">
      <div className="w-full max-w-full overflow-x-auto space-y-3">
        <div className="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
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
                placeholder="Cari Judul atau Deskripsi"
                className="border border-gray-200 bg-white px-2 py-1 pl-8 rounded-lg text-sm w-full focus:outline-none focus:ring-2 focus:ring-gray-300"
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

          <div className="flex flex-col gap-2 sm:flex-row sm:justify-end lg:items-center">
            <button
              onClick={() => setModalOpen(true)}
              className="inline-flex items-center justify-center gap-2 bg-green-700 text-white px-2 py-1 rounded hover:bg-green-800 transition w-full sm:w-auto"
            >
              <svg xmlns="http://www.w3.org/2000/svg" className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
              </svg>
              <span>Tambah</span>
            </button>
          </div>
        </div>
      </div>

      <KegiatanTable
        data={sortedData}
        sortBy={sortBy}
        sortDir={sortDir}
        onToggleSort={handleToggleSortTanggal}
        onEdit={setEditing}
        onDelete={handleDeleteClick}
      />

      {(modalOpen || editing) && (
        <KegiatanModal
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
              <button onClick={handleDeleteConfirm} className="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                Ya
              </button>
              <button onClick={() => setShowHapusModal(false)} className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                Tidak
              </button>
            </div>
          </div>
        </div>
      )}

      {notif && (
        <div className="fixed inset-0 bg-black/50 flex justify-center items-center z-50" onClick={() => setNotif("")}>
          <div className="bg-white p-6 rounded-lg shadow-xl w-80 text-center" onClick={(e) => e.stopPropagation()}>
            <h2 className="text-lg font-bold mb-2 text-green-700">Berhasil</h2>
            <p className="text-gray-700">{notif}</p>
          </div>
        </div>
      )}
    </div>
  );
}
