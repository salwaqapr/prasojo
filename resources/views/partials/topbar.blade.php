<div class="flex items-center justify-between mb-6 bg-[#111827] text-white px-6 py-4 rounded-lg">
    
    {{-- TITLE DINAMIS --}}
    <p class="text-2xl font-semibold">
        {{ $pageTitle ?? '' }}
    </p>

    {{-- TOMBOL LOGOUT --}}
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">
            Logout
        </button>
    </form>
</div>
