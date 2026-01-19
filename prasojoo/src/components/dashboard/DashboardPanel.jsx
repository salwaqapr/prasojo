// DashboardPanel.jsx
import React, { useMemo, useState } from "react";
import {
  ResponsiveContainer,
  LineChart,
  Line,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
} from "recharts";

const bulanNama = [
  "",
  "Januari",
  "Februari",
  "Maret",
  "April",
  "Mei",
  "Juni",
  "Juli",
  "Agustus",
  "September",
  "Oktober",
  "November",
  "Desember",
];

const rupiah = (n) =>
  new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    maximumFractionDigits: 0,
  }).format(n || 0);

const rpCompact = (v) => `Rp ${new Intl.NumberFormat("id-ID").format(v)}`;

function isMonthMode(mode) {
  return mode === "bulanan" || mode === "bulan_tahun";
}

function formatTooltipTitle(row, mode) {
  const x = row?.x;
  const n = Number(x);
  const tahun = row?.tahun;

  if (isMonthMode(mode) && n >= 1 && n <= 12) {
    return `${bulanNama[n]}${tahun ? ` ${tahun}` : ""}`;
  }

  if (mode === "harian") {
    return `Tanggal ${x}${tahun ? ` ${tahun}` : ""}`;
  }

  return `${x ?? ""}`;
}

function Tip({ active, payload, mode }) {
  if (!active || !payload?.length) return null;
  const row = payload[0]?.payload;
  const title = formatTooltipTitle(row, mode);

  return (
    <div className="bg-white border rounded shadow px-3 py-2 text-xs">
      <div className="font-semibold mb-1">{title}</div>
      {payload.map((p) => (
        <div key={p.dataKey} className="flex justify-between gap-6">
          <span>{p.name}</span>
          <span className="font-semibold">{rupiah(p.value)}</span>
        </div>
      ))}
    </div>
  );
}

function ChartBlock({ title, data, lines, height = 170, mode }) {
  const SHIFT_LEFT = 20;

  const chartData = useMemo(() => {
    if (mode !== "bulan_tahun") return data || [];
    return (data || []).map((d) => ({
      ...d,
      xKey: `${d.tahun}-${String(d.x).padStart(2, "0")}`,
    }));
  }, [data, mode]);

  const xDataKey = mode === "bulan_tahun" ? "xKey" : "x";
  const pointCount = chartData?.length || 0;

  const pxPerPoint = isMonthMode(mode) ? 64 : 56;
  const minChartWidth = Math.max(560, pointCount * pxPerPoint);

  return (
    <div className="mb-6">
      <div className="text-sm font-bold text-gray-800 mb-2">{title}</div>

      <div
        className="w-full overflow-x-auto overscroll-x-contain"
        style={{ WebkitOverflowScrolling: "touch", scrollbarGutter: "stable" }}
      >
        <div
          className="relative"
          style={{ width: `max(${minChartWidth}px, 100%)`, height }}
        >
          <ResponsiveContainer width="100%" height="100%">
            <LineChart
              data={chartData}
              margin={{ top: 8, right: 14, left: -SHIFT_LEFT, bottom: 0 }}
            >
              <CartesianGrid strokeDasharray="3 3" />

              <XAxis
                dataKey={xDataKey}
                interval={0}
                height={18}
                tick={{ fontSize: 12 }}
                tickFormatter={(v, idx) => {
                  if (mode === "bulan_tahun") return chartData[idx]?.x ?? "";
                  if (isMonthMode(mode)) return String(v);
                  return String(v);
                }}
              />

              {mode === "bulan_tahun" && (
                <XAxis
                  xAxisId="year"
                  dataKey="tahun"
                  interval={0}
                  height={20}
                  tick={{ fontSize: 12 }}
                  tickFormatter={(value, index) => {
                    const prev = chartData[index - 1]?.tahun;
                    if (!value) return "";
                    if (index === 0 || value !== prev) return value;
                    return "";
                  }}
                  axisLine={false}
                  tickLine={false}
                />
              )}

              <YAxis
                width={100}
                tickLine={false}
                axisLine={false}
                tick={({ x, y, payload }) => (
                  <text
                    x={x}
                    y={y}
                    dy={3}
                    textAnchor="end"
                    fontSize={12}
                    style={{ whiteSpace: "nowrap" }}
                  >
                    {rpCompact(payload.value)}
                  </text>
                )}
              />

              <Tooltip content={<Tip mode={mode} />} />

              {lines.map((ln) => (
                <Line
                  key={ln.key}
                  type="monotone"
                  dataKey={ln.key}
                  name={ln.name}
                  stroke={ln.stroke}
                  strokeWidth={2}
                  dot={false}
                  connectNulls
                />
              ))}
            </LineChart>
          </ResponsiveContainer>
        </div>
      </div>
    </div>
  );
}

