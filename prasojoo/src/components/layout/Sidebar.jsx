import React from "react";
import { Link, useLocation } from "react-router-dom";

const logoPublic = "/logo_prasojo.png";

export default function Sidebar({ isOpen, onClose, userNama }) {
  const location = useLocation();

  // ✅ Admin jika userNama === "Admin" (abaikan spasi & kapital)
  const isAdmin =
    typeof userNama === "string" &&
    userNama.trim().toLowerCase() === "admin";

  const isActive = (to) => location.pathname === to;

  return (
    <>
      {/* overlay mobile */}
      <div
        className={`fixed inset-0 z-40 lg:hidden transition-all duration-300
          ${
            isOpen
              ? "pointer-events-auto backdrop-brightness-25"
              : "pointer-events-none backdrop-brightness-100"
          }`}
        onClick={onClose}
      />

      <aside
        className={`fixed left-0 top-0 z-50 h-screen w-60 bg-[#111827] p-6 transform transition-transform duration-300
        lg:translate-x-0 ${isOpen ? "translate-x-0" : "-translate-x-full"}`}
        onClick={(e) => e.stopPropagation()}
      >
        <img
          src={logoPublic}
          alt="Logo"
          className="w-52 mt-3 mb-24 mx-auto select-none"
        />

        <nav className="space-y-6">
          {/* MENU UMUM */}
          <Link
            to="/dashboard"
            onClick={onClose}
            className={`block px-3 py-2 text-xl font-bold rounded ${
              isActive("/dashboard")
                ? "bg-gray-200 text-[#111827]"
                : "text-white hover:bg-gray-600"
            }`}
          >
            🏠 DASHBOARD
          </Link>

          <Link
            to="/kas"
            onClick={onClose}
            className={`block px-3 py-2 text-xl font-bold rounded ${
              isActive("/kas")
                ? "bg-gray-200 text-[#111827]"
                : "text-white hover:bg-gray-600"
            }`}
          >
            💰 KAS
          </Link>

          <Link
            to="/inventaris"
            onClick={onClose}
            className={`block px-3 py-2 text-xl font-bold rounded ${
              isActive("/inventaris")
                ? "bg-gray-200 text-[#111827]"
                : "text-white hover:bg-gray-600"
            }`}
          >
            💸 INVENTARIS
          </Link>

          <Link
            to="/sosial"
            onClick={onClose}
            className={`block px-3 py-2 text-xl font-bold rounded ${
              isActive("/sosial")
                ? "bg-gray-200 text-[#111827]"
                : "text-white hover:bg-gray-600"
            }`}
          >
            🪙 SOSIAL
          </Link>

          {/* MENU ADMIN (muncul hanya jika nama Admin) */}
          {isAdmin && (
            <>
              <Link
                to="/kegiatan"
                onClick={onClose}
                className={`block px-3 py-2 text-xl font-bold rounded ${
                  isActive("/kegiatan")
                    ? "bg-gray-200 text-[#111827]"
                    : "text-white hover:bg-gray-600"
                }`}
              >
                📝 KEGIATAN
              </Link>

              <Link
                to="/hakAkses"
                onClick={onClose}
                className={`block px-3 py-2 text-xl font-bold rounded ${
                  isActive("/hakAkses")
                    ? "bg-gray-200 text-[#111827]"
                    : "text-white hover:bg-gray-600"
                }`}
              >
                🔐 HAK AKSES
              </Link>
            </>
          )}
        </nav>
      </aside>
    </>
  );
}
