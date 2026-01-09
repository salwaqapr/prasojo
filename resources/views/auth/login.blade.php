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
        class="min-h-screen flex items-center w-full overflow-hidden
        bg-[#111827] bg-cover
        [background-position:70%_center]"
        style="background-image: url('{{ asset('login.png') }}');"
    >
        <!-- login box -->
        <div class="relative z-10
                    w-full max-w-xs sm:max-w-sm md:max-w-md
                    mx-auto
                    sm:ml-6 sm:mr-0
                    md:ml-16
                    lg:ml-40">
            <div class="bg-[#111827]/95 backdrop-blur-xl 
            rounded-2xl shadow-2xl p-4 sm:p-6 md:p-10">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-center mb-4 sm:mb-6 text-white">
                    LOGIN
                </h2>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- EMAIL -->
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

                    <!-- PASSWORD + EYE -->
                    <div>
                        <label class="block text-sm text-white mb-1">Password</label>

                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="w-full px-4 py-2 pr-12 rounded-lg bg-[#0f172a] text-white
                                       border border-white/10 outline-none
                                       focus:ring-2 focus:ring-blue-500"
                                required
                            >

                            <button
                                type="button"
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-600 hover:text-gray-400"
                            >
                                <!-- eye -->
                                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5
                                             c4.478 0 8.268 2.943 9.542 7
                                             -1.274 4.057-5.064 7-9.542 7
                                             -4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>

                                <!-- eye-off -->
                                <svg id="eyeClose" xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 hidden"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13.875 18.825A10.05 10.05 0 0112 19
                                             c-4.478 0-8.268-2.943-9.542-7
                                             a9.956 9.956 0 012.042-3.368"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6.223 6.223A9.956 9.956 0 0112 5
                                             c4.478 0 8.268 2.943 9.542 7
                                             a9.964 9.964 0 01-4.293 5.293"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-center pt-2">
                        <button
                            type="submit"
                            class="bg-white text-[#111827] font-bold px-8 py-2 rounded-lg
                                   transition hover:bg-[#25ff48]"
                        >
                            Masuk
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClose = document.getElementById('eyeClose');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClose.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClose.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
