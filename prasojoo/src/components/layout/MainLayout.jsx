import React, { useState } from "react";
import Sidebar from "./Sidebar";
import Topbar from "./Topbar";

export default function MainLayout({ children, pageTitle, userName }) {
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const toggleSidebar = () => setSidebarOpen((prev) => !prev);
  const closeSidebar = () => setSidebarOpen(false);

  return (
    <div className="bg-gray-100 min-h-screen relative flex">
      {/* ✅ KIRIM userNama ke Sidebar */}
      <Sidebar
        isOpen={sidebarOpen}
        onClose={closeSidebar}
        userNama={userName}
      />

      {/* Konten utama */}
      <div className="flex-1 flex flex-col lg:ml-60">
        <div className="px-3 sm:px-2 pt-1 sm:pt-1">
          <Topbar
            pageTitle={pageTitle}
            onToggleMenu={toggleSidebar}
            userName={userName}
          />
        </div>

        <main className="flex-1 px-3 sm:px-6 pb-3 sm:pb-4 pt-3 overflow-auto">
          {children}
        </main>
      </div>
    </div>
  );
}
