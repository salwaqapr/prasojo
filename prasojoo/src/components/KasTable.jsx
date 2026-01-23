export default function KasTable({
  data,
  onEdit,
  onDelete,
  sortBy,
  sortDir,
  onToggleSortTanggal,
  totalPemasukan,
  totalPengeluaran,
  saldoAkhir,
  canManage = false,
}) {
  const colSpanEmpty = canManage ? 7 : 6;

  return (
    <div className="bg-white rounded-2xl shadow overflow-hidden">
      {/* ✅ hanya tabel yang scroll horizontal */}
      <div
        className="w-full overflow-x-auto overscroll-x-contain touch-auto"
        style={{ WebkitOverflowScrolling: "touch", scrollbarGutter: "stable" }}
      >
        {/* ✅ wrapper biar tabel punya lebar minimum (desktop tetap rapi) */}
        <div className="min-w-[900px]">
          <table className="w-full bg-white table-auto text-sm">
            <thead className="bg-[#111827] text-white text-sm">
              <tr>
                <th className="p-2 text-center whitespace-nowrap">No</th>
                <th className="p-2 text-center whitespace-nowrap">ID</th>
                
                <th
                  className="p-2 text-center cursor-pointer select-none whitespace-nowrap"
                  onClick={onToggleSortTanggal}
                >
                  Tanggal{" "}
                  {sortBy !== "tanggal" && "⇅"}
                  {sortBy === "tanggal" && sortDir === "asc" && "▼"}
                  {sortBy === "tanggal" && sortDir === "desc" && "▲"}
                </th>

                <th className="p-2 text-center">Subjek</th>
                <th className="p-2 text-center whitespace-nowrap">Pemasukan</th>
                <th className="p-2 text-center whitespace-nowrap">Pengeluaran</th>
                {canManage && (
                  <th className="p-2 text-center whitespace-nowrap">Aksi</th>
                )}
              </tr>
            </thead>

            <tbody>
              {data.length === 0 && (
                <tr>
                  <td
                    colSpan={colSpanEmpty}
                    className="border border-gray-300 px-3 py-4 text-center text-gray-500"
                  >
                    Tidak ada data
                  </td>
                </tr>
              )}

              {data.map((k, i) => (
                <tr
                  key={k.id}
                  className="border-b border-gray-300 hover:bg-gray-50"
                >
                  <td className="p-2 text-center font-medium whitespace-nowrap">
                    {i + 1}
                  </td>

                  <td className="p-2 text-center font-medium whitespace-nowrap">
                    K-{String(k.id).padStart(4, "0")}
                  </td>

                  <td className="p-2 text-center font-medium whitespace-nowrap">
                    {k.tanggal.split("-").reverse().join("-")}
                  </td>

                  <td className="p-2 font-medium">
                    {/* ✅ biar subjek panjang tetap rapi */}
                    <div className="min-w-[240px]">
                      {k.subjek}
                    </div>
                  </td>

                  <td className="p-2 text-right font-medium whitespace-nowrap">
                    Rp {(k.pemasukan ?? 0).toLocaleString("id-ID")}
                  </td>

                  <td className="p-2 text-right font-medium whitespace-nowrap">
                    Rp {(k.pengeluaran ?? 0).toLocaleString("id-ID")}
                  </td>

                  {canManage && (
                    <td className="p-2 text-center whitespace-nowrap">
                      <div className="flex justify-center gap-3">
                        <button
                          onClick={() => onEdit(k)}
                          className="inline-flex items-center gap-1
                                bg-yellow-400 hover:bg-yellow-600
                                text-black px-2 py-1 rounded text-xs"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            className="w-3.5 h-3.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                          >
                            <path
                              strokeLinecap="round"
                              strokeLinejoin="round"
                              strokeWidth="2"
                              d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5
                            M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"
                            />
                          </svg>
                          <span>Edit</span>
                        </button>

                        <button
                          onClick={() => onDelete(k.id)}
                          className="inline-flex items-center gap-1
                                bg-red-600 hover:bg-red-700
                                text-white px-2 py-1 rounded text-xs"
                        >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            className="w-3.5 h-3.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                          >
                            <path
                              strokeLinecap="round"
                              strokeLinejoin="round"
                              strokeWidth="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                            a2 2 0 01-1.995-1.858L5 7
                            m5 4v6m4-6v6
                            M9 7h6m-6 0V5a1 1 0 011-1h4
                            a1 1 0 011 1v2"
                            />
                          </svg>
                          <span>Hapus</span>
                        </button>
                      </div>
                    </td>
                  )}
                </tr>
              ))}

              {/* ===== BARIS JUMLAH ===== */}
              {data.length > 0 && (
                <tr className="font-bold">
                  <td colSpan="3"></td>
                  <td className="p-2 text-right whitespace-nowrap">Jumlah</td>

                  <td className="p-2 text-right text-green-700 whitespace-nowrap">
                    Rp {totalPemasukan.toLocaleString("id-ID")}
                  </td>

                  <td className="p-2 text-right text-red-700 whitespace-nowrap">
                    Rp {totalPengeluaran.toLocaleString("id-ID")}
                  </td>

                  {canManage && <td></td>}
                </tr>
              )}

              {/* ===== BARIS SALDO ===== */}
              {data.length > 0 && (
                <tr className="font-bold">
                  <td colSpan="3"></td>
                  <td className="p-2 text-right whitespace-nowrap">Saldo</td>

                  <td
                    colSpan="2"
                    className={`p-2 text-right whitespace-nowrap ${
                      saldoAkhir >= 0 ? "text-blue-700" : "text-red-700"
                    }`}
                  >
                    Rp {saldoAkhir.toLocaleString("id-ID")}
                  </td>

                  {canManage && <td></td>}
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
