import React, { useEffect } from 'react';
import { assets } from '../assets/assets';
import Navbar from '../components/Navbar';

const TentangDetail = () => {

  // Scroll ke atas ketika halaman dibuka
  useEffect(() => {
    window.scrollTo(0, 0);
  }, []);

  return (
    <div className="relative w-full min-h-screen overflow-hidden bg-white">
      <Navbar />

      {/* Spacer supaya konten tidak tertutup navbar */}
      <div className="mt-10"></div>

      {/* Konten utama */}
      <div className="flex flex-col items-center justify-center mx-auto p-14 md:px-20 lg:px-32 w-full overflow-hidden">
        
        <h1 className="text-2xl sm:text-4xl text-[#111827] font-bold mb-2 text-center">
          Tentang <span className="underline underline-offset-4 decoration-1 font-light">Prasojo</span>
        </h1>

        <p className="text-gray-500 text-xl max-w-2xl text-center mb-8 mx-auto">
          Tentang Paguyuban Pemuda Prasojo
        </p>

        <img
          src={assets.logo_prasojoo}
          alt="Logo Prasojo"
          className="w-full sm:w-1/2 max-w-lg mb-8"
        />

        <div className="text-[#111827] max-w-4xl space-y-6 text-[17px] leading-relaxed text-justify">
          <p>
            Paguyuban Pemuda Prasojo, atau yang akrab disebut Prasojo, merupakan organisasi kepemudaan 
            yang berdiri di Desa Juwiring. Organisasi ini menjadi wadah bagi para pemuda untuk 
            berkontribusi dalam kegiatan sosial, budaya, maupun kemasyarakatan. Saat ini, Prasojo 
            memiliki 82 anggota aktif yang berasal dari empat Pokja, yaitu 35 anggota Pokja Bagor, 
            15 anggota Pokja Kamongan, 14 anggota Pokja Juwiring Pasar, dan 18 anggota Pokja Winong.
          </p>

          <p>
            Sebagai organisasi yang aktif dan dinamis, Prasojo rutin menyelenggarakan berbagai 
            kegiatan. Mulai dari pertemuan rutin, gotong royong, piknik, tirakatan di masing-masing 
            dukuh, menghadiri undangan kegiatan dari desa, serta perayaan HUT RI yang terdiri 
            dari lomba, senam, jalan sehat, dan hiburan. Tidak hanya itu, Prasojo juga kerap 
            mengadakan kegiatan rekreasi bersama seperti lari pagi, berenang, badminton, dan 
            jalan-jalan ke tempat tertentu untuk mempererat kebersamaan.
          </p>

          <p>
            Selain berfokus pada kegiatan sosial dan pemuda, Prasojo juga menyediakan layanan 
            penyewaan inventaris untuk mendukung kebutuhan warga. Berbagai barang seperti lampu, 
            fitting, tlisir, roll kabel, hingga karpet tersedia dan dapat disewa dengan mudah. 
            Penyewaan barang dapat dilakukan dengan menghubungi pengurus prasojo secara langsung.
          </p>

          <p>
            Dengan berbagai aktivitas dan fasilitas tersebut, Prasojo tidak hanya menjadi 
            organisasi pemuda biasa, tetapi juga menjadi sarana untuk memperkuat solidaritas, 
            meningkatkan kepedulian sosial, dan mendukung kegiatan masyarakat di Desa Juwiring.
          </p>
        </div>

      </div>
    </div>
  );
};

export default TentangDetail;