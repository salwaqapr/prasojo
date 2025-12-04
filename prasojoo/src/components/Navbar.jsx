import React, { useState, useEffect } from 'react';
import { assets } from '../assets/assets';
import { Link } from "react-router-dom";
import { useLocation } from "react-router-dom";

const Navbar = () => {
  const [showMobileMenu, setShowMobileMenu] = useState(false);
  const location = useLocation();

  // Active class: underline + offset + semi-bold
  const isActive = (path) =>
    location.pathname === path
      ? "text-white font-semibold underline underline-offset-8 decoration-2"
      : "text-white/80 hover:text-white";

  // Lock scroll saat mobile menu terbuka
  useEffect(() => {
    document.body.style.overflow = showMobileMenu ? 'hidden' : 'auto';
    return () => { document.body.style.overflow = 'auto'; };
  }, [showMobileMenu]);

  return (
    <div>
      {/* Navbar */}
      <div className="fixed top-0 left-0 w-full z-50 bg-[#111827]">
        <div className="container mx-auto flex justify-between items-center py-4 px-6 md:px-20 lg:px-32">
          
          {/* Logo */}
          <Link 
            to="/" 
            onClick={() => setShowMobileMenu(false)}
          >
            <img 
              src={assets.logo_prasojo} 
              alt="Logo" 
              className="h-8 cursor-pointer" 
            />
          </Link>


          {/* Desktop Menu */}
          <ul className="hidden md:flex gap-7 text-white">
            <Link to="/" className={isActive("/")}>Beranda</Link>
            <Link to="/tentang-detail" className={isActive("/tentang-detail")}>Tentang</Link>
            <Link to="/kegiatan-detail" className={isActive("/kegiatan-detail")}>Kegiatan</Link>
          </ul>

          {/* Login Button */}
          <a 
            href="http://127.0.0.1:8000/login"
            className="hidden md:block bg-white px-8 py-2 rounded-full text-[#111827]"
          >
            Log In
          </a>

          {/* Mobile Menu Button */}
          <img
            onClick={() => setShowMobileMenu(true)}
            src={assets.menu_icon}
            className="md:hidden w-7 cursor-pointer z-50"
            alt="Menu"
          />
        </div>
      </div>

      {/* Mobile Menu Overlay */}
      <div
        className={`md:hidden fixed top-0 left-0 w-full h-full z-[9999] transition-all ${
          showMobileMenu ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'
        }`}
        style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}
      >
        {/* Panel */}
        <div className="absolute top-0 right-0 w-3/4 h-full bg-white shadow-lg p-6 flex flex-col">
          <div className="flex justify-end cursor-pointer mb-6">
            <img
              onClick={() => setShowMobileMenu(false)}
              src={assets.cross_icon}
              className="w-6"
              alt="Close"
            />
          </div>

          {/* Mobile Menu */}
          <ul className="flex flex-col gap-4 text-lg font-medium text-[#111827]">
            <Link onClick={() => setShowMobileMenu(false)} to="/" className="px-4 py-2">Beranda</Link>
            <Link onClick={() => setShowMobileMenu(false)} to="/tentang-detail" className="px-4 py-2">Tentang</Link>
            <Link onClick={() => setShowMobileMenu(false)} to="/kegiatan-detail" className="px-4 py-2">Kegiatan</Link>
            <a onClick={() => setShowMobileMenu(false)} href="http://127.0.0.1:8000/login" className="px-4 py-2">Login</a>
          </ul>
        </div>
      </div>
    </div>
  );
};

export default Navbar;
