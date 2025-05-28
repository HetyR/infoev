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
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100">
        <div class="container mx-auto px-4 py-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Favorite Vehicles</h1>
                <p class="text-gray-600">Your collection of preferred electric vehicles</p>
                <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-teal-500 rounded-full mt-4"></div>
            </div>

            <!-- Vehicle Cards Grid -->
            @if(count($informasiKendaraan) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach ($informasiKendaraan as $kendaraan)
                        <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:border-gray-200">
                            <!-- Vehicle Image Container -->
                                 <div class="relative bg-gray-100 aspect-square min-h-[200px] overflow-hidden">
                                <a href="{{ route('vehicle.show', ['vehicle' => $kendaraan['slug']]) }}" class="block h-full">
                                    <img src="{{ $kendaraan['gambar'] }}"
                                         class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                                         alt="{{ ($kendaraan['merek'] ?? 'Unknown Brand') . ' ' . ($kendaraan['nama'] ?? 'Unknown Model') }}"
                                         loading="lazy"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    
                                    <!-- Hover Overlay -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 rounded-xl flex items-center justify-center">
                                        <div class="bg-white bg-opacity-90 backdrop-blur-sm px-4 py-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                                            <span class="text-sm font-semibold text-gray-800">View Details</span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Vehicle Info -->
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-lg text-gray-900 mb-1 group-hover:text-blue-600 transition-colors duration-300">
                                            {{ $kendaraan['merek'] }}
                                        </h3>
                                        <p class="text-gray-600 text-sm font-medium">
                                            {{ $kendaraan['nama'] }}
                                        </p>
                                    </div>
                                    
                                    <!-- Remove Button -->
                                    <form action="{{ route('keranjang.remove', ['vehicleId' => $kendaraan['id']]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="flex items-center justify-center w-10 h-10 bg-red-50 hover:bg-red-100 text-red-500 hover:text-red-600 rounded-full transition-all duration-300 hover:scale-110 group/btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 group-hover/btn:scale-110 transition-transform duration-300">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Card Border Gradient -->
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-blue-500 via-teal-500 to-green-500 opacity-0 group-hover:opacity-10 transition-opacity duration-500 pointer-events-none"></div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16">
                    <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-16 h-16 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Favorite Vehicles Yet</h3>
                    <p class="text-gray-600 text-center max-w-md mb-6">
                        Start exploring our collection and add vehicles to your favorites to see them here.
                    </p>
                    <a href="{{ route('home') }}" class="bg-gradient-to-r from-blue-500 to-teal-500 hover:from-blue-600 hover:to-teal-600 text-white font-semibold px-8 py-3 rounded-full transition-all duration-300 hover:scale-105 hover:shadow-lg">
                        Explore Vehicles
                    </a>
                </div>
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