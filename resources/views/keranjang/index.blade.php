<x-layouts.main>
    <x-slot:title>{{ $title }} - InfoEV</x-slot>

    {{-- Meta tags --}}
    <x-slot:meta>
        {{-- Add meta tags if necessary --}}
    </x-slot>

    {{-- Header --}}
    <x-slot:header>
        {{-- Navbar --}}
        <x-menu.navbar :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" />

        {{-- Additional Menu --}}
        <x-menu.menu />
    </x-slot>
    {{-- End Header --}}

    {{-- Sidebar --}}
    <x-slot:sidebar>
          {{-- Brand Menu --}}
          {{-- <x-sidebar.brand-menu :bikeBrands="$bikeBrands" :carBrands="$carBrands" /> --}}

          {{-- Latest Models --}}
          <x-sidebar.latest :recentVehicles="$recentVehicles" />

          {{-- Top 10 --}}
          <x-sidebar.top :popularVehicles="$popularVehicles" />

          {{-- Featured
        <x-sidebar.featured :featuredArticles="$stickies" /> --}}
    </x-slot>
    {{-- End Sidebar --}}

    {{-- Main Content --}}
    <div class="container mx-auto">
        <h2 class="text-2xl font-bold my-4 px-3">Favorite Vehicles</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @if ($informasiKendaraan && count($informasiKendaraan) > 0)
                @foreach ($informasiKendaraan as $kendaraan)
                    <div class="group flex flex-col px-4 py-3 border border-zinc-200 hover:z-30 hover:scale-105 transition-transform ease-out">
                        <a href="{{ route('vehicle.show', ['vehicle' => $kendaraan['slug']]) }}" class="block mb-2 text-center">
                            <div class="relative">
                                <img src="{{ $kendaraan['gambar'] }}" class="h-32 w-32 object-cover rounded-md shadow-md" alt="Gambar Kendaraan">
                                <div class="absolute inset-0 flex justify-center items-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-opacity-50 rounded-md">
                                    <div class="text-black justify-center items-center">
                                        {{ $kendaraan['merek'] }} {{ $kendaraan['nama'] }}
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="flex flex-col items-end">
                            <form action="{{ route('keranjang.remove', ['vehicleId' => $kendaraan['id']]) }}" method="POST" class="self-end">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex bg-red-500 hover:bg-red-600 text-black text-sm font-medium px-4 py-2 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-1 14H6L5 7M4 4h16M9 4V2h6v2H9z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center">Belum ada kendaraan dalam keranjang.</p>
            @endif
        </div>
    </div>
    {{-- End Main Content --}}

    {{-- Footer --}}
    <x-slot:footer>
        <x-menu.footer :logo="$logo"
            :bikeBrands="$bikeBrands"
            :carBrands="$carBrands"
            :recentVehicles="$recentVehicles"
            :popularVehicles="$popularVehicles"
            :featuredArticles="$stickies" />
    </x-slot>
    {{-- End Footer --}}
</x-layouts.main>



