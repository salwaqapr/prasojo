import React, { useEffect } from 'react';
import Navbar from './Navbar'

const Beranda = () => {

  useEffect(() => {
      window.scrollTo(0, 0);
    }, []);
  
  return (
    <div className='min-h-screen mb-4 bg-cover bg-center flex items-center w-full overflow-hidden'
    style= {{ backgroundColor: '#111827' }} id='Header'>
    {/* style={{backgroundImage: "url('/header.jpg')"}} id='Header'> */ }
      <Navbar/>
      <div className='container text-center mx-auto py-4 px-6 md:px-20 lg:px-32 text-white'>
        <p className="text-6xl sm:text-6xl font-semibold">
          Selamat Datang
        </p>

        <p className="text-3xl sm:text-3xl font-semibold mt-5">
          di Website Paguyuban Pemuda Prasojo
        </p>

        <div className='space-x-6 mt-10'>
            <a href="#Tentang" className='border border-white px-8 py-3 rounded transition hover:bg-white hover:text-[#111827]'>Tentang</a>
            <a href="#Kegiatan" className='border border-white px-8 py-3 rounded transition hover:bg-white hover:text-[#111827]'>Kegiatan</a>
        </div>
      </div>
    </div>
  )
}

export default Beranda
