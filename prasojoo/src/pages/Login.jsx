import { useState } from "react";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  const handleLogin = async (e) => {
    e.preventDefault();
    console.log({ email, password });
    // nanti tinggal dihubungkan ke Laravel API /api/login
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-700 p-4">
      <div className="w-full max-w-md bg-white/20 backdrop-blur-xl border border-white/30 shadow-2xl rounded-2xl p-8 animate-fadeIn">

        <h2 className="text-4xl font-extrabold text-center text-white drop-shadow mb-6">
          Login
        </h2>

        <form onSubmit={handleLogin} className="space-y-5">

          <div>
            <label className="block text-white text-sm mb-1">Email</label>
            <input
              type="email"
              className="w-full px-4 py-3 rounded-lg bg-white/90 focus:ring-4 focus:ring-yellow-300 outline-none border-0"
              required
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />
          </div>

          <div>
            <label className="block text-white text-sm mb-1">Password</label>
            <input
              type="password"
              className="w-full px-4 py-3 rounded-lg bg-white/90 focus:ring-4 focus:ring-yellow-300 outline-none border-0"
              required
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />
          </div>

          <button
            type="submit"
            className="w-full py-3 bg-yellow-400 hover:bg-yellow-500 transition font-bold text-gray-900 rounded-lg shadow-lg shadow-yellow-300/40"
          >
            Masuk
          </button>
        </form>
      </div>
    </div>
  );
}
