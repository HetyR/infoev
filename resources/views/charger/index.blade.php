<x-layouts.main>
    <x-slot:title>Charger Station - InfoEV</x-slot:title>

    <x-slot:meta>
        <meta name="description" content="Temukan informasi lengkap dan kalkulasi terkait penggunaan charger station untuk kendaraan listrik.">
    </x-slot:meta>

    <x-slot:header>
        <x-menu.navbar :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
        <x-menu.menu />
    </x-slot:header>

    <x-slot:sidebar>
        <x-sidebar.brand-menu :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
        <x-sidebar.latest :recentVehicles="$recentVehicles" />
        <x-sidebar.top :popularVehicles="$popularVehicles" />
        <x-sidebar.featured :featuredArticles="$stickies" />
    </x-slot:sidebar>

    <x-slot:footer>
        <x-menu.footer :logo="$logo" />
    </x-slot:footer>

    @if (isset($banner))
        <x-menu.title-header :img="$banner" title="Informasi Charger Station" />
    @else
        <x-menu.title-header title="Informasi Charger Station" />
    @endif

    <div class="bg-gray-100 min-h-screen">
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="text-center">
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Lokasi Charging Station</h1>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                        Temukan stasiun pengisian kendaraan listrik terdekat dan hitung estimasi biaya berdasarkan lokasi serta harga listrik terkini.
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">🔌 Cari Stasiun Pengisian EV</h2>

            <form action="{{ route('charger.search') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-center justify-center mb-8">
                <input 
                    type="text" 
                    name="wilayah" 
                    placeholder="Masukkan wilayah (contoh: Jakarta)"
                    value="{{ old('wilayah', $wilayah ?? '') }}"
                    required
                    class="w-full sm:w-96 px-4 py-3 text-gray-900 bg-white border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                >
                <button 
                    type="submit" 
                    class="bg-blue-600 text-white px-6 py-3 font-semibold shadow-md hover:bg-blue-700 hover:shadow-lg transition-all duration-200">
                    Cari
                </button>
            </form>

            @if(isset($places))
                <div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        Hasil untuk: <span class="text-blue-600 italic">{{ $wilayah }}</span>
                    </h3>

                    <div id="map" class="w-full h-[500px] shadow-md mb-10"></div>

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse ($places as $place)
                            <div class="bg-white border border-gray-200 shadow-md p-6 hover:shadow-lg transition-all duration-200">
                                <h4 class="text-lg font-bold text-blue-800 mb-2">{{ $place['name'] }}</h4>
                                <p class="text-gray-600 text-sm mb-3">{{ $place['vicinity'] ?? 'Alamat tidak tersedia' }}</p>

                                @if(isset($place['rating']))
                                    <p class="text-yellow-500 text-sm font-medium mb-2">Rating: {{ $place['rating'] }} ⭐</p>
                                @endif

                                @if(isset($place['opening_hours']['open_now']))
                                    <span class="inline-block px-3 py-1 text-xs font-medium {{ $place['opening_hours']['open_now'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $place['opening_hours']['open_now'] ? 'Buka Sekarang' : 'Tutup Sekarang' }}
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100">
                                        Status tidak tersedia
                                    </span>
                                @endif

                                <a href="https://www.google.com/maps/place/?q=place_id:{{ $place['place_id'] }}"
                                   target="_blank"
                                   class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 text-sm font-semibold hover:bg-blue-700 transition-all duration-200">
                                    📍 Buka di Google Maps
                                </a>
                            </div>
                        @empty
                            <p class="bg-red-100 text-red-800 font-semibold px-4 py-3 text-center">
                                🚫 Tidak ada stasiun pengisian ditemukan.
                            </p>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    {{-- Leaflet CSS --}}
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    @endpush

    {{-- Leaflet JS --}}
    @push('scripts')
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const map = L.map('map').setView([-6.200000, 106.816666], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                @if(isset($places))
                    @foreach($places as $place)
                        @if(isset($place['geometry']['location']))
                            L.marker([{{ $place['geometry']['location']['lat'] }}, {{ $place['geometry']['location']['lng'] }}])
                                .addTo(map)
                                .bindPopup(`<b>{{ $place['name'] }}</b><br>{{ $place['vicinity'] ?? 'Alamat tidak tersedia' }}`);
                        @endif
                    @endforeach
                @endif
            });
        </script>
    @endpush
</x-layouts.main>