export default function DashboardPanel({ title = "KAS", chart }) {
  const [collapsed, setCollapsed] = useState(false);

  const data = useMemo(() => chart?.series ?? [], [chart]);
  const totals = chart?.totals ?? { pemasukan: 0, pengeluaran: 0, saldo: 0 };
  const mode = chart?.mode || "bulan_tahun";

  return (
    <div className="bg-white rounded-xl shadow">
      <div className="flex items-center justify-between px-5 py-3">
        <div className="font-semibold tracking-wide text-sm sm:text-base">
          {title}
        </div>

        <button
          onClick={() => setCollapsed((v) => !v)}
          className="text-gray-700 hover:text-black text-sm"
          title="Collapse"
        >
          {collapsed ? "▲" : "▼"}
        </button>
      </div>

      {!collapsed && (
        // ✅ FIX: padding isi panel juga KONSTAN (biar sama dengan topbar & filter)
        <div className="px-5 pb-5">
          <ChartBlock
            title="Pemasukan"
            data={data}
            lines={[{ key: "pemasukan", name: "Pemasukan", stroke: "#16a34a" }]}
            mode={mode}
          />

          <ChartBlock
            title="Pengeluaran"
            data={data}
            lines={[
              { key: "pengeluaran", name: "Pengeluaran", stroke: "#dc2626" },
            ]}
            mode={mode}
          />

          <ChartBlock
            title="Pemasukan dan Pengeluaran"
            data={data}
            height={210}
            lines={[
              { key: "pemasukan", name: "Pemasukan", stroke: "#16a34a" },
              { key: "pengeluaran", name: "Pengeluaran", stroke: "#dc2626" },
              { key: "saldo", name: "Saldo", stroke: "#2563eb" },
            ]}
            mode={mode}
          />

          <div className="text-sm space-y-2">
            {/* Pemasukan */}
            <div className="flex items-center justify-between gap-4">
                <div className="flex items-center gap-2 min-w-0">
                <span className="w-2 h-2 rounded-full bg-green-600 inline-block flex-shrink-0" />
                <span className="text-green-700 truncate">Pemasukan</span>
                </div>
                <div className="text-green-700 font-medium text-right whitespace-nowrap">
                {rupiah(totals.pemasukan)}
                </div>
            </div>

            {/* Pengeluaran */}
            <div className="flex items-center justify-between gap-4">
                <div className="flex items-center gap-2 min-w-0">
                <span className="w-2 h-2 rounded-full bg-red-600 inline-block flex-shrink-0" />
                <span className="text-red-700 truncate">Pengeluaran</span>
                </div>
                <div className="text-red-700 font-medium text-right whitespace-nowrap">
                {rupiah(totals.pengeluaran)}
                </div>
            </div>

            {/* Saldo */}
            <div className="flex items-center justify-between gap-4">
                <div className="flex items-center gap-2 min-w-0">
                <span className="w-2 h-2 rounded-full bg-blue-600 inline-block flex-shrink-0" />
                <span className="text-blue-700 truncate">Saldo</span>
                </div>
                <div className="text-blue-700 font-medium text-right whitespace-nowrap">
                {rupiah(totals.saldo)}
                </div>
            </div>
            </div>

        </div>
      )}
    </div>
  );
}
