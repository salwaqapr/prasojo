import React, { useEffect, useState, useRef } from "react";
import { kegiatanData } from "../assets/assets";
import Navbar from "../components/Navbar";

const KegiatanDetail = () => {
  const [cardsToShow, setCardsToShow] = useState(1);
  const sliderRef = useRef(null);
  const [cardWidth, setCardWidth] = useState(0);

  // Scroll ke atas saat halaman dibuka
  useEffect(() => {
    window.scrollTo(0, 0);
  }, []);

  // Menentukan jumlah card berdasarkan lebar layar
  useEffect(() => {
    const updateCardsToShow = () => {
      if (window.innerWidth >= 1280) {
        setCardsToShow(4); 
      } else if (window.innerWidth >= 1024) {
        setCardsToShow(3);
      } else {
        setCardsToShow(2);
      }
    };

    updateCardsToShow();
    window.addEventListener("resize", updateCardsToShow);
    return () => window.removeEventListener("resize", updateCardsToShow);
  }, []);

  // Hitung lebar kartu secara dinamis
  useEffect(() => {
    const updateCardWidth = () => {
      if (sliderRef.current) {
        const containerWidth = sliderRef.current.offsetWidth;
        const gap = 32;
        const dynamicWidth =
          (containerWidth - gap * (cardsToShow - 1)) / cardsToShow;
        setCardWidth(dynamicWidth);
      }
    };

    updateCardWidth();
    window.addEventListener("resize", updateCardWidth);
    return () => window.removeEventListener("resize", updateCardWidth);
  }, [cardsToShow]);

  return (
    <div className="container mx-auto w-full min-h-screen overflow-hidden bg-white">
      <Navbar />

      <div className="mt-10"></div>

      <div
        ref={sliderRef}
        className="flex flex-col items-center justify-center mx-auto p-14 md:px-20 lg:px-32 w-full overflow-hidden"
      >
        <h1 className="text-2xl sm:text-4xl text-[#111827] font-bold mb-2 text-center">
          Kegiatan{" "}
          <span className="underline underline-offset-4 decoration-1 font-light">
            Prasojo
          </span>
        </h1>

        <p className="text-gray-500 text-xl max-w-2xl text-center mb-8 mx-auto">
          Kegiatan yang telah Dilakukan oleh Prasojo
        </p>

        {/* GRID KEGIATAN */}
        <div className="grid gap-10"
             style={{
               gridTemplateColumns: `repeat(${cardsToShow}, minmax(0, 1fr))`
             }}>
          {kegiatanData.map((kegiatan, index) => (
            <div
              key={index}
              className="bg-white rounded-lg shadow hover:shadow-lg transition p-4"
              style={{ width: "100%" }}
            >
              {/* ukuran foto tetap */}
              <div className="w-full h-[320px] bg-gray-85 rounded-lg flex items-center justify-center overflow-hidden">
                <img src={kegiatan.image} 
                alt={kegiatan.judul}
                className="max-h-full max-w-full object-contain" />
              </div>


              <div className="px-1.5">
                <h2 className="text-xl font-semibold text-gray-800">
                  {kegiatan.judul}
                </h2>

                <p className="text-gray-500 text-sm mt-1">
                  {kegiatan.tanggal} <span className="px-1">|</span>{" "}
                  {kegiatan.lokasi}
                </p>

                <p className="text-gray-600 text-sm mt-3 leading-relaxed text-justify">
                  {kegiatan.deskripsi}
                </p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default KegiatanDetail;
