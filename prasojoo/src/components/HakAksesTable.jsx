export default function HakAksesTable({
  data,
  onEdit,
  onDelete,
  sortDir,
  onToggleSortId,
}) {
  return (
    <div className="overflow-x-auto rounded-2xl">
      <table className="min-w-[800px] w-full bg-white">
        <thead className="bg-[#111827] text-white text-sm">
          <tr>
            <th className="p-2 text-center">No</th>
            <th className="p-2 text-center">ID</th>
            <th className="p-2 text-center">Nama</th>
            <th className="p-2 text-center">Email</th>
            <th className="p-2 text-center">Username</th>
            <th className="p-2 text-center">Password</th>
            <th className="p-2 text-center">Role</th>
            <th className="p-2 text-center">Aksi</th>
          </tr>
        </thead>

        <tbody>
          {data.length === 0 && (
            <tr>
              <td colSpan="7"
               className="border border-gray-300 px-3 py-4 text-center text-gray-500"
               >
                Tidak ada data
              </td>
            </tr>
          )}

          {data.map((u, i) => (
            <tr
              key={u.id}
              className="border-b border-gray-300 hover:bg-gray-50"
            >
              <td className="p-2 text-center font-medium">{i + 1}</td>
              <td className="p-2 text-center font-medium">U-{String(u.id).padStart(4, "0")}</td>
              <td className="p-2 font-medium">{u.nama}</td>
              <td className="p-2 font-medium">{u.email}</td>
              <td className="p-2 font-medium">{u.username}</td>
              <td className="text-center font-medium passwordCell">********</td>
              <td className="text-center font-medium roleCell">{u.role}</td>
              <td className="text-center font-medium">
                <div className="flex justify-center gap-3">
                    <button
                    onClick={() => onEdit(u)}
                    className="inline-flex items-center gap-1
                              bg-yellow-400 hover:bg-yellow-600
                              text-black px-2 py-1 rounded text-xs"
                  >
                    {/* ICON PENSIL */}
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

                  {/* HAPUS */}
                  <button
                    onClick={() => onDelete(u.id)}
                    className="inline-flex items-center gap-1
                              bg-red-600 hover:bg-red-700
                              text-white px-2 py-1 rounded text-xs"
                  >
                    {/* ICON SAMPAH */}
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
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
