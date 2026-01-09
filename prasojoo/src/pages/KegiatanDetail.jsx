import { useEffect, useState, useRef } from "react";
import Navbar from "../components/Navbar";

const KegiatanDetail = () => {
  const [data, setData] = useState([]);
  const [cardsToShow, setCardsToShow] = useState(1);
  const sliderRef = useRef(null);
  const [cardWidth, setCardWidth] = useState(0);

  // scroll ke atas
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

  // ambil data dari Laravel
  useEffect(() => {
    fetch("http://localhost:8000/api/kegiatan")
      .then((res) => res.json())
      .then((res) => setData(res))
      .catch((err) => console.error(err));
  }, []);

  const handleImageLoad = (e) => {
    const img = e.target;
    const ratio = img.naturalWidth / img.naturalHeight;

    // rasio 3:4 = 0.75
    if (ratio < 0.75) {
      // terlalu tinggi → crop
      img.style.objectFit = "cover";
    } else {
      // lebar / normal → tampilkan utuh
      img.style.objectFit = "contain";
    }
  };

  const formatTanggal = (tanggal) => {
    if (!tanggal) return "";
    const [y, m, d] = tanggal.split("-");
    return `${d}-${m}-${y}`;
  };


  return (
    <div className="mx-auto px-4 w-full min-h-screen bg-white">
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

        <p className="text-gray-500 text-xl max-w-2xl text-center mb-10 mx-auto">
          Kegiatan yang telah Dilakukan oleh Prasojo
        </p>

        {/* GRID KEGIATAN */}
        <div
          ref={sliderRef}
          className="
            mt-4 grid gap-6
            grid-cols-1
            sm:grid-cols-2
            md:grid-cols-3
            lg:grid-cols-4
          "
        >
          {data.map((item) => (
            <div
              key={item.id}
              className="
                bg-white rounded-lg p-3 sm:p-4
                transition-all duration-300 ease-in-out
                hover:-translate-y-3
                shadow-[0_0_20px_rgba(0,0,0,0.12)]
                hover:shadow-[0_0_40px_rgba(0,0,0,0.25)]
              "
            >
              <div
                className="w-full bg-white rounded-lg overflow-hidden"
                style={{ aspectRatio: "3 / 4" }}
              >
                <img
                  src={`http://localhost:8000/storage/${item.foto}`}
                  alt={item.judul}
                  onLoad={handleImageLoad}
                  className="w-full h-full object-cover"
                />

              </div>

              <div className="px-1.5 mt-3">
                <h2 className="text-lg sm:text-xl font-semibold text-gray-800">
                  {item.judul}
                </h2>

                <p className="text-gray-500 text-sm mt-1">
                  {formatTanggal(item.tanggal)}
                  {item.lokasi && (
                    <>
                      <span className="px-1">|</span>
                      {item.lokasi}
                    </>
                  )}
                </p>

                <p className="text-gray-600 text-sm sm:text-base mt-3 leading-relaxed text-justify">
                  {item.deskripsi}
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
