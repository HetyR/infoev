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
    <div class="bg-white min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Page Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Favorite Vehicles</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">Discover and manage your collection of preferred electric
                    vehicles</p>
            </div>

            <!-- Vehicle Cards Grid -->
            @if ($informasiKendaraan && count($informasiKendaraan) > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach ($informasiKendaraan as $kendaraan)
                        <div
                            class="bg-white rounded shadow-sm border border-gray-100 hover:shadow-md hover:border-gray-200 transition-all duration-300 overflow-hidden group">
                            <!-- Vehicle Image -->
                            <div class="relative bg-gray-50 aspect-square overflow-hidden">
                                <a href="{{ route('vehicle.show', ['vehicle' => $kendaraan['slug']]) }}"
                                    class="block h-full">
                                    <img src="{{ $kendaraan['gambar'] ?? 'https://via.placeholder.com/300' }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        alt="{{ ($kendaraan['merek'] ?? '') . ' ' . ($kendaraan['nama'] ?? '') }}"
                                        loading="lazy" onerror="this.src='https://via.placeholder.com/300';">

                                    <!-- Placeholder for missing/broken images -->
                                    <div
                                        class="placeholder absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200 hidden items-center justify-center">
                                        <div class="text-center">
                                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-1" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-gray-500 text-xs">Image Error</p>
                                        </div>
                                    </div>

                                    <!-- Debug info (remove in production) -->
                                    <div
                                        class="absolute top-1 left-1 bg-black bg-opacity-75 text-white text-xs px-1 py-0.5 rounded max-w-full truncate">
                                        {{ Str::limit($kendaraan['gambar'] ?? 'No URL', 30) }}
                                    </div>

                                    <!-- Hover overlay -->
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                                        <span
                                            class="bg-white text-gray-800 px-2 py-1 rounded text-xs font-medium opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                                            View
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <!-- Vehicle Info -->
                            <div class="p-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-sm text-gray-900 mb-0.5 truncate">
                                            {{ $kendaraan['merek'] ?? 'Unknown Brand' }}
                                        </h3>
                                        <p class="text-gray-600 text-xs truncate">
                                            {{ $kendaraan['nama'] ?? 'Unknown Model' }}
                                        </p>
                                    </div>

                                    <!-- Remove Button -->
                                    <form action="{{ route('keranjang.remove', ['vehicleId' => $kendaraan['id']]) }}"
                                        method="POST" class="flex-shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Remove from favorites"
                                            onclick="return confirm('Remove this vehicle?')"
                                            class="flex items-center justify-center w-7 h-7 bg-red-50 hover:bg-red-100 text-red-500 hover:text-red-600 rounded transition-all duration-200 hover:scale-105">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" class="w-3.5 h-3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Results count -->
                <div class="mt-8 text-center">
                    <p class="text-gray-500 text-sm">
                        Showing {{ count($informasiKendaraan) }} favorite
                        vehicle{{ count($informasiKendaraan) !== 1 ? 's' : '' }}
                    </p>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <!-- Empty state illustration -->
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-800 mb-3">No Favorite Vehicles</h3>
                        <p class="text-gray-600 mb-6">
                            You haven't added any vehicles to your favorites yet. Start exploring our collection to find
                            vehicles you love!
                        </p>

                        <a href="{{ url('/') }}"
                            class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Explore Vehicles
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
    {{-- End Main Content --}}

    {{-- Footer --}}
    <x-slot:footer>
        <x-menu.footer :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" :recentVehicles="$recentVehicles" :popularVehicles="$popularVehicles"
            :featuredArticles="$stickies" />
    </x-slot>
    {{-- End Footer --}}
</x-layouts.main>
