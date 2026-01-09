<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Prasojo' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* HILANGKAN ICON PASSWORD BAWAAN BROWSER */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        /* Chrome / Edge */
        input[type="password"]::-webkit-textfield-decoration-container {
            display: none;
        }
    </style>

</head>
<body class="bg-gray-100">

    <!-- OVERLAY -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden"></div>

    {{-- SIDEBAR --}}
    @include('partials.sidebar')

    {{-- MAIN CONTENT --}}
    <div id="mainContent" class="ml-2 p-2 relative z-10">
        @include('partials.topbar')
        @yield('content')
    </div>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        // 🔥 FIX UTAMA
        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation(); // ⛔ STOP BUBBLE
            sidebar.classList.contains('-translate-x-full')
                ? openSidebar()
                : closeSidebar();
        });

        sidebar.addEventListener('click', (e) => {
            e.stopPropagation(); // ⛔ klik sidebar tidak nutup
        });

        overlay.addEventListener('click', closeSidebar);
        mainContent.addEventListener('click', closeSidebar);
    </script>

</body>
</html>
