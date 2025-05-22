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

    <!-- Main Content -->
    <div class="bg-gray-50 min-h-screen">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-slate-50 to-gray-100 border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-900 mb-3 mt-4">Lokasi Charging Station</h1>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Hitung estimasi biaya penggunaan charger station untuk kendaraan listrik Anda berdasarkan lokasi dan harga listrik terkini.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <body class="bg-white min-h-screen px-4 py-8 text-gray-900">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-4xl font-bold mb-8 text-center">🔌 Stasiun Pengisian EV Terdekat</h1>

        <!-- Form Pencarian -->
        <form method="GET" action="{{ route('charging.stations') }}" class="flex flex-col sm:flex-row gap-4 mb-10">
            <input type="text" name="wilayah" placeholder="Masukkan nama wilayah/kota (misal: Jakarta)"
                   class="w-full p-3 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400"
                   required>
            <button type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-3 rounded-lg transition">
                Cari Lokasi
            </button>
        </form>

        <!-- Daftar Stasiun -->
        <div class="space-y-6">
            @if(isset($data) && count($data))
                @foreach($data as $index => $station)
                    <div class="bg-yellow-50 border border-yellow-500 rounded-xl p-5 shadow-md hover:shadow-lg transition">
                        <div class="flex items-start justify-between flex-col sm:flex-row">
                            <div class="space-y-2">
                                <h2 class="text-xl font-semibold text-yellow-700">{{ $station['AddressInfo']['Title'] ?? '-' }}</h2>
                                <p class="text-gray-800">{{ $station['AddressInfo']['AddressLine1'] ?? '-' }}</p>
                                <p class="text-sm text-gray-600">{{ $station['AddressInfo']['Town'] ?? '' }}, {{ $station['AddressInfo']['Country']['Title'] ?? '' }}</p>
                                <p class="text-sm"><strong>Status:</strong> {{ $station['StatusType']['Title'] ?? 'Tidak diketahui' }}</p>
                            </div>
                            <a href="https://maps.google.com/?q={{ $station['AddressInfo']['Latitude'] }},{{ $station['AddressInfo']['Longitude'] }}"
                               target="_blank"
                               class="mt-4 sm:mt-0 bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg transition">
                                Lihat di Google Maps
                            </a>
                        </div>

                        <div class="mt-4 text-sm text-gray-700 space-y-2">
                            <p><strong>Operator:</strong> {{ $station['OperatorInfo']['Title'] ?? '-' }}</p>
                            <p><strong>Jam Buka:</strong>
                                @if(isset($station['AddressInfo']['OpeningTimes']) && !empty($station['AddressInfo']['OpeningTimes']))
                                    {{ $station['AddressInfo']['OpeningTimes'] }}
                                @else
                                    Tidak Tersedia
                                @endif
                            </p>
                            <p><strong>Jumlah Konektor:</strong> {{ count($station['Connections']) }}</p>

                            @foreach($station['Connections'] as $connIndex => $connection)
                                <div class="pl-4 border-l-4 border-yellow-500 ml-2 mt-2">
                                    <p class="font-medium">Konektor #{{ $connIndex + 1 }}</p>
                                    <ul class="list-disc pl-5 text-sm">
                                        <li><strong>Tipe:</strong> {{ $connection['ConnectionType']['Title'] ?? '-' }}</li>
                                        <li><strong>Status:</strong> {{ $connection['StatusType']['Title'] ?? '-' }}</li>
                                        <li><strong>Daya:</strong> {{ $connection['PowerKW'] ?? '-' }} kW</li>
                                    </ul>
                                </div>
                            @endforeach
                        </div>

                        @if(!empty($station['AddressInfo']['ContactTelephone1']) || !empty($station['AddressInfo']['ContactEmail']))
                            <div class="mt-4">
                                <p class="font-medium">Kontak:</p>
                                <ul class="list-disc ml-5 text-sm">
                                    @if(!empty($station['AddressInfo']['ContactTelephone1']))
                                        <li>Telepon: {{ $station['AddressInfo']['ContactTelephone1'] }}</li>
                                    @endif
                                    @if(!empty($station['AddressInfo']['ContactEmail']))
                                        <li>Email: {{ $station['AddressInfo']['ContactEmail'] }}</li>
                                    @endif
                                </ul>
                            </div>
                        @endif

                        <p class="mt-2 text-xs text-gray-500 italic">
                            Terakhir diperbarui: {{ \Carbon\Carbon::parse($station['DateLastStatusUpdate'])->translatedFormat('d F Y H:i') }}
                        </p>
                    </div>
                @endforeach
            @else
                <p class="text-center text-gray-600">Tidak ada data ditemukan untuk wilayah tersebut.</p>
            @endif
        </div>
    </div>
</x-layouts.main>
