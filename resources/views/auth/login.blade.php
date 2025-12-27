<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        html, body {
            height: 100%;
        }
    </style>
</head>

<body class="m-0 p-0">
    <div
        class="relative min-h-screen w-full bg-cover bg-center flex items-center"
        style="background-image: url('{{ asset('login.png') }}');"
    >
        <!-- login box -->
        <div class="relative z-10 ml-6 md:ml-16 lg:ml-40 w-full max-w-md">
            <div class="bg-[#111827]/95 backdrop-blur-xl rounded-2xl shadow-2xl p-10">

                <h2 class="text-3xl font-bold text-center mb-6 text-white">
                    LOGIN
                </h2>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm text-white mb-1">Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full px-4 py-2 rounded-lg bg-[#0f172a] text-white
                                   border border-white/10 outline-none
                                   focus:ring-2 focus:ring-blue-500"
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-sm text-white mb-1">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="w-full px-4 py-2 rounded-lg bg-[#0f172a] text-white
                                   border border-white/10 outline-none
                                   focus:ring-2 focus:ring-blue-500"
                            required
                        >
                    </div>

                    <div class="flex justify-center pt-2">
                        <button
                            type="submit"
                            class="bg-white text-[#111827] font-bold px-8 py-2 rounded-lg
                                   transition hover:bg-[#25ff48] hover:text-[#111827]"
                        >
                            Masuk
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>
</html>
