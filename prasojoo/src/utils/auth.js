import axios from "axios";

export function getLocalUser() {
  try {
    const raw = localStorage.getItem("user");
    return raw ? JSON.parse(raw) : null;
  } catch {
    return null;
  }
}

export async function logout() {
  try {
    await axios.post("http://127.0.0.1:8000/api/logout", {}, { withCredentials: true });
  } catch (e) {
    // kalau gagal pun tetep lanjut hapus local & redirect
  } finally {
    localStorage.removeItem("user");
    window.location.replace("/");
  }
}
