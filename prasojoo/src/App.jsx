import React, { useEffect, useMemo, useState } from "react";
import { Routes, Route, Navigate } from "react-router-dom";

import Landing from "./pages/Landing";
import Dashboard from "./pages/Dashboard";
import Login from "./pages/Login";
import TentangDetail from "./pages/TentangDetail";
import KegiatanDetail from "./pages/KegiatanDetail";
import MainLayout from "./components/layout/MainLayout";
import Kas from "./pages/Kas";
import Inventaris from "./pages/Inventaris";
import Sosial from "./pages/Sosial";
import Kegiatann from "./pages/Kegiatan";
import HakAkses from "./pages/HakAkses";

function getLocalUser() {
  try {
    const raw = localStorage.getItem("user");
    return raw ? JSON.parse(raw) : null;
  } catch {
    return null;
  }
}

export default function App() {
  const [checking, setChecking] = useState(true);
  const [user, setUser] = useState(null);

  const isLoggedIn = useMemo(() => !!user, [user]);
  const userName = useMemo(() => user?.nama || user?.username || "User", [user]);

  useEffect(() => {
    // initial load
    setUser(getLocalUser());
    setChecking(false);

    // kalau berubah dari tab lain
    const onStorage = (e) => {
      if (e.key === "user") setUser(getLocalUser());
    };

    // kalau berubah dari tab yang sama (kita trigger manual dari Login/Logout)
    const onAuthChanged = () => {
      setUser(getLocalUser());
    };

    window.addEventListener("storage", onStorage);
    window.addEventListener("auth:changed", onAuthChanged);

    return () => {
      window.removeEventListener("storage", onStorage);
      window.removeEventListener("auth:changed", onAuthChanged);
    };
  }, []);

  if (checking) return <div style={{ padding: 20 }}>Loading...</div>;

  const Protected = ({ pageTitle, children }) => {
    if (!isLoggedIn) return <Navigate to="/" replace />;
    return (
      <MainLayout pageTitle={pageTitle} userName={userName}>
        {children}
      </MainLayout>
    );
  };

  return (
    <Routes>
      {/* Halaman tanpa layout */}
      <Route path="/" element={<Landing />} />
      <Route
        path="/login"
        element={isLoggedIn ? <Navigate to="/dashboard" replace /> : <Login />}
      />
      <Route path="/tentang-detail" element={<TentangDetail />} />
      <Route path="/kegiatan-detail" element={<KegiatanDetail />} />

      {/* Halaman dengan MainLayout (protected) */}
      <Route path="/dashboard"element={<Protected pageTitle="Dashboard"><Dashboard /></Protected>}/>

      <Route path="/kas" element={<Protected pageTitle="Kas"><Kas userNama={user?.nama} /></Protected>}/>

      <Route path="/inventaris" element={<Protected pageTitle="Inventaris"><Inventaris userNama={user?.nama} /></Protected>}/>

      <Route path="/sosial" element={<Protected pageTitle="Sosial"><Sosial userNama={user?.nama} /></Protected>}/>

      <Route path="/kegiatan" element={<Protected pageTitle="Kegiatan"><Kegiatann /></Protected>}/>

      <Route path="/hakAkses" element={<Protected pageTitle="Hak Akses"><HakAkses /></Protected>}/>
      
      <Route path="*" element={<div style={{ padding: 20 }}>404</div>} />
    </Routes>
  );
}
