import React, { forwardRef, useMemo } from "react";

function ucfirst(str = "") {
  if (!str) return "";
  return str.charAt(0).toUpperCase() + str.slice(1);
}

function formatRupiah(n) {
  const num = Number(n ?? 0);
  return "Rp " + new Intl.NumberFormat("id-ID").format(num);
}

function formatTanggalID(dateStr) {
  const d = new Date(dateStr);
  if (Number.isNaN(d.getTime())) return "-";
  const dd = String(d.getDate());
  const mm = String(d.getMonth() + 1).padStart(2, "0");
  const yy = String(d.getFullYear());
  return `${dd}-${mm}-${yy}`;
}

function formatTanggalTTD() {
  const d = new Date();
  const hari = d.getDate();
  const bulan = d.toLocaleString("id-ID", { month: "long" });
  const tahun = d.getFullYear();
  return `${hari} ${bulan} ${tahun}`;
}

const SosialPdfTemplate = forwardRef(function SosialPdfTemplate(
  { data = [], jenis = "sosial" },
  ref
) {
  const { totalPemasukan, totalPengeluaran } = useMemo(() => {
    let masuk = 0;
    let keluar = 0;
    for (const item of data) {
      masuk += Number(item?.pemasukan ?? 0);
      keluar += Number(item?.pengeluaran ?? 0);
    }
    return { totalPemasukan: masuk, totalPengeluaran: keluar };
  }, [data]);

  const saldo = totalPemasukan - totalPengeluaran;

  return (
    <div
      ref={ref}
      style={{
        background: "#fff",
        paddingBottom: "90px", // ✅ ini bikin tulisan ttd nggak kepotong
      }}
    >
      <style>{`
        :root{
            --line: 0.5px;         /* garis row normal (tipis) */
            --lineHeader: 2.5px;   /* garis bawah header (tebal) */
            --headerBg: #111827;   /* warna header */
        }

        .pdf-body {
            font-family: "Times New Roman", Times, serif;
            font-size: 14px;
            color: #000;
            text-align: center;
        }

        .header-table { width: 100%; margin-bottom: 30px; }
        .header-table td { border: none; }
        .header-text { text-align: center; }
        .header-text h3 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            line-height: 1.5;
        }

        .table-main{
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px; /* mirip gambar: tabel agak dekat */
            table-layout: auto; /* posisi kolom konsisten */
        }

        /* ✅ set lebar kolom biar layout stabil seperti gambar */
        .table-main col.col-no { width: 7%; }
        .table-main col.col-id { width: 13%; }
        .table-main col.col-tgl { width: 16%; }
        .table-main col.col-subjek { width: 34%; }
        .table-main col.col-masuk { width: 15%; }
        .table-main col.col-keluar { width: 15%; }

        .table-main th,
        .table-main td{
            border-top: var(--line) solid #4d4d4d;
            border-left: none;
            border-right: none;

            padding: 10px 10px;     /* tinggi baris rapi */
            line-height: 1.2;       /* teks pas di tengah */
            vertical-align: middle; /* center vertikal */
            background:: transparent;
            white-space: nowrap;    /* seperti gambar: 1 baris */
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .table-main th{
            background: var(--headerBg);
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        /* default isi tabel rata kiri */
        .table-main td{ text-align: left; }

        /* alignment kolom seperti gambar */
        .td-center{ text-align: center !important; }
        .td-right{ text-align: right !important; }

        /* baris TOTAL/SALDO: tulisan center & bold */
        .row-summary td{
            font-weight: bold;
        }
        .summary-label{
            text-align: center !important;
            letter-spacing: 0.5px;
        }

        /* (optional) biar angka di total/saldo tetap rapih */
        .summary-value{
            text-align: right !important;
        }

        /* tanda tangan */
        .ttd-block{
            page-break-inside: avoid;
            break-inside: avoid;
            margin-top: 35px;
        }
        .ttd-spacer{ height: 60px; }
        `}
      </style>

      <div className="pdf-body">
        {/* HEADER */}
        <table className="header-table">
          <tbody>
            <tr>
              <td className="header-text">
                <h3>Laporan {ucfirst(jenis)}</h3>
                <h3>Paguyuban Pemuda Prasojo</h3>
                <h3>Desa Juwiring, Kecamatan Juwiring, Kabupaten Klaten</h3>
              </td>
            </tr>
          </tbody>
        </table>

        {/* TABEL */}
        <table className="table-main">
          <thead>
            <tr>
              <th>No</th>
              <th>ID</th>
              <th>Tanggal</th>
              <th>Subjek</th>
              <th>Pemasukan</th>
              <th>Pengeluaran</th>
            </tr>
          </thead>

          <tbody>
            {data.map((item, i) => {
              const pemasukan = Number(item?.pemasukan ?? 0);
              const pengeluaran = Number(item?.pengeluaran ?? 0);

              const kode =
                String(jenis).slice(0, 1).toUpperCase() +
                "-" +
                String(item?.id ?? "").padStart(4, "0");

              return (
                <tr key={item?.id ?? i}>
                  <td style={{ textAlign: "center" }}>{i + 1}</td>
                  <td style={{ textAlign: "center" }}>{kode}</td>
                  <td style={{ textAlign: "center" }}>
                    {formatTanggalID(item?.tanggal)}
                  </td>
                  <td style={{ textAlign: "left" }}>{item?.subjek ?? ""}</td>
                  <td style={{ textAlign: "right" }}>
                    {formatRupiah(pemasukan)}
                  </td>
                  <td style={{ textAlign: "right" }}>
                    {formatRupiah(pengeluaran)}
                  </td>
                </tr>
              );
            })}

            <tr style={{ fontWeight: "bold" }}>
              <td colSpan={4} style={{ textAlign: "center" }}>
                TOTAL
              </td>
              <td style={{ textAlign: "right" }}>
                {formatRupiah(totalPemasukan)}
              </td>
              <td style={{ textAlign: "right" }}>
                {formatRupiah(totalPengeluaran)}
              </td>
            </tr>

            <tr style={{ fontWeight: "bold" }}>
              <td colSpan={4} style={{ textAlign: "center" }}>
                SALDO
              </td>
              <td colSpan={2} style={{ textAlign: "right" }}>
                {formatRupiah(saldo)}
              </td>
            </tr>
          </tbody>
        </table>

        {/* ruang sebelum ttd */}
        <div className="ttd-spacer" />

        {/* TTD */}
        <table className="ttd-block" style={{ width: "100%", border: "none" }}>
          <tbody>
            <tr>
              <td style={{ width: "60%", border: "none" }} />
              <td style={{ width: "40%", textAlign: "center", border: "none" }}>
                <p style={{ margin: 0 }}>Klaten, {formatTanggalTTD()}</p>
                <br />
                <p style={{ margin: 0, fontWeight: "bold" }}>
                  Paguyuban Pemuda Prasojo
                </p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  );
});

export default SosialPdfTemplate;
