import React from "react";
import { useLocation } from "react-router-dom";
import { logout } from "../../utils/auth";

const menuIconPublic = "/menu_icon.svg";

export default function Topbar({ pageTitle = "", onToggleMenu, userName = "" }) {
  const location = useLocation();
  const showUserName = location.pathname === "/dashboard";

  const title = showUserName ? `Selamat Datang, ${userName} 👋` : pageTitle;

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
              text-sm sm:text-xl
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
          onClick={logout}
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
      </div>
    </div>
  );
}
