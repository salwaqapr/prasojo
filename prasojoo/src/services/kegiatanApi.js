import { api } from "../lib/api";

export async function getKegiatan() {
  const res = await api.get("/kegiatan");
  return res.data;
}

export async function createKegiatan(formData) {
  const res = await api.post("/kegiatan", formData); // ✅ multipart otomatis
  return res.data;
}

export async function updateKegiatan(id, formData) {
  // kamu pakai _method=PUT di formData, jadi tetap POST
  const res = await api.post(`/kegiatan/${id}`, formData);
  return res.data;
}

export async function deleteKegiatan(id) {
  const res = await api.delete(`/kegiatan/${id}`);
  return res.data;
}
