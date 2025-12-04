<aside class="w-60 bg-[#111827] h-screen shadow-md p-6">
    <img 
        src="{{ asset('logo_prasojo.png') }}" 
        alt="PRASOJO Logo" 
        class="w-50 mb-8 mx-auto block"
    />

    <nav class="space-y-3">

        <a href="{{ route('dashboard') }}" 
           class="block px-3 py-2 rounded-lg 
           {{ request()->is('dashboard') ? 'bg-white text-[#111827] font-medium' : 'text-white hover:bg-gray-700' }}">
            Dashboard
        </a>

        <a href="{{ route('kas') }}" 
           class="block px-3 py-2 rounded-lg 
           {{ request()->is('kas') ? 'bg-white text-[#111827] font-medium' : 'text-white hover:bg-gray-700' }}">
            Kas
        </a>

        <a href="{{ route('inventaris') }}" 
           class="block px-3 py-2 rounded-lg 
           {{ request()->is('inventaris') ? 'bg-white text-[#111827] font-medium' : 'text-white hover:bg-gray-700' }}">
            Inventaris
        </a>

        <a href="{{ route('sosial') }}" 
           class="block px-3 py-2 rounded-lg 
           {{ request()->is('sosial') ? 'bg-white text-[#111827] font-medium' : 'text-white hover:bg-gray-700' }}">
            Sosial
        </a>

        <a href="{{ route('kegiatan') }}" 
           class="block px-3 py-2 rounded-lg 
           {{ request()->is('kegiatan') ? 'bg-white text-[#111827] font-medium' : 'text-white hover:bg-gray-700' }}">
            Kegiatan
        </a>

        <a href="{{ route('hakAkses') }}" 
           class="block px-3 py-2 rounded-lg 
           {{ request()->is('hakAkses') ? 'bg-white text-[#111827] font-medium' : 'text-white hover:bg-gray-700' }}">
            Hak Akses
        </a>

    </nav>
</aside>
