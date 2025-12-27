<aside class="w-60 h-screen bg-[#111827] p-6 fixed left-0 top-0">
    <img 
        src="{{ asset('logo_prasojo.png') }}" 
        alt="PRASOJO Logo" 
        class="w-60 mt-6 mb-24 mx-auto block"
    />

    <nav class="space-y-8">
        {{-- MENU UMUM (SEMUA ROLE) --}}
        <a href="{{ route('dashboard') }}" 
           class="block px-3 py-2 rounded-lg 
           {{ request()->is('dashboard') ? 'bg-white text-[#111827] text-xl font-bold' : 'text-xl font-bold text-white hover:bg-gray-500' }}">
            🏠 DASHBOARD
        </a>
        <a href="{{ route('kas') }}" 
           class="block px-3 py-2 rounded-lg 
           {{ request()->is('kas') ? 'bg-white text-[#111827] text-xl font-bold' : 'text-xl font-bold text-white hover:bg-gray-500' }}">
            💰 KAS
        </a>
        <a href="{{ route('inventaris') }}" 
           class="block px-3 py-2 rounded-lg 
           {{ request()->is('inventaris') ? 'bg-white text-[#111827] text-xl font-bold' : 'text-xl font-bold text-white hover:bg-gray-500' }}">
            💸 INVENTARIS
        </a>
        <a href="{{ route('sosial') }}" 
           class="block px-3 py-2 rounded-lg 
           {{ request()->is('sosial') ? 'bg-white text-[#111827] text-xl font-bold' : 'text-xl font-bold text-white hover:bg-gray-500' }}">
            🪙 SOSIAL
        </a>

        {{-- MENU KHUSUS ADMIN --}}
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('kegiatan') }}" 
               class="block px-3 py-2 rounded-lg 
               {{ request()->is('kegiatan') ? 'bg-white text-[#111827] text-xl font-bold' : 'text-xl font-bold text-white hover:bg-gray-500' }}">
                📝 KEGIATAN
            </a>

            <a href="{{ route('hakAkses') }}"
               class="block px-3 py-2 rounded-lg
               {{ request()->is('hak-akses') ? 'bg-white text-[#111827] text-xl font-bold' : 'text-xl font-bold text-white hover:bg-gray-500' }}">
               🔐 HAK AKSES
            </a>
        @endif
    </nav>
</aside>
