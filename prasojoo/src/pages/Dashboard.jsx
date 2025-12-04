import React, { useEffect, useState } from "react";
import axios from "axios";

const Dashboard = () => {
  const [data, setData] = useState(null);

  useEffect(() => {
    axios.get("http://127.0.0.1:8000/api/dashboard").then(res => {
      setData(res.data);
    });
  }, []);

  if (!data) return <div>Loading...</div>;

  return (
    <div className="flex">
      <Sidebar />

      <div className="flex-1 bg-gray-50 min-h-screen">
        <Header />

        <div className="p-8">

          <h2 className="text-3xl font-bold mb-6">Dashboard</h2>

          <h3 className="text-xl font-semibold mb-4">Kas</h3>
          <div className="flex gap-4 mb-8">
            <CardBox title="Pemasukan" income={data.kas.pemasukan} expense={data.kas.pengeluaran} total={data.kas.saldo} />
          </div>

          <h3 className="text-xl font-semibold mb-4">Inventaris</h3>
          <div className="flex gap-4 mb-8">
            <CardBox title="Inventaris" income={data.inventaris.pemasukan} expense={data.inventaris.pengeluaran} total={data.inventaris.saldo} />
          </div>

          <h3 className="text-xl font-semibold mb-4">Sosial</h3>
          <div className="flex gap-4 mb-8">
            <CardBox title="Sosial" income={data.sosial.pemasukan} expense={data.sosial.pengeluaran} total={data.sosial.saldo} />
          </div>

        </div>
      </div>
    </div>
  );
};

export default Dashboard;
