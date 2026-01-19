import { useEffect, useMemo, useState, useRef } from "react";
import HakAksesTable from "../components/HakAksesTable";
import HakAksesModal from "../components/HakAksesModal";
import {
  getHakAkses,
  createHakAkses,
  updateHakAkses,
  deleteHakAkses
} from "../services/hakAksesApi";

export default function HakAkses() {
  const [hakAkses, setHakAkses] = useState([]);
  const [search, setSearch] = useState("");
  const [sortDir, setSortDir] = useState("desc");

  const [modalOpen, setModalOpen] = useState(false);
  const [editing, setEditing] = useState(null);

  /* =====================
     LOAD DATA
  ===================== */
  const loadData = async () => {
    const res = await getHakAkses();
    setHakAkses(res);
  };

  useEffect(() => {
    loadData();
  }, []);

  /* =================
     FILTER DATA
  ================= */
  const filteredData = useMemo(() => {
    return hakAkses.filter(u => {
      return (
        search === "" ||
        u.nama?.toLowerCase().includes(search.toLowerCase()) ||
        u.email?.toLowerCase().includes(search.toLowerCase()) ||
        u.username?.toLowerCase().includes(search.toLowerCase())
      );
    });
  }, [hakAkses, search]);


  /* =====================
     NOTIF
  ===================== */
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

  /* =====================
     CRUD
  ===================== */
  const handleSave = async (data) => {
    if (editing) {
      await updateHakAkses(editing.id, data);
      showNotif("Data berhasil diperbarui");
    } else {
      await createHakAkses(data);
      showNotif("Data berhasil ditambahkan");
    }

    setModalOpen(false);
    setEditing(null);
    loadData();
  };

  const handleDeleteClick = (id) => {
    setHapusId(id);
    setShowHapusModal(true);
  };

  const handleDeleteConfirm = async () => {
    await deleteHakAkses(hapusId);
    setShowHapusModal(false);
    setHapusId(null);
    showNotif("Data berhasil dihapus");
    loadData();
  };


  return (
    <div className="flex flex-col gap-4">

      {/* HEADER */}
      <div className="flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-between">

        {/* SEARCH */}
        <div className="relative w-full sm:w-60">
            <input
                type="search"
                placeholder="Cari Nama, Email, Username"
                className="border border-gray-200 bg-white px-2 py-1 pl-8 rounded-lg text-sm w-full"
                value={search}
                onChange={e => setSearch(e.target.value)}
            />

            <svg
                xmlns="http://www.w3.org/2000/svg"
                className="w-4 h-4 text-gray-400 absolute left-2 top-1/2 -translate-y-1/2"
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

        </div>

        {/* TOMBOL TAMBAH */}
        <button
              onClick={() => setModalOpen(true)}
              className="
                inline-flex items-center justify-center gap-2
                bg-green-700 text-white px-2 py-1 rounded
                hover:bg-green-800 transition
                w-full sm:w-auto
              "
            >
              {/* ICON PLUS */}
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

      {/* TABLE */}
      <HakAksesTable
        data={filteredData}
        sortDir={sortDir}
        onEdit={setEditing}
        onDelete={handleDeleteClick}
      />

      {/* MODAL */}
      {(modalOpen || editing) && (
        <HakAksesModal
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
            <h2 className="text-lg font-bold mb-2 text-green-700">
              Berhasil
            </h2>
            <p className="text-gray-700">
              {notif}
            </p>
          </div>
        </div>
      )}
    </div>
  );
}
