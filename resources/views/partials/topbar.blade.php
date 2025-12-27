<div class="flex items-center justify-between mb-6 bg-[#111827] text-white px-6 py-4 rounded-lg"> 
    
    {{-- TITLE DINAMIS --}}
    <p class="text-2xl font-semibold">
        @if (request()->routeIs('dashboard'))
            Selamat Datang, {{ ucfirst(auth()->user()->nama) }}👋
        @else
            {{ $pageTitle ?? '' }}
        @endif
    </p>

    {{-- TOMBOL LOGOUT --}}
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded">
            Logout
        </button>
    </form>
</div>
