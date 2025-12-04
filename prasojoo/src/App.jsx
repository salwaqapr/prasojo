import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Landing from './pages/Landing';
import Dashboard from './pages/Dashboard';
import Kegiatan from './components/Kegiatan';
import Login from "./pages/Login";
import TentangDetail from './pages/TentangDetail';
import KegiatanDetail from "./pages/KegiatanDetail";

function App() {
  const isLoggedIn = false;

  return (
    <Router>
      <Routes>
        <Route path="/" element={<Landing />} />
        <Route path="/dashboard" element={isLoggedIn ? <Dashboard /> : <Navigate to="/login" />} />
        <Route path="/kegiatan" element={<Kegiatan />} />
        <Route path="/login" element={<Login />} />
        <Route path="/tentang-detail" element={<TentangDetail />} />
        <Route path="/kegiatan-detail" element={<KegiatanDetail />} />
      </Routes>
    </Router>
  );
}

export default App;
