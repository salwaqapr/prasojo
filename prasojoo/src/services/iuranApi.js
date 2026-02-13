import axios from "axios";

// Pakai env kalau ada, fallback ke localhost
const api = axios.create({
  baseURL: import.meta?.env?.VITE_API_BASE_URL ?? "http://127.0.0.1:8000/api",
  headers: {
    Accept: "application/json",
  },
});

// helper biar error dari axios enak dibaca
const pickErrorMessage = (err) => {
  if (err?.response?.data?.message) return err.response.data.message;
  if (err?.message) return err.message;
  return "Terjadi kesalahan.";
};

export const getIuranMembers = async (search = "") => {
  try {
    const res = await api.get("/iuran-members", { params: { search } });
    return res.data;
  } catch (err) {
    throw new Error(pickErrorMessage(err));
  }
};

export const createIuranMember = async (payload) => {
  try {
    const res = await api.post("/iuran-members", payload);
    return res.data;
  } catch (err) {
    throw new Error(pickErrorMessage(err));
  }
};

export const updateIuranMember = async (id, payload) => {
  try {
    const res = await api.put(`/iuran-members/${id}`, payload);
    return res.data;
  } catch (err) {
    throw new Error(pickErrorMessage(err));
  }
};

export const deleteIuranMember = async (id) => {
  try {
    const res = await api.delete(`/iuran-members/${id}`);
    return res.data;
  } catch (err) {
    throw new Error(pickErrorMessage(err));
  }
};

export const getMemberPayments = async (memberId) => {
  try {
    const res = await api.get(`/iuran-members/${memberId}/payments`);
    return res.data;
  } catch (err) {
    throw new Error(pickErrorMessage(err));
  }
};

export const createMemberPayment = async (memberId, payload) => {
  try {
    const res = await api.post(`/iuran-members/${memberId}/payments`, payload);
    return res.data;
  } catch (err) {
    throw new Error(pickErrorMessage(err));
  }
};

export const updatePayment = async (paymentId, payload) => {
  try {
    const res = await api.put(`/iuran-payments/${paymentId}`, payload);
    return res.data;
  } catch (err) {
    throw new Error(pickErrorMessage(err));
  }
};

export const deletePayment = async (paymentId) => {
  try {
    const res = await api.delete(`/iuran-payments/${paymentId}`);
    return res.data;
  } catch (err) {
    throw new Error(pickErrorMessage(err));
  }
};

export const getMemberSummary = async (memberId) => {
  try {
    const res = await api.get(`/iuran-members/${memberId}/summary`);
    return res.data;
  } catch (err) {
    throw new Error(pickErrorMessage(err));
  }
};
