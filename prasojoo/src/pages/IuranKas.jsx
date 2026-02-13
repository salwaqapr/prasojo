import { useEffect, useMemo, useState, useRef } from "react";
import {
  createIuranMember,
  createMemberPayment,
  deleteIuranMember,
  getIuranMembers,
  updateIuranMember,
} from "../services/iuranApi";

import IuranMemberTable from "../components/iuran/IuranMemberTable";
import MemberModal from "../components/iuran/MemberModal";
import IuranAddModal from "../components/iuran/IuranAddModal";
import IuranEditModal from "../components/iuran/IuranEditModal";
import IuranDetailModal from "../components/iuran/IuranDetailModal";

export default function IuranKas({ userNama }) {
  const [members, setMembers] = useState([]);
  const [search, setSearch] = useState("");

  // modal states
  const [memberModalOpen, setMemberModalOpen] = useState(false);
  const [memberEditing, setMemberEditing] = useState(null);

  const [addIuranFor, setAddIuranFor] = useState(null);
  const [editIuranFor, setEditIuranFor] = useState(null);
  const [detailFor, setDetailFor] = useState(null);

  // role
  const normalizedNama = String(userNama ?? "").trim().toLowerCase();
  const canManage = normalizedNama === "admin" || normalizedNama === "bendahara";

  /* =====================
     NOTIF
  ===================== */
  const [notif, setNotif] = useState("");
  const notifTimer = useRef(null);

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
     HAPUS MODAL (CONFIRM)
  ===================== */
  const [hapusMember, setHapusMember] = useState(null);
  const [showHapusModal, setShowHapusModal] = useState(false);

  const loadMembers = async () => {
    const res = await getIuranMembers(search);
    setMembers(res);
  };

  useEffect(() => {
    loadMembers();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  // reload saat search berubah (debounce ringan)
  const timer = useRef(null);
  useEffect(() => {
    if (timer.current) clearTimeout(timer.current);
    timer.current = setTimeout(() => loadMembers(), 250);
    return () => clearTimeout(timer.current);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [search]);

  /* =====================
     CRUD
  ===================== */
  const handleSaveMember = async (payload) => {
    try {
      if (memberEditing) {
        await updateIuranMember(memberEditing.id, payload);
        showNotif("Data anggota berhasil diperbarui");
      } else {
        await createIuranMember(payload);
        showNotif("Data anggota berhasil ditambahkan");
      }

      setMemberModalOpen(false);
      setMemberEditing(null);
      loadMembers();
    } catch (err) {
      alert(err?.message || "Gagal menyimpan anggota");
    }
  };

  // ✅ sekarang bukan confirm() lagi, tapi buka modal konfirmasi
  const handleDeleteMember = (m) => {
    setHapusMember(m);
    setShowHapusModal(true);
  };

  const handleDeleteConfirm = async () => {
    if (!hapusMember) return;

    try {
      await deleteIuranMember(hapusMember.id);
      setShowHapusModal(false);
      setHapusMember(null);

      showNotif("Data anggota berhasil dihapus");
      loadMembers();
    } catch (err) {
      alert(err?.message || "Gagal menghapus anggota");
    }
  };

  const handleSaveIuran = async (member, payload) => {
    try {
      await createMemberPayment(member.id, payload);
      setAddIuranFor(null);
      showNotif("Iuran berhasil disimpan");
    } catch (err) {
      alert(err?.message || "Gagal menyimpan iuran");
    }
  };

  return (
    <div className="space-y-4 bg-gray-100 min-h-screen p-0">
      {/* Header filter + tambah */}
      <div className="space-y-3">
        <div className="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
          {/* search kiri */}
          <div className="relative w-full lg:w-64">
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
              placeholder="Cari Nama"
              className="border border-gray-200 bg-white px-2 py-1 pl-8 rounded-lg text-sm w-full
                         focus:outline-none focus:ring-2 focus:ring-gray-300"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
            />
          </div>

          {/* tombol tambah kanan */}
          {canManage && (
            <div className="flex justify-end">
              <button
                onClick={() => setMemberModalOpen(true)}
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

      {/* tabel anggota */}
      <IuranMemberTable
        data={members}
        canManage={canManage}
        onTambahIuran={(m) => setAddIuranFor(m)}
        onEditIuran={(m) => setEditIuranFor(m)}
        onDetail={(m) => setDetailFor(m)}
        onDeleteMember={handleDeleteMember}
      />

      {/* modal tambah/edit anggota */}
      {memberModalOpen && (
        <MemberModal
          data={memberEditing}
          onClose={() => {
            setMemberModalOpen(false);
            setMemberEditing(null);
          }}
          onSave={handleSaveMember}
        />
      )}

      {/* modal tambah iuran */}
      {addIuranFor && (
        <IuranAddModal
          member={addIuranFor}
          onClose={() => setAddIuranFor(null)}
          onSave={(payload) => handleSaveIuran(addIuranFor, payload)}
        />
      )}

      {/* modal edit iuran */}
      {editIuranFor && (
        <IuranEditModal
          member={editIuranFor}
          onClose={() => setEditIuranFor(null)}
          onUpdated={({ memberId, nama, type }) => {
            // ✅ update nama di tabel tanpa reload
            if (memberId && nama) {
              setMembers((prev) =>
                prev.map((m) => (m.id === memberId ? { ...m, nama } : m))
              );
              setEditIuranFor((prev) => (prev ? { ...prev, nama } : prev));
            }

            // ✅ notif untuk edit iuran/nama (default)
            if (type === "delete") {
              showNotif("Iuran berhasil dihapus");
            } else {
              showNotif("Iuran berhasil diperbarui");
            }
          }}
        />
      )}

      {/* modal detail */}
      {detailFor && (
        <IuranDetailModal member={detailFor} onClose={() => setDetailFor(null)} />
      )}

      {/* ✅ MODAL KONFIRMASI HAPUS (anggota) */}
      {showHapusModal && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
          <div className="bg-white p-6 rounded-lg shadow-xl w-80 text-center">
            <h2 className="text-lg font-bold mb-4">Hapus Data?</h2>
            <p className="mb-4">
              Apakah Anda yakin menghapus data ini?
            </p>

            <div className="flex justify-center gap-3">
              <button
                onClick={handleDeleteConfirm}
                className="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
              >
                Ya
              </button>

              <button
                onClick={() => {
                  setShowHapusModal(false);
                  setHapusMember(null);
                }}
                className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
              >
                Tidak
              </button>
            </div>
          </div>
        </div>
      )}

      {/* NOTIF MODAL */}
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
