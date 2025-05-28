<x-layouts.main>
    <x-slot:title>{{ $title }} Keranjang - InfoEV</x-slot>

    {{-- Meta tags --}}
    <x-slot:meta>
        {{-- Add meta tags if necessary --}}
    </x-slot>

    {{-- Header --}}
    <x-slot:header>
        <x-menu.navbar :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
        <x-menu.menu />
    </x-slot>

    {{-- Sidebar --}}
    <x-slot:sidebar>
        {{-- Brand Menu --}}
        {{-- <x-sidebar.brand-menu :bikeBrands="$bikeBrands" :carBrands="$carBrands" /> --}}

        <x-sidebar.latest :recentVehicles="$recentVehicles" />
        <x-sidebar.top :popularVehicles="$popularVehicles" />
        {{-- <x-sidebar.featured :featuredArticles="$stickies" /> --}}
    </x-slot>

    {{-- Main Content --}}
    <div class="bg-gray-50 min-h-screen">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Page Header -->
           <div class="mb-8 text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Favorite Vehicles</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">Discover and manage your collection of preferred electric vehicles</p>
            </div>


            @if(count($informasiKendaraan) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach ($informasiKendaraan as $kendaraan)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg hover:border-gray-300 group-hover:scale-105 transition-all duration-300 ease-out overflow-hidden group">
                            <!-- Vehicle Image -->
                            <div class="relative bg-gray-100 aspect-square min-h-[200px] overflow-hidden">
                                <a href="{{ route('vehicle.show', ['vehicle' => $kendaraan['slug']]) }}" class="block h-full">
                                    <img src="{{ $kendaraan['gambar'] }}"
                                         class="w-full h-full object-cover relative z-10 group-hover:scale-110 transition-transform duration-500 ease-out"
                                         alt="{{ ($kendaraan['merek'] ?? 'Unknown Brand') . ' ' . ($kendaraan['nama'] ?? 'Unknown Model') }}"
                                         loading="lazy"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <!-- Placeholder for broken image -->
                                    <div class="placeholder absolute inset-0 bg-gradient-to-br from-gray-200 to-gray-300 hidden items-center justify-center z-0">
                                        <div class="text-center">
                                            <svg class="w-10 h-10 text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <p class="text-gray-600 text-sm">Image Not Available</p>
                                        </div>
                                    </div>
                                    <!-- Hover overlay -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center pointer-events-none z-20">
                                        <span class="bg-white text-gray-800 px-3 py-1.5 rounded-md text-sm font-medium opacity-0 group-hover:opacity-100 transform translate-y-3 group-hover:translate-y-0 transition-all duration-300">
                                            View Details
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <!-- Vehicle Info -->
                            <div class="p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-base text-gray-900 mb-1 truncate">
                                            {{ $kendaraan['merek'] ?? 'Unknown Brand' }}
                                        </h3>
                                        <p class="text-gray-600 text-sm truncate">
                                            {{ $kendaraan['nama'] ?? 'Unknown Model' }}
                                        </p>
                                    </div>
                                    <!-- Remove Button -->
                                    <form action="{{ route('keranjang.remove', ['vehicleId' => $kendaraan['id']]) }}" method="POST" class="flex-shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                title="Remove from favorites"
                                                onclick="return confirm('Remove this vehicle from favorites?')"
                                                class="flex items-center justify-center w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 hover:text-red-700 rounded-full transition-all duration-200 hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Results count -->
                <div class="mt-10 text-center">
                    <p class="text-gray-600 text-base">
                        Showing {{ count($informasiKendaraan) }} favorite vehicle{{ count($informasiKendaraan) !== 1 ? 's' : '' }}
                    </p>
                </div>
            @else
                <!-- No Favorites -->
                <div class="text-center py-20">
                    <div class="max-w-lg mx-auto">
                        <div class="w-28 h-28 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-14 h-14 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4">No Favorite Vehicles Yet</h3>
                        <p class="text-gray-600 text-base mb-8 leading-relaxed">You haven't added any vehicles to your favorites. Explore our collection to find your dream electric vehicle!</p>
                        <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-md">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Explore Vehicles
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Footer --}}
    <x-slot:footer>
        <x-menu.footer :logo="$logo"
            :bikeBrands="$bikeBrands"
            :carBrands="$carBrands"
            :recentVehicles="$recentVehicles"
            :popularVehicles="$popularVehicles"
            :featuredArticles="$stickies" />
    </x-slot>
</x-layouts.main>