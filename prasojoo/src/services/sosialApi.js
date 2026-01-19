import api from "../api/axios";

export const getSosial = async (params = {}) => {
  const res = await api.get("/sosial", { params });
  return res.data;
};

export const createSosial = data =>
  api.post("/sosial", data);

export const updateSosial = (id, data) =>
  api.put(`/sosial/${id}`, data);

export const deleteSosial = id =>
  api.delete(`/sosial/${id}`);
