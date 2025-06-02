<x-layouts.main>
    <x-slot:title>Charger Station - InfoEV</x-slot:title>

    <x-slot:meta>
        <meta name="description"
            content="Temukan informasi lengkap dan kalkulasi terkait penggunaan charger station untuk kendaraan listrik.">
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

    <div class="bg-gray-50 min-h-screen">
        <!-- Bagian Header -->
        <div class="bg-white border-b">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Lokasi Charging Station</h1>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Temukan stasiun pengisian kendaraan listrik terdekat dengan mudah dan cepat
                    </p>
                </div>
            </div>
        </div>

        <!-- Bagian Pencarian -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white shadow-sm border p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">🔍 Cari Stasiun Pengisian EV</h2>

                <form action="{{ route('charger.search') }}" method="GET"
                    class="flex flex-col sm:flex-row gap-4 items-center justify-center">
                    <input type="text" name="wilayah" placeholder="Masukkan wilayah (contoh: Jakarta)"
                        value="{{ old('wilayah', $wilayah ?? '') }}" required
                        class="w-full sm:w-80 px-4 py-3 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        style="focus:ring-color: #630A8C;">
                    <button type="submit"
                        class="px-6 py-3 font-medium text-white hover:opacity-90 transition-opacity"
                        style="background-color: #630A8C;">
                        Cari
                    </button>
                </form>
            </div>
        </div>

        @if (isset($places))
            <!-- Konten Utama - Layout Terpisah -->
            <div class="flex h-screen max-h-[700px]">
                <!-- Sisi Kiri - Daftar Stasiun -->
                <div class="w-1/2 bg-white shadow-sm border overflow-hidden">
                    <div class="p-4 border-b bg-gray-50">
                        <h4 class="font-semibold text-gray-800">
                            Hasil untuk: <span class="font-bold" style="color: #630A8C;">{{ $wilayah }}</span>
                        </h4>
                    </div>
                    <div class="overflow-y-auto h-full pb-16">
                        <div class="p-4 space-y-4">
                            @forelse ($places as $place)
                                <div class="bg-gray-50 border p-4 hover:shadow-md transition-shadow cursor-pointer station-item"
                                     data-lat="{{ $place['geometry']['location']['lat'] ?? '' }}"
                                     data-lng="{{ $place['geometry']['location']['lng'] ?? '' }}">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $place['name'] }}</h4>
                                    <p class="text-gray-600 text-sm mb-3">{{ $place['vicinity'] }}</p>

                                    @if (isset($place['rating']))
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span class="text-sm"
                                                        style="color: {{ $i <= floor($place['rating']) ? '#FE7E00' : '#d1d5db' }};">★</span>
                                                @endfor
                                            </div>
                                            <span class="text-sm text-gray-600">{{ $place['rating'] }}</span>
                                        </div>
                                    @endif

                                    @if (isset($place['opening_hours']['open_now']))
                                        <div class="mb-4">
                                            <span
                                                class="inline-block px-3 py-1 text-xs font-medium {{ $place['opening_hours']['open_now'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $place['opening_hours']['open_now'] ? 'Buka Sekarang' : 'Tutup Sekarang' }}
                                            </span>
                                        </div>
                                    @else
                                        <div class="mb-4">
                                            <span
                                                class="inline-block px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100">
                                                Status tidak tersedia
                                            </span>
                                        </div>
                                    @endif

                                    <a href="https://www.google.com/maps/place/?q=place_id:{{ $place['place_id'] }}"
                                        target="_blank"
                                        class="inline-block w-full text-center px-4 py-2 text-sm font-medium text-white hover:opacity-90 transition-opacity"
                                        style="background-color: #FE7E00;">
                                        📍 Buka di Google Maps
                                    </a>
                                </div>
                            @empty
                                <div class="bg-red-50 border border-red-200 p-6 text-center">
                                    <p class="text-red-800 font-medium">🚫 Tidak ada stasiun pengisan ditemukan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Right Side - Map (Full without card) -->
                <div class="w-1/2 relative">
                    <div id="map" class="w-full h-full"></div>
                    <!-- Optional: Map overlay controls -->
                    <div class="absolute top-4 left-4 bg-white shadow-lg px-3 py-2 z-[1000]">
                        <h4 class="font-semibold text-gray-800 text-sm">Peta Lokasi</h4>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            .leaflet-popup-content-wrapper {
                border-radius: 8px;
            }
            .station-item:hover {
                background-color: #f3f4f6;
            }
            .station-item.active {
                background-color: #e5e7eb;
                border-color: #630A8C;
                border-width: 2px;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const map = L.map('map').setView([-7.830843, 112.0098111], 13);
                const markers = [];

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                // Custom icon
                const customIcon = L.divIcon({
                    html: `
                        <div style="
                            width: 40px; 
                            height: 40px; 
                            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e40af 100%);
                            border: 3px solid white;
                            border-radius: 50% 50% 50% 0;
                            transform: rotate(-45deg);
                            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            position: relative;
                        ">
                            <div style="
                                color: white;
                                font-size: 16px;
                                transform: rotate(45deg);
                                font-weight: bold;
                            ">⚡</div>
                        </div>
                    `,
                    className: 'custom-charging-marker',
                    iconSize: [40, 40],
                    iconAnchor: [20, 40],
                    popupAnchor: [0, -40]
                });

                @if (isset($places))
                    @foreach ($places as $index => $place)
                        @if (isset($place['geometry']['location']['lat']) && isset($place['geometry']['location']['lng']))
                            const marker{{ $index }} = L.marker([{{ $place['geometry']['location']['lat'] }},
                                    {{ $place['geometry']['location']['lng'] }}
                                ], {
                                    icon: customIcon
                                })
                                .addTo(map)
                                .bindPopup(`
                                <div style="min-width: 200px;">
                                    <h4 style="font-weight: bold; margin-bottom: 8px; color: #630A8C;">{{ addslashes($place['name']) }}</h4>
                                    <p style="color: #666; font-size: 14px; margin-bottom: 12px;">{{ addslashes($place['vicinity']) }}</p>
                                    @if (isset($place['rating']))
                                        <div style="margin-bottom: 12px;">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <span style="color: {{ $i <= floor($place['rating']) ? '#FE7E00' : '#ddd' }};">★</span>
                                            @endfor
                                            <span style="margin-left: 4px; color: #666; font-size: 14px;">{{ $place['rating'] }}</span>
                                        </div>
                                    @endif
                                    <a href="https://www.google.com/maps/place/?q=place_id:{{ $place['place_id'] }}" target="_blank" 
                                       style="display: inline-block; padding: 6px 12px; background-color: #FE7E00; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;">
                                        Buka di Google Maps
                                    </a>
                                </div>
                            `);
                            markers.push(marker{{ $index }});
                        @endif
                    @endforeach

                    // Fit bounds to show all markers
                    @if (
                        !empty($places) &&
                            count(array_filter(
                                    $places,
                                    fn($place) => isset($place['geometry']['location']['lat']) &&
                                        isset($place['geometry']['location']['lng']))) > 0)
                        const bounds = [
                            @foreach ($places as $place)
                                @if (isset($place['geometry']['location']['lat']) && isset($place['geometry']['location']['lng']))
                                    [{{ $place['geometry']['location']['lat'] }},
                                        {{ $place['geometry']['location']['lng'] }}
                                    ],
                                @endif
                            @endforeach
                        ];
                        map.fitBounds(bounds, {
                            padding: [20, 20]
                        });
                    @endif
                @endif

                // Add click functionality to station items
                document.querySelectorAll('.station-item').forEach((item, index) => {
                    item.addEventListener('click', function() {
                        const lat = parseFloat(this.dataset.lat);
                        const lng = parseFloat(this.dataset.lng);
                        
                        if (lat && lng && markers[index]) {
                            // Remove active class from all items
                            document.querySelectorAll('.station-item').forEach(el => el.classList.remove('active'));
                            // Add active class to clicked item
                            this.classList.add('active');
                            
                            // Center map on marker and open popup
                            map.setView([lat, lng], 16);
                            markers[index].openPopup();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layouts.main>