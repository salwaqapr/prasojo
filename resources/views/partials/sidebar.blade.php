<aside  
    id="sidebar"
    class="fixed left-0 top-0 z-50 h-screen w-60 bg-[#111827] p-6
           transform -translate-x-full transition-transform duration-300"
>
    <img src="{{ asset('logo_prasojo.png') }}" class="w-52 mt-3 mb-24 mx-auto">

    <nav class="space-y-6">

        <a href="{{ route('dashboard') }}"
           class="block px-3 py-2 text-xl font-bold rounded
           {{ request()->routeIs('dashboard') 
                ? 'bg-gray-200 text-[#111827]' 
                : 'text-white hover:bg-gray-600' }}">
            🏠 DASHBOARD
        </a>

        <a href="{{ route('kas') }}"
           class="block px-3 py-2 text-xl font-bold rounded
           {{ request()->routeIs('kas') 
                ? 'bg-gray-200 text-[#111827]' 
                : 'text-white hover:bg-gray-600' }}">
            💰 KAS
        </a>

        <a href="{{ route('inventaris') }}"
           class="block px-3 py-2 text-xl font-bold rounded
           {{ request()->routeIs('inventaris') 
                ? 'bg-gray-200 text-[#111827]' 
                : 'text-white hover:bg-gray-600' }}">
            💸 INVENTARIS
        </a>

        <a href="{{ route('sosial') }}"
           class="block px-3 py-2 text-xl font-bold rounded
           {{ request()->routeIs('sosial') 
                ? 'bg-gray-200 text-[#111827]' 
                : 'text-white hover:bg-gray-600' }}">
            🪙 SOSIAL
        </a>

        @if(auth()->user()->role === 'admin')
            <a href="{{ route('kegiatan') }}"
               class="block px-3 py-2 text-xl font-bold rounded
               {{ request()->routeIs('kegiatan') 
                    ? 'bg-gray-200 text-[#111827]' 
                    : 'text-white hover:bg-gray-600' }}">
                📝 KEGIATAN
            </a>

            <a href="{{ route('hakAkses') }}"
               class="block px-3 py-2 text-xl font-bold rounded
               {{ request()->routeIs('hakAkses') 
                    ? 'bg-gray-200 text-[#111827]' 
                    : 'text-white hover:bg-gray-600' }}">
                🔐 HAK AKSES
            </a>
        @endif

    </nav>
</aside>
