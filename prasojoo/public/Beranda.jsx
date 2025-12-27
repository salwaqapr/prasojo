import React, { useEffect } from 'react';
import Navbar from './Navbar'

const Beranda = () => {

  useEffect(() => {
      window.scrollTo(0, 0);
    }, []);
  
  return (
    <div
      className='min-h-screen flex items-center w-full overflow-hidden bg-[#111827] bg-cover bg-center'
      style={{backgroundImage: "url('/beranda.png')"}}
      id='Header'
    >
      {/* Konten */}
      <Navbar />
      <div className='relative w-full'>
        <div className='container text-center mx-auto py-4 px-6 md:px-2 lg:px-2 text-white'>
          <div className="text-center px-4">
            <h1
              className="
                text-6xl sm:text-7xl font-extrabold text-white
                drop-shadow-[0_4px_8px_rgba(0,0,0,0.75)]
                [text-shadow:
                  0_2px_4px_rgba(0,0,0,0.9),
                  0_6px_20px_rgba(0,0,0,0.6)
                ]
              "
            >
              Selamat Datang
            </h1>

            <h2
              className="
                text-3xl sm:text-4xl font-semibold mt-5 text-white
                drop-shadow-[0_3px_6px_rgba(0,0,0,0.7)]
                [text-shadow:
                  0_1px_3px_rgba(0,0,0,0.9),
                  0_4px_12px_rgba(0,0,0,0.5)
                ]
              "
            >
              di Website Paguyuban Pemuda Prasojo
            </h2>
          </div>

          <div className='space-x-6 mt-10'>
            <a href="#Tentang" className='text-[#111827] bg-white font-bold px-8 py-3 rounded transition hover:bg-blue-600 hover:text-white'>Tentang</a>
            <a href="#Kegiatan" className='text-[#111827] bg-white font-bold px-8 py-3 rounded transition hover:bg-blue-600 hover:text-white'>Kegiatan</a>
          </div>
        </div>
      </div>
    </div>
  )
}

export default Beranda
