import React from 'react'
import { assets } from '../assets/assets'
import { useNavigate } from 'react-router-dom';

const Tentang = () => {
  const navigate = useNavigate();

  return (
    <div className="container mx-auto py-4 pt-25 px-6 md:px-20 lg:px-32 my-20 w-full overflow-hidden" id='Tentang'>
      <h1 className='text-2xl sm:text-4xl text-[#111827] font-bold mb-2 text-center'>
        Tentang{' '}
        <span className='underline underline-offset-4 decoration-1 font-light text-[#111827]'>
          Prasojo
        </span>
      </h1>
      <p className='text-gray-500 text-xl max-w-2xl text-center mb-8 mx-auto'>
        Tentang Paguyuban Pemuda Prasojo
      </p>

      <div className='flex flex-col md:flex-row items-center md:items-start md:gap-20'>
        <img src={assets.logo_prasojoo} alt="" className='w-full sm:w-1/2 max-w-lg'/>
        <div className='flex flex-col items-center md:items-start mt-5 text-[#111827]'>
          <div className='grid grid-cols-2 gap-6 md:gap-10 w-full 2xl'>
            <div>
              <div className="flex items-baseline gap-1">
                <p className="text-3xl font-medium text-[#111827]">35</p>
                <span className="text-sm text-[#111827]">Anggota</span>
              </div>
              <p className="text-l font-bold text-[#111827]">Pokja Bagor</p>
            </div>
            <div>
              <div className="flex items-baseline gap-1">
                <p className="text-3xl font-medium text-[#111827]">15</p>
                <span className="text-sm text-[#111827]">Anggota</span>
              </div>
              <p className="text-l font-bold text-[#111827]">Pokja Kamongan</p>
            </div>
            <div>
              <div className="flex items-baseline gap-1">
                <p className="text-3xl font-medium text-[#111827]">14</p>
                <span className="text-sm text-[#111827]">Anggota</span>
              </div>
              <p className="text-l font-bold text-[#111827]">Pokja Juwiring Pasar</p>
            </div>
            <div>
              <div className="flex items-baseline gap-1">
                <p className="text-3xl font-medium text-[#111827]">18</p>
                <span className="text-sm text-[#111827]">Anggota</span>
              </div>
              <p className="text-l font-bold text-[#111827]">Pokja Winong</p>
            </div>
          </div>

          <p className='my-10 max-w-lg text-[#111827] text-justify'>
            Paguyuban Pemuda Prasojo merupakan organisasi kepemudaan
            yang berada di Desa Juwiring. Organisasi ini terdiri dari empat Pokja, yaitu Pokja Bagor,
            Pokja Kamongan, Pokja Juwiring Pasar, dan Pokja Winong. Saat ini Paguyuban Pemuda Prasojo
            memiliki 82 anggota aktif yang bersama-sama bergerak dalam kegiatan sosial, pengembangan potensi pemuda,
            serta program-program yang mendukung kemajuan dan kemandirian desa.
          </p>
        
          <button
            className="bg-[#111827] text-white px-3 py-2 rounded transition hover:text-gray-400"
            onClick={() => navigate('/tentang-detail')}
          >
            Selengkapnya
          </button>
        </div>
      </div>
    </div>
  )
}

export default Tentang
