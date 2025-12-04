import React, { useEffect, useState, useRef } from 'react';
import { assets, kegiatanData } from '../assets/assets';
import { useNavigate } from 'react-router-dom';

const Kegiatan = () => {
  const [currentIndex, setCurrentIndex] = useState(0);
  const [cardsToShow, setCardsToShow] = useState(1);
  const sliderRef = useRef(null);
  const [cardWidth, setCardWidth] = useState(0);
  const navigate = useNavigate();

  // Update jumlah kartu berdasarkan layar
  useEffect(() => {
    const updateCardsToShow = () => {
      if (window.innerWidth >= 1280) {
        setCardsToShow(4);        // Desktop besar
      } else if (window.innerWidth >= 1024) {
        setCardsToShow(3);        // Layar sedang (laptop 13–14 inch)
      } else 
        setCardsToShow(2);        // HP besar / tablet
    };

    updateCardsToShow();
    window.addEventListener('resize', updateCardsToShow);
    return () => window.removeEventListener('resize', updateCardsToShow);
  }, []);

  // Hitung lebar kartu secara dinamis
  useEffect(() => {
    const updateCardWidth = () => {
      if (sliderRef.current) {
        const containerWidth = sliderRef.current.offsetWidth;
        const gap = 32;
        const dynamicWidth = (containerWidth - gap * (cardsToShow - 1)) / cardsToShow;
        setCardWidth(dynamicWidth);
      }
    };

    updateCardWidth();
    window.addEventListener('resize', updateCardWidth);
    return () => window.removeEventListener('resize', updateCardWidth);
  }, [cardsToShow]);

  const nextKegiatan = () => {
    setCurrentIndex(prev =>
      Math.min(prev + 1, kegiatanData.length - cardsToShow)
    );
  };

  const prevKegiatan = () => {
    setCurrentIndex(prev => Math.max(prev - 1, 0));
  };

  return (
    <div className="container mx-auto py-4 pt-25 px-6 md:px-20 lg:px-32 my-20 w-full overflow-hidden" id="Kegiatan">
      <h1 className="text-2xl sm:text-4xl text-[#111827] font-bold mb-2 text-center">
        Kegiatan <span className="underline underline-offset-4 decoration-1 font-light">Prasojo</span>
      </h1>
      <p className="text-gray-500 text-xl max-w-2xl text-center mb-8 mx-auto">
        Kegiatan yang telah Dilakukan oleh Prasojo
      </p>

      <div className="flex justify-between items-center mb-8 w-full">
        <div className="flex items-center gap-2">
          {currentIndex > 0 && (
            <button
              onClick={prevKegiatan}
              className="p-3 bg-gray-200 rounded"
            >
              <img src={assets.left_arrow} alt="Sebelum" />
            </button>
          )}

          {currentIndex < kegiatanData.length - cardsToShow && (
            <button
              onClick={nextKegiatan}
              className="p-3 bg-gray-200 rounded"
            >
              <img src={assets.right_arrow} alt="Sesudah" />
            </button>
          )}
        </div>

        <button
          className="bg-[#111827] text-white px-3 py-2 rounded transition hover:text-gray-400"
          onClick={() => navigate('/kegiatan-detail')}
        >
          Selengkapnya
        </button>

      </div>

      <div className="overflow-hidden">
        <div
          ref={sliderRef}
          className="flex transition-transform duration-500 ease-in-out gap-8"
          style={{
            transform: `translateX(-${currentIndex * (cardWidth + 32)}px)`
          }}
        >
          {kegiatanData.map((kegiatan, index) => (
            <div
              key={index}
              className="flex-shrink-0 relative"
              style={{ width: cardWidth }}
            >
              <div className="w-full h-full bg-gray-85 rounded-lg flex items-center justify-center mb-14 overflow-hidden">
                <img
                  src={kegiatan.image}
                  alt={kegiatan.judul}
                  className="w-full h-auto mb-14 rounded-lg"
                />

                <div className="absolute left-0 right-0 bottom-5 flex justify-center">
                  <div className="inline-block bg-white w-3/4 px-4 py-2 shadow-md rounded-lg">
                    <h2 className="text-xl font-semibold text-gray-800">{kegiatan.judul}</h2>
                    <p className="text-gray-500 text-sm">{kegiatan.tanggal}</p>
                    <p className="text-gray-500 text-sm">{kegiatan.lokasi}</p>
                  </div>
                </div>
              </div>
            </div>
          ))}

        </div>
      </div>
    </div>
  );
};

export default Kegiatan;