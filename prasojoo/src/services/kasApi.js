import api from "../api/axios";

export const getKas = async (params = {}) => {
  const res = await api.get("/kas", { params });
  return res.data;
};

export const createKas = data =>
  api.post("/kas", data);

export const updateKas = (id, data) =>
  api.put(`/kas/${id}`, data);

export const deleteKas = id =>
  api.delete(`/kas/${id}`);
