<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Prasojo' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    {{-- SIDEBAR --}}
    @include('partials.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="ml-60 p-8">

        {{-- TOP NAV --}}
        @include('partials.topbar')

        {{-- KONTEN HALAMAN --}}
        @yield('content')

    </div>
</body>
</html>
