import React, { useEffect, useMemo, useState } from "react";
import DashboardPanel from "../components/dashboard/DashboardPanel";
import { api } from "../lib/api";

export default function Dashboard() {
  const [payload, setPayload] = useState(null);
  const [loading, setLoading] = useState(true);
  const [errorMsg, setErrorMsg] = useState("");

  const [bulan, setBulan] = useState(""); // "" = semua
  const [tahun, setTahun] = useState(""); // "" = semua

  const filters = payload?.filters;
  const sections = payload?.sections;

  const bulanNama = [
    "Januari","Februari","Maret","April","Mei","Juni",
    "Juli","Agustus","September","Oktober","November","Desember",
  ];

  const fetchDashboard = async (b, t) => {
    setLoading(true);
    setErrorMsg("");
    try {
      const res = await api.get("/dashboard", {
        params: {
          bulan: b ? Number(b) : undefined,
          tahun: t ? Number(t) : undefined,
        },
      });
      setPayload(res.data);
    } catch (err) {
      console.error("Dashboard fetch error:", err);
      setPayload(null);

      const status = err?.response?.status;
      const url = (err?.config?.baseURL ?? "") + (err?.config?.url ?? "");
      setErrorMsg(
        status
          ? `Gagal load dashboard (HTTP ${status}) dari ${url}`
          : `Gagal load dashboard. Cek server Laravel & URL API.`
      );
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDashboard("", "");
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const monthsToShow = useMemo(() => {
    const arr = (filters?.availableBulan ?? []).map(Number).filter(Boolean);
    return Array.from(new Set(arr)).sort((a, b) => a - b);
  }, [filters]);

  const yearsToShow = useMemo(() => {
    const arr = (filters?.availableTahun ?? []).map(Number).filter(Boolean);
    return Array.from(new Set(arr)).sort((a, b) => a - b);
  }, [filters]);

  useEffect(() => {
    fetchDashboard(bulan, tahun);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [bulan, tahun]);

  useEffect(() => {
    if (!filters) return;
    if (bulan && !monthsToShow.includes(Number(bulan))) setBulan("");
    if (tahun && !yearsToShow.includes(Number(tahun))) setTahun("");
  }, [filters, monthsToShow, yearsToShow, bulan, tahun]);

  return (
    // ✅ SAMA seperti Topbar: jarak kiri-kanan KONSTAN
    <div className="px-5 py-3">
      {/* FILTER */}
      <div className="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:gap-3 items-stretch mb-6">
        <select
          className="border border-gray-300 bg-white px-3 py-2 rounded text-sm w-full sm:w-56"
          value={bulan}
          onChange={(e) => setBulan(e.target.value)}
          disabled={loading}
        >
          <option value="">Semua Bulan</option>
          {monthsToShow.map((m) => (
            <option key={m} value={m}>
              {bulanNama[m - 1]}
            </option>
          ))}
        </select>

        <select
          className="border border-gray-300 bg-white px-3 py-2 rounded text-sm w-full sm:w-56"
          value={tahun}
          onChange={(e) => setTahun(e.target.value)}
          disabled={loading}
        >
          <option value="">Semua Tahun</option>
          {yearsToShow.map((y) => (
            <option key={y} value={y}>
              {y}
            </option>
          ))}
        </select>
      </div>

      {/* CONTENT */}
      {loading ? (
        <div className="p-4 bg-white rounded-xl shadow">Loading...</div>
      ) : errorMsg ? (
        <div className="p-4 bg-white rounded-xl shadow text-red-600">
          {errorMsg}
        </div>
      ) : !payload ? (
        <div className="p-4 bg-white rounded-xl shadow">Belum ada data.</div>
      ) : (
        <div className="space-y-6">
          <DashboardPanel title="KAS" chart={sections?.kas} />
          <DashboardPanel title="INVENTARIS" chart={sections?.inventaris} />
          <DashboardPanel title="SOSIAL" chart={sections?.sosial} />
        </div>
      )}
    </div>
  );
}
