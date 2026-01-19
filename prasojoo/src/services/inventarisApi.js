import api from "../api/axios";

export const getInventaris = async (params = {}) => {
  const res = await api.get("/inventaris", { params });
  return res.data;
};

export const createInventaris = data =>
  api.post("/inventaris", data);

export const updateInventaris = (id, data) =>
  api.put(`/inventaris/${id}`, data);

export const deleteInventaris = id =>
  api.delete(`/inventaris/${id}`);
