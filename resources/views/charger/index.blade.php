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



        <!-- Title -->
        <h1 class="text-5xl font-extrabold bg-black text-yellow-300 p-5 border-4 border-yellow-300 shadow-lg pop-in">
            ⚡ Cari EV Charger
        </h1>

        <!-- Form -->
        <form action="{{ route('charger.search') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-center pop-in">
            <input 
                type="text" 
                name="wilayah" 
                placeholder="Masukkan wilayah"
                value="{{ old('wilayah', $wilayah ?? '') }}"
                required
                class="bg-white text-black border-4 border-black px-4 py-3 w-full sm:w-auto placeholder-black shadow-md focus:outline-none focus:ring-4 focus:ring-pink-400 transition-all duration-300"
            >
            <button 
                type="submit" 
                class="bg-pink-500 text-white px-6 py-3 font-bold border-4 border-black shadow-lg hover:translate-x-1 hover:-translate-y-1 transition-all duration-300">
                🔍 Cari
            </button>
        </form>

        <!-- Result -->
        @if(isset($places))
            <div class="pop-in">
                <h2 class="text-2xl font-bold bg-black text-white px-5 py-3 border-l-8 border-pink-500 shadow-md">
                    Hasil untuk: <span class="text-pink-300 italic">{{ $wilayah }}</span>
                </h2>

                <div class="mt-6 max-h-[500px] overflow-y-auto pr-2 space-y-4">
                    @forelse ($places as $place)
                        <div class="bg-white border-4 border-black p-5 shadow-xl transition-transform duration-300 hover:translate-x-1 hover:-translate-y-1 pop-in">
                            <h3 class="text-xl font-extrabold text-blue-800">{{ $place['name'] }}</h3>
                            <p class="text-gray-700">{{ $place['vicinity'] ?? 'Alamat tidak tersedia' }}</p>
                
                            <!-- Menambahkan Rating jika tersedia -->
                            @if(isset($place['rating']))
                                <p class="text-yellow-500 font-semibold">Rating: {{ $place['rating'] }} ⭐</p>
                            @endif
                
                            @if(isset($place['opening_hours']['open_now']))
                                @if($place['opening_hours']['open_now'])
                                    <span class="inline-block px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                        Buka Sekarang
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 text-sm font-medium text-red-800 bg-red-100 rounded-full">
                                        Tutup Sekarang
                                    </span>
                                @endif
                            @else
                                <span class="inline-block px-3 py-1 text-sm font-medium text-gray-600 bg-gray-100 rounded-full">
                                    Status tidak tersedia
                                </span>
                            @endif
                
                            <a href="https://www.google.com/maps/place/?q=place_id:{{ $place['place_id'] }}"
                            target="_blank"
                            class="mt-3 inline-block bg-black text-white px-4 py-2 font-bold border-4 border-pink-500 hover:bg-pink-500 hover:text-black transition-all duration-300">
                                📍 Buka di Google Maps
                            </a>

                        </div>
                    @empty
                        <p class="bg-red-500 text-white font-bold px-4 py-3 border-4 border-black shadow-lg pop-in">
                            🚫 Tidak ada stasiun pengisian ditemukan.
                        </p>
                    @endforelse
                </div>


            </div>
        @endif

    </div>
</x-layouts.main>
