import React, { useEffect, useState } from "react";
import { getKas } from "../api/kas";
import MainLayout from "../layout/MainLayout";

const Kas = () => {
  const [kas, setKas] = useState([]);

  useEffect(() => {
    loadKas();
  }, []);

  const loadKas = async () => {
    const res = await getKas();
    setKas(res.data);
  };

  return (
    <MainLayout>
      <div className="flex justify-between items-center mb-4">
        <h1 className="text-2xl font-bold">Kas</h1>

        <button className="bg-blue-500 text-white px-4 py-2 rounded">
          Tambah
        </button>
      </div>

      <table className="w-full border">
        <thead className="bg-gray-100">
          <tr>
            <th className="border p-2">ID</th>
            <th className="border p-2">Tanggal</th>
            <th className="border p-2">Subjek</th>
            <th className="border p-2">Pemasukan</th>
            <th className="border p-2">Pengeluaran</th>
            <th className="border p-2">Saldo</th>
          </tr>
        </thead>

        <tbody>
          {kas.map((item) => (
            <tr key={item.id}>
              <td className="border p-2">{item.id}</td>
              <td className="border p-2">{item.tanggal}</td>
              <td className="border p-2">{item.subjek}</td>
              <td className="border p-2">{item.pemasukan}</td>
              <td className="border p-2">{item.pengeluaran}</td>
              <td className="border p-2">{item.saldo}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </MainLayout>
  );
};

export default Kas;
