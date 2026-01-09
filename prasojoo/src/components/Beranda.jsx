import React, { useEffect } from 'react';
import Navbar from './Navbar'

const Beranda = () => {

  useEffect(() => {
      window.scrollTo(0, 0);
    }, []);
  
  return (
    <div
      className="
        min-h-screen flex items-center w-full overflow-hidden
        bg-[#111827] bg-cover
        [background-position:70%_center]
      "
      style={{ backgroundImage: "url('/beranda.png')" }}
      id="Header"
    >
      {/* Konten */}
      <Navbar />
      <div className='relative w-full'>
        <div className='container text-center mx-auto py-4 px-6 md:px-2 lg:px-2 text-white'>
          <div className="absolute left-0 top-1/2 -translate-y-1/2 pl-6 pr-28 text-left">
            <h1
              className="
                text-6xl px-4 sm:text-7xl font-extrabold text-white mb-5 
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
                text-3xl px-4
                sm:text-3xl
                md:text-4xl
                lg:text-4xl
                font-bold mt-4 sm:mt-5 text-white
                drop-shadow-[0_8px_16px_rgba(0,0,0,0.9)]
                [text-shadow:
                  0_3px_6px_rgba(0,0,0,1),
                  0_8px_24px_rgba(0,0,0,0.75),
                  0_16px_40px_rgba(0,0,0,0.6)
                ]
              "
            >
              di Paguyuban Pemuda Prasojo
            </h2>
            <div className="mt-6 sm:mt-8 px-4 flex gap-3 sm:gap-4">
              <a
                href="#Tentang"
                className="
                  bg-white text-[#111827]
                  text-sm sm:text-base
                  font-bold px-4 sm:px-6 py-2
                  rounded transition
                  hover:bg-[#ffa725] hover:text-[#111827]
                  drop-shadow-[0_3px_6px_rgba(0,0,0,0.7)]
                "
              >
                Tentang
              </a>

              <a
                href="#Kegiatan"
                className="
                  bg-white text-[#111827]
                  text-sm sm:text-base
                  font-bold px-4 sm:px-6 py-2
                  rounded transition
                  hover:bg-[#ffa725] hover:text-[#111827]
                  drop-shadow-[0_3px_6px_rgba(0,0,0,0.7)]
                "
              >
                Kegiatan
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Beranda
