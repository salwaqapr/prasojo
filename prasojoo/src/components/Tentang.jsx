import React from "react";
import { assets } from "../assets/assets";
import { useNavigate } from "react-router-dom";

const Tentang = () => {
  const navigate = useNavigate();

  return (
    <div
      className="py-16 pt-24 px-6 md:px-20 lg:px-16 w-full overflow-hidden"
      id="Tentang"
    >
      <h1 className="text-3xl sm:text-4xl text-[#111827] font-bold mb-2 text-center">
        Tentang{" "}
        <span className="underline underline-offset-4 decoration-1 font-light text-[#111827]">
          Prasojo
        </span>
      </h1>

      <p className="text-gray-600 text-xl max-w-2xl text-center mb-8 mx-auto">
        Tentang Paguyuban Pemuda Prasojo
      </p>

      {/* ✅ container biar konten benar-benar di tengah */}
      <div className="max-w-6xl mx-auto">
        {/* ✅ sampai 1024 (lg) tetap kolom, baru row di xl */}
        <div className="flex flex-col items-center gap-6 xl:flex-row xl:justify-center xl:items-center xl:gap-12">
          <img
            src={assets.logo_prasojoo}
            alt="Logo Prasojo"
            className="w-full sm:w-1/2 max-w-lg"
          />

          {/* ✅ lebar teks dibatasi biar center & rapi */}
          <div className="w-full max-w-lg text-[#111827] flex flex-col items-center xl:items-start">
            <div className="grid grid-cols-2 gap-6 w-full">
              <div>
                <div className="flex items-baseline gap-1">
                  <p className="text-2xl font-medium">35</p>
                  <span className="text-l">Anggota</span>
                </div>
                <p className="text-l font-bold">Pokja Bagor</p>
              </div>

              <div>
                <div className="flex items-baseline gap-1">
                  <p className="text-2xl font-medium">15</p>
                  <span className="text-l">Anggota</span>
                </div>
                <p className="text-l font-bold">Pokja Kamongan</p>
              </div>

              <div>
                <div className="flex items-baseline gap-1">
                  <p className="text-2xl font-medium">14</p>
                  <span className="text-l">Anggota</span>
                </div>
                <p className="text-l font-bold">Pokja Juwiring Pasar</p>
              </div>

              <div>
                <div className="flex items-baseline gap-1">
                  <p className="text-2xl font-medium">18</p>
                  <span className="text-l">Anggota</span>
                </div>
                <p className="text-l font-bold">Pokja Winong</p>
              </div>
            </div>

            <p className="text-l my-8 w-full text-justify">
              Paguyuban Pemuda Prasojo merupakan organisasi kepemudaan yang berada
              di Desa Juwiring. Organisasi ini terdiri dari empat Pokja, yaitu Pokja
              Bagor, Pokja Kamongan, Pokja Juwiring Pasar, dan Pokja Winong. Saat ini
              Paguyuban Pemuda Prasojo memiliki 82 anggota aktif yang bergerak
              bersama dalam kegiatan sosial, pengembangan potensi pemuda, serta
              program-program yang mendukung kemajuan dan kemandirian desa.
            </p>

            <div className="w-full flex justify-end">
              <button
                className="text-white bg-[#111827] font-bold px-3 py-2 rounded transition hover:bg-[#ffa725] hover:text-[#111827]"
                onClick={() => navigate("/tentang-detail")}
              >
                Selengkapnya
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Tentang;
