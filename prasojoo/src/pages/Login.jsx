import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  const navigate = useNavigate();

  // Paksa kosong saat halaman login dibuka
  useEffect(() => {
    setEmail("");
    setPassword("");
  }, []);

  const handleLogin = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);

    try {
      const res = await fetch("http://127.0.0.1:8000/api/login", {
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
        setLoading(false);
        return;
      }

      // simpan user + kasih tahu App kalau auth berubah
      localStorage.setItem("user", JSON.stringify(data.user));
      window.dispatchEvent(new Event("auth:changed"));

      setLoading(false);
      navigate("/dashboard", { replace: true });
    } catch (err) {
      setError("Server tidak dapat dihubungi");
      setLoading(false);
    }
  };

  return (
    <div
      className="min-h-screen flex items-center w-full overflow-hidden bg-[#111827] bg-cover [background-position:70%_center]"
      style={{ backgroundImage: "url('/login.png')" }}
    >
      <div className="relative z-10 ml-6 md:ml-20 w-full max-w-md p-4">
        <div className="bg-[#111827]/95 backdrop-blur-xl border border-white/10 shadow-2xl rounded-2xl p-8">
          <h2 className="text-4xl font-extrabold text-center text-white mb-6">
            LOGIN
          </h2>

          {error && (
            <div className="bg-red-100 text-red-700 p-2 mb-4 rounded text-sm text-center">
              {error}
            </div>
          )}

          <form onSubmit={handleLogin} className="space-y-4" autoComplete="off">
            {/* EMAIL */}
            <div>
              <label className="block text-white text-sm mb-1">Email</label>
              <input
                type="email"
                name="email-login"
                autoComplete="new-email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="w-full px-4 py-3 rounded-lg bg-[#0f172a] text-white outline-none border border-white/10 focus:ring-1 focus:ring-gray-200"
              />
            </div>

            {/* PASSWORD + EYE */}
            <div>
              <label className="block text-white text-sm mb-1">Password</label>

              <div className="relative">
                <input
                  type={showPassword ? "text" : "password"}
                  name="password-login"
                  autoComplete="new-password"
                  required
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  className="w-full px-4 py-3 rounded-lg bg-[#0f172a] text-white outline-none border border-white/10 focus:ring-1 focus:ring-gray-200"
                />

                <button
                  type="button"
                  onClick={() => setShowPassword((v) => !v)}
                  className="absolute inset-y-0 right-3 flex items-center text-xl text-gray-300 hover:text-white"
                  aria-label="Toggle password visibility"
                >
                  {/* EYE OPEN */}
                {!showPassword && (
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    className="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      strokeWidth="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      strokeWidth="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5
                        c4.478 0 8.268 2.943 9.542 7
                        -1.274 4.057-5.064 7-9.542 7
                        -4.477 0-8.268-2.943-9.542-7z"
                    />
                  </svg>
                )}

                {/* EYE CLOSE */}
                {showPassword && (
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    className="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      strokeWidth="2"
                      d="M13.875 18.825A10.05 10.05 0 0112 19
                        c-4.478 0-8.268-2.943-9.542-7
                        a9.956 9.956 0 012.042-3.368"
                    />
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      strokeWidth="2"
                      d="M6.223 6.223A9.956 9.956 0 0112 5
                        c4.478 0 8.268 2.943 9.542 7
                        a9.964 9.964 0 01-4.293 5.293"
                    />
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      strokeWidth="2"
                      d="M3 3l18 18"
                    />
                  </svg>
                )}
                </button>
              </div>
            </div>

            <button
              type="submit"
              disabled={loading}
              className="mt-4 w-full py-3 bg-gray-200 hover:bg-[#ffa725] disabled:opacity-60 transition font-bold text-gray-900 rounded-lg"
            >
              Masuk
            </button>
          </form>
        </div>
      </div>
    </div>
  );
}
