export default function IuranMemberTable({
  data,
  canManage,
  onTambahIuran,
  onEditIuran,
  onDetail,
  onDeleteMember,
}) {
  return (
    <div className="bg-white rounded-2xl shadow overflow-hidden">
      <div className="w-full overflow-x-auto">
        <div className="min-w-[900px]">
          <table className="w-full bg-white table-auto text-sm">
            <thead className="bg-[#111827] text-white text-sm">
              <tr>
                <th className="p-2 text-center whitespace-nowrap">No</th>
                <th className="p-2 text-center whitespace-nowrap">ID</th>
                <th className="p-2 text-left whitespace-nowrap">Nama</th>
                <th className="p-2 text-center whitespace-nowrap">Aksi</th>
              </tr>
            </thead>

            <tbody>
              {data.length === 0 && (
                <tr>
                  <td colSpan={4} className="border px-3 py-6 text-center text-gray-500">
                    Tidak ada anggota
                  </td>
                </tr>
              )}

              {data.map((m, i) => (
                <tr key={m.id} className="border-b hover:bg-gray-50">
                  <td className="p-2 text-center whitespace-nowrap">{i + 1}</td>
                  <td className="p-2 text-center whitespace-nowrap">{m.kode}</td>
                  <td className="p-2 font-medium">{m.nama}</td>

                  <td className="p-2 text-center whitespace-nowrap">
                    <div className="flex justify-center gap-2 flex-wrap">
                      {canManage && (
                        <>
                          <button
                            onClick={() => onTambahIuran(m)}
                            className="inline-flex items-center gap-1
                                bg-green-700 hover:bg-green-800
                                text-white px-2 py-1 rounded text-xs"
                          >
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            className="w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                          >
                            <path
                              strokeLinecap="round"
                              strokeLinejoin="round"
                              strokeWidth="2"
                              d="M12 4v16m8-8H4"
                            />
                          </svg>
                            Tambah
                          </button>

                          <button
                            onClick={() => onEditIuran(m)}
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
                            Edit
                          </button>

                          <button
                            onClick={() => onDetail(m)}
                            className="inline-flex items-center gap-1
                                      bg-blue-700 hover:bg-blue-800
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
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                              />
                              <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                              />
                            </svg>
                            Detail
                          </button>


                          <button
                            onClick={() => onDeleteMember(m)}
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
                            Hapus
                          </button>
                        </>
                      )}
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>

          </table>
        </div>
      </div>
    </div>
  );
}
