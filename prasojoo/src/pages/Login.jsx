import { useState } from "react";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [showPassword, setShowPassword] = useState(false);
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
      className="min-h-screen flex items-center w-full overflow-hidden
        bg-[#111827] bg-cover
        [background-position:70%_center]"
      style={{ backgroundImage: "url('/login.png')" }}
    >
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

            {/* EMAIL */}
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

            {/* PASSWORD + EYE */}
            <div>
              <label className="block text-white text-sm mb-1">Password</label>

              <div className="relative">
                <input
                  type={showPassword ? "text" : "password"}
                  className="w-full px-4 py-3 pr-12 rounded-lg bg-[#0f172a] text-white
                             outline-none border border-white/10
                             focus:ring-4 focus:ring-yellow-300"
                  required
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                />

                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute inset-y-0 right-3 flex items-center
                             text-xl text-gray-300 hover:text-white"
                >
                  {showPassword ? "🙈" : "👁️"}
                </button>
              </div>
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
