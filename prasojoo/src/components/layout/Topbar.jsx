import React, { useState }  from "react";
import { useLocation } from "react-router-dom";
import { logout } from "../../utils/auth";

const menuIconPublic = "/menu_icon.svg";

export default function Topbar({ pageTitle = "", onToggleMenu, userName = "" }) {
  const location = useLocation();
  const showUserName = location.pathname === "/dashboard";

  const title = showUserName ? `Selamat Datang, ${userName} 👋` : pageTitle;

  const [showLogoutModal, setShowLogoutModal] = useState(false);

  const handleLogoutConfirm = () => {
    setShowLogoutModal(false);
    logout(); // ini akan redirect ke "/"
  };

  return (
    <div className="px-1 py-3">
      <div
        className="
          flex items-center justify-between
          bg-[#111827] text-white
          px-4 sm:px-5 py-3
          rounded-lg
          gap-3
          min-w-0
        "
      >
        {/* KIRI: hamburger + title */}
        <div className="flex items-center gap-3 min-w-0">
          {/* Hamburger (mobile only) */}
          <button
            type="button"
            onClick={onToggleMenu}
            className="p-2 rounded hover:bg-gray-700 lg:hidden flex-shrink-0"
            aria-label="Toggle menu"
          >
            <img
              src={menuIconPublic}
              alt="Menu"
              className="w-5 h-5 select-none"
              onError={(e) => {
                e.currentTarget.src = menuIconPublic;
              }}
            />
          </button>

          {/* Title */}
          <p
            className="
              text-medium sm:text-xl
              font-semibold
              truncate
              leading-tight
              min-w-0
            "
            title={title}
          >
            {title}
          </p>
        </div>

        {/* KANAN: logout */}
        <button
          type="button"
          onClick={() => setShowLogoutModal(true)}
          className="
            bg-red-600 hover:bg-red-700
            text-white
            px-3 sm:px-4 py-2
            rounded-md
            text-xs sm:text-base
            flex-shrink-0
            whitespace-nowrap
          "
        >
          Logout
        </button>

        {showLogoutModal && (
          <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div className="bg-white text-black p-6 rounded-lg shadow-xl w-80 text-center">
              <h2 className="text-lg font-bold mb-4">Log Out</h2>
              <p className="mb-4">Apakah Anda yakin akan Log Out?</p>

              <div className="flex justify-center gap-3">
                <button
                  onClick={handleLogoutConfirm}
                  className="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                >
                  Ya
                </button>

                <button
                  onClick={() => setShowLogoutModal(false)}
                  className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                >
                  Tidak
                </button>
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
