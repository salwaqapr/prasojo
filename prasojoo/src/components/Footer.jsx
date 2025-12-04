import React from 'react'
import { assets } from '../assets/assets'

const Footer = () => {
  return (
    <div className='pt-10 px-4 md:px-20 lg:px-32 bg-gray-900 w-full overflow-hidden' id='Footer'>
      <div className='container mx-auto flex flex-col md:flex-row justify-between items-start'>
        <div className='w-full md:w-10/15 mb-8 md:mb-0'>
            <img src={assets.logo_prasojo} alt="" className="h-12"/>
            <p className='text-white mt-4'>Paguyuban Pemuda Prasojo merupakan organisasi kepemudaan
              yang berada di Desa Juwiring. Terdiri dari empat Pokja, yaitu Pokja Bagor,
              Pokja Kamongan, Pokja Juwiring Pasar, dan Pokja Winong dengan 82 anggota aktif.</p>
        </div>
        <div className='w-full md:w-4/15'>
        <h3 className='text-white text-2xl font-bold mb-4'>Ikuti Kami</h3>
        <p className='text-white mb-4 max-w-100'>Ikuti kami untuk mengetahui info lebih lengkap</p>
          <div className='flex gap-4 items-center'>
              <a 
                  href="https://www.instagram.com/prasojo_juwiring?igsh=MWVrZHA2ZXZ4Y3JiNQ==" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  className="hover:opacity-70 transition"
              >
                  <img 
                      src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" 
                      alt="Instagram" 
                      className="w-7 h-7 invert"
                  />
              </a>
              <a 
                  href="https://www.tiktok.com/@prasojo.juwiring?_r=1&_t=ZS-91V0L80rHwg" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  className="hover:opacity-70 transition"
              >
                  <img 
                      src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/tiktok.svg" 
                      alt="TikTok" 
                      className="w-7 h-7 invert"
                  />
              </a>
              <a 
                  href="https://youtube.com/@prasojojuwiring?si=VtPepSTRKtrWCQVT" 
                  target="_blank" 
                  rel="noopener noreferrer"
                  className="hover:opacity-70 transition"
              >
                  <img 
                      src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/youtube.svg" 
                      alt="YouTube" 
                      className="w-8 h-8 invert"
                  />
              </a>
          </div>
        </div>
      </div>
      <div className='border-t border-gray-700 py-4 mt-10 text-center text-white'>
        Copyright 2025 © Salwaqapr
      </div>
    </div>
  )
}

export default Footer
