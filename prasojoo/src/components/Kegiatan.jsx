import React, { useEffect, useState, useRef } from 'react';
import { assets } from '../assets/assets';
import { useNavigate } from 'react-router-dom';

const Kegiatan = () => {
  const [data, setData] = useState([]);
  const [currentIndex, setCurrentIndex] = useState(0);
  const [cardsToShow, setCardsToShow] = useState(1);
  const sliderRef = useRef(null);
  const [cardWidth, setCardWidth] = useState(0);
  const navigate = useNavigate();

  /* ================= AMBIL DATA DARI LARAVEL ================= */
  useEffect(() => {
    fetch('http://localhost:8000/api/kegiatan')
      .then(res => res.json())
      .then(res => setData(res))
      .catch(err => console.error(err));
  }, []);

  /* ================= RESPONSIVE CARD ================= */
  useEffect(() => {
    const updateCardsToShow = () => {
      if (window.innerWidth >= 1280) {
        setCardsToShow(4); // desktop besar
      } else if (window.innerWidth >= 900) {
        setCardsToShow(3); // laptop
      } else if (window.innerWidth >= 500) {
        setCardsToShow(2); // tablet
      } else {
        setCardsToShow(1); // MOBILE 🔥
      }
    };

    updateCardsToShow();
    window.addEventListener('resize', updateCardsToShow);
    return () => window.removeEventListener('resize', updateCardsToShow);
  }, []);

  /* ================= HITUNG LEBAR CARD ================= */
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
    window.addEventListener('resize', updateCardWidth);
    return () => window.removeEventListener('resize', updateCardWidth);
  }, [cardsToShow]);

  /* ================= NAVIGASI SLIDER ================= */
  const nextKegiatan = () => {
    setCurrentIndex(prev =>
      Math.min(prev + 1, data.length - cardsToShow)
    );
  };

  const prevKegiatan = () => {
    setCurrentIndex(prev => Math.max(prev - 1, 0));
  };

  /* ================= HANDLE FOTO 3:4 ================= */
  const handleImageLoad = (e) => {
    const img = e.target;
    const ratio = img.naturalWidth / img.naturalHeight;

    // rasio card = 3 / 4 = 0.75
    if (ratio < 0.75) {
      // foto terlalu tinggi → crop
      img.style.objectFit = 'cover';
      img.style.objectPosition = 'center';
    } else {
      // foto terlalu lebar → tampilkan utuh, tengah
      img.style.objectFit = 'contain';
      img.style.objectPosition = 'center';
    }
  };

  const formatTanggal = (tanggal) => {
    if (!tanggal) return "";
    const [y, m, d] = tanggal.split("-");
    return `${d}-${m}-${y}`;
  };

  return (
    <div
      id="Kegiatan"
      className="mx-auto py-16 pt-24 px-6 md:px-20 lg:px-40 mb-1 w-full overflow-hidden"
    >
      <h1 className="text-3xl sm:text-4xl text-[#111827] font-bold mb-2 text-center px-2">
        Kegiatan{' '}
        <span className="underline underline-offset-4 decoration-1 font-light">
          Prasojo
        </span>
      </h1>

      <p className="text-gray-500 text-xl max-w-2xl text-center mb-8 mx-auto">
        Kegiatan yang Telah Dilakukan oleh Prasojo
      </p>

      
      {/* ================= NAV BUTTON ================= */}
      <div className="flex justify-between items-center mb-1 w-full px-2">
        <div className="flex items-center gap-2">
          {currentIndex > 0 && (
            <button
              onClick={prevKegiatan}
              className="p-3 bg-gray-200 rounded"
            >
              <img src={assets.left_arrow} alt="Sebelum" />
            </button>
          )}

          {currentIndex < data.length - cardsToShow && (
            <button
              onClick={nextKegiatan}
              className="p-3 bg-gray-200 rounded"
            >
              <img src={assets.right_arrow} alt="Sesudah" />
            </button>
          )}
        </div>

        <button
          className="bg-[#111827] text-white font-bold px-3 py-2 rounded transition hover:bg-[#ffa725] hover:text-[#111827]"
          onClick={() => navigate('/kegiatan-detail')}
        >
          Selengkapnya
        </button>
      </div>

      {/* ================= SLIDER ================= */}
      <div className="overflow-hidden px-2">
      <div
        ref={sliderRef}
        className="flex transition-transform duration-500 ease-in-out gap-8"
        style={{
          transform: `translateX(-${currentIndex * (cardWidth + 32)}px)`
        }}
      >

          {data.map((item) => (
            <div
              key={item.id}
              className="flex-shrink-0 relative bg-white rounded p-4 mt-6 mb-6
                transition-all duration-300 ease-in-out
                hover:-translate-y-3
                shadow-[0_0_25px_rgba(0,0,0,0.15)]
                hover:shadow-[0_0_16px_rgba(0,0,0,0.25)]"
              style={{width: cardWidth}}
            >
              <div className="w-full rounded-lg mb-14 overflow-hidden bg-white">
                
                {/* CONTAINER FOTO 3:4 */}
                <div
                  className="w-full overflow-hidden rounded-lg"
                  style={{ aspectRatio: '3 / 4' }}
                >
                  <img
                    src={`http://localhost:8000/storage/${item.foto}`}
                    alt={item.judul}
                    onLoad={handleImageLoad}
                    className="w-full h-full"
                  />
                </div>

                {/* INFO */}
                <div className="absolute left-0 right-0 bottom-5 flex justify-center">
                  <div className="inline-block bg-white w-3/4 px-4 py-2 shadow-md rounded-lg">
                    <h2 className="text-xl font-semibold text-gray-800">
                      {item.judul}
                    </h2>
                    <p className="text-gray-500 text-sm">
                      {formatTanggal(item.tanggal)}
                    </p>
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
