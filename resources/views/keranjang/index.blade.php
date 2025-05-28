<x-layouts.main>
    <x-slot:title>{{ $title }} Keranjang - InfoEV</x-slot>

    {{-- Meta tags --}}
    <x-slot:meta>
        <meta name="description"
            content="Manage your favorite electric vehicles on InfoEV. Explore and curate your dream EV collection.">
        <meta name="keywords" content="electric vehicles, EV favorites, InfoEV, electric cars, electric bikes">
    </x-slot>

    {{-- Header --}}
    <x-slot:header>
        <x-menu.navbar :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
        <x-menu.menu />
    </x-slot>

    {{-- Sidebar --}}
    <x-slot:sidebar>
        <x-sidebar.latest :recentVehicles="$recentVehicles" />
        <x-sidebar.top :popularVehicles="$popularVehicles" />
    </x-slot>

    {{-- Main Content --}}
    <div class="bg-gradient-to-b from-gray-100 to-gray-50 min-h-screen py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-10">
            <!-- Page Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Favorite Vehicles</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">Temukan dan kelola koleksi kendaraan
                    listrik pilihan Anda</p>
            </div>

            @if (count($informasiKendaraan) > 0)
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-y-12 gap-x-8 mt-6 mb-6">
                    @foreach ($informasiKendaraan as $kendaraan)
                        <div
                            class="relative bg-white rounded-2xl shadow-md border border-gray-200 hover:shadow-xl hover:scale-105 transition-all duration-300 ease-out group overflow-hidden">
                            <!-- Vehicle Image -->
                            <div class="relative bg-gray-100 aspect-square min-h-[200px] overflow-hidden">
                                <a href="{{ route('vehicle.show', ['vehicle' => $kendaraan['slug']]) }}"
                                    class="block h-full">
                                    <img src="{{ $kendaraan['gambar'] }}"
                                        class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-110"
                                        alt="{{ ($kendaraan['merek'] ?? 'Merek Tidak Diketahui') . ' ' . ($kendaraan['nama'] ?? 'Model Tidak Diketahui') }}"
                                        loading="lazy"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <!-- Placeholder for broken image -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-gray-200 to-gray-300 hidden items-center justify-center">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 text-gray-500 mx-auto mb-3" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-gray-600 text-sm font-medium">Gambar Tidak Tersedia</p>
                                        </div>
                                    </div>
                                    <!-- Hover overlay -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pointer-events-none">
                                        <span
                                            class="bg-white text-gray-900 px-4 py-2 rounded-full text-sm font-semibold mb-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 shadow-sm">
                                            Lihat Detail
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <!-- Vehicle Info -->
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-lg text-gray-900 truncate mb-1">
                                            {{ $kendaraan['merek'] ?? 'Merek Tidak Diketahui' }}</h3>
                                        <p class="text-gray-600 text-sm truncate">
                                            {{ $kendaraan['nama'] ?? 'Model Tidak Diketahui' }}</p>
                                    </div>
                                    <!-- Remove Button -->
                                    <form action="{{ route('keranjang.remove', ['vehicleId' => $kendaraan['id']]) }}"
                                        method="POST" class="flex-shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus dari favorit"
                                            onclick="return confirm('Hapus kendaraan ini dari favorit?')"
                                            class="flex items-center justify-center w-10 h-10 bg-red-100 hover:bg-red-200 text-red-600 hover:text-red-700 rounded-full transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
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
                <div class="mt-12 text-center">
                    <p class="text-gray-600 text-lg font-medium">
                        Menampilkan {{ count($informasiKendaraan) }} kendaraan
                        favorit{{ count($informasiKendaraan) !== 1 ? 's' : '' }}
                    </p>
                </div>
            @else
                <!-- No Favorites -->
                <div class="text-center py-24">
                    <div class="max-w-lg mx-auto">
                        <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">Belum Ada Kendaraan Favorit</h3>
                        <p class="text-gray-600 text-lg mb-8 leading-relaxed">Mulai bangun koleksi kendaraan listrik
                            impian Anda dengan menjelajahi berbagai pilihan kami!</p>
                        <a href="{{ url('/') }}"
                            class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Jelajahi Kendaraan
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Footer --}}
    <x-slot:footer>
        <x-menu.footer :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" :recentVehicles="$recentVehicles" :popularVehicles="$popularVehicles"
            :featuredArticles="$stickies" />
    </x-slot>
</x-layouts.main>
