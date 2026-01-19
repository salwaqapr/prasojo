// src/utils/auth.js

export function getLocalUser() {
  try {
    const raw = localStorage.getItem("user");
    return raw ? JSON.parse(raw) : null;
  } catch {
    return null;
  }
}

export function logout() {
  // 1) hapus user dulu
  localStorage.removeItem("user");

  // 2) pindah ke landing (replace supaya history bersih)
  window.location.replace("/");
}
