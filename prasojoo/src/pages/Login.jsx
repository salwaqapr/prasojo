import { useState } from "react";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");

  const handleLogin = async (e) => {
    e.preventDefault();
    setError("");

    try {
      const res = await fetch("http://localhost:8000/api/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({ email, password }),
      });

      const data = await res.json();

      if (!res.ok) {
        setError(data.message || "Login gagal");
        return;
      }

      console.log("Login sukses:", data);
      // window.location.href = "/dashboard";

    } catch {
      setError("Server tidak dapat dihubungi");
    }
  };

  return (
  <div
  className="relative min-h-screen w-full bg-cover bg-center flex items-center"
  style={{ backgroundImage: "url('/login.png')" }}
  >

      {/* login box */}
      <div className="relative z-10 ml-6 md:ml-20 w-full max-w-md p-4">
        <div className="bg-[#111827]/95 backdrop-blur-xl border border-white/10 shadow-2xl rounded-2xl p-8">

          <h2 className="text-4xl font-extrabold text-center text-white mb-6">
            Login
          </h2>

          {error && (
            <div className="bg-red-100 text-red-700 p-2 mb-4 rounded text-sm text-center">
              {error}
            </div>
          )}

          <form onSubmit={handleLogin} className="space-y-4">
            <div>
              <label className="block text-white text-sm mb-1">Email</label>
              <input
                type="email"
                className="w-full px-4 py-3 rounded-lg bg-[#0f172a] text-white
                           outline-none border border-white/10
                           focus:ring-4 focus:ring-yellow-300"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
              />
            </div>

            <div>
              <label className="block text-white text-sm mb-1">Password</label>
              <input
                type="password"
                className="w-full px-4 py-3 rounded-lg bg-[#0f172a] text-white
                           outline-none border border-white/10
                           focus:ring-4 focus:ring-yellow-300"
                required
                value={password}
                onChange={(e) => setPassword(e.target.value)}
              />
            </div>

            <button
              type="submit"
              className="w-full py-3 bg-yellow-400 hover:bg-yellow-500 transition
                         font-bold text-gray-900 rounded-lg"
            >
              Masuk
            </button>
          </form>
        </div>
      </div>
    </div>
  );
}
