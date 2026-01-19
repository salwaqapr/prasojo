import api from "../api/axios";

export const getHakAkses = async (params = {}) => {
  const res = await api.get("/hakAkses", { params });
  return res.data;
};

export const createHakAkses = data =>
  api.post("/hakAkses", data);

export const updateHakAkses = (id, data) =>
  api.put(`/hakAkses/${id}`, data);

export const deleteHakAkses = id =>
  api.delete(`/hakAkses/${id}`);
