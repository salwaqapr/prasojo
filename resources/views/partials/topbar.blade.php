<div class="p-3">
    <div class="flex items-center justify-between bg-[#111827] text-white px-6 py-4 rounded-lg"> 
    
        <div class="flex items-center gap-4">
            <button 
                id="menuToggle"
                class="p-2 rounded hover:bg-gray-700"
            >
                <img src="{{ asset('menu_icon.svg') }}" class="w-6 h-6">
            </button>

            <p class="text-2xl font-semibold">
                @if (request()->routeIs('dashboard'))
                    Selamat Datang, {{ ucfirst(auth()->user()->nama) }} 👋
                @else
                    {{ $pageTitle ?? '' }}
                @endif
            </p>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-1 rounded">
                Logout
            </button>
        </form>
    </div>
</div>