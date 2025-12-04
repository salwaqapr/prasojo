import React from 'react';
import Beranda from '../components/Beranda';
import Tentang from '../components/Tentang';
import Kegiatan from '../components/Kegiatan';
import Footer from '../components/Footer';

const Landing = () => (
  <div className="w-full overflow-hidden">
    <Beranda />
    <Tentang />
    <Kegiatan />
    <Footer />
  </div>
);

export default Landing;
