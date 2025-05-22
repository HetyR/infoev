<x-layouts.main>
    <x-slot:title>Kalkulasi Biaya Kendaraan Listrik - InfoEV</x-slot:title>

    <x-slot:meta>
        <meta name="description" content="Hitung biaya penggunaan kendaraan listrik berdasarkan model dan harga listrik terkini.">
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
        <x-menu.title-header :img="$banner" title="Kalkulasi Biaya Kendaraan Listrik" />
    @else
        <x-menu.title-header title="Kalkulasi Biaya Kendaraan Listrik" />
    @endif

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    EV Cost <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600">Calculator</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Hitung estimasi biaya operasional kendaraan listrik Anda dengan akurat. 
                    Bandingkan efisiensi dan hemat biaya dibanding kendaraan konvensional.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
                
                <!-- Calculator Form - Lebih prominent -->
                <div class="xl:col-span-3">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <!-- Header with gradient -->
                        <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 px-8 py-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-white">Parameter Kalkulasi</h2>
                                    <p class="text-emerald-100">Masukkan data untuk menghitung biaya operasional</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Content -->
                        <div class="p-8">
                            <form id="kalkulasiForm" class="space-y-8">
                                <!-- Vehicle Selection Card -->
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1V8a1 1 0 00-1-1h-3z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">Pilih Kendaraan Listrik</h3>
                                    </div>
                                    <select id="vehicle" name="vehicle" required
                                        class="tom-select w-full border-2 border-gray-200 rounded-xl shadow-sm px-4 py-3 text-base focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                        <option value="">🔍 Cari dan pilih kendaraan listrik...</option>
                                        @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->brand->name }} {{ $vehicle->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Daily Driving Card -->
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                                    <div class="flex items-center space-x-3 mb-6">
                                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">Rata-rata Jarak Tempuh</h3>
                                            <p class="text-sm text-gray-600">Berapa kilometer Anda berkendara setiap hari?</p>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between text-sm text-gray-500">
                                            <span class="flex items-center space-x-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span>1 KM</span>
                                            </span>
                                            <span class="flex items-center space-x-1">
                                                <span>300 KM</span>
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <input type="range" 
                                            class="w-full h-3 bg-blue-200 rounded-full appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                            min="1" max="300" id="rata_rata_berkendara" value="30">
                                        <div class="text-center">
                                            <div class="inline-flex items-center space-x-2 px-6 py-3 bg-white rounded-xl shadow-sm border border-blue-200">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                                </svg>
                                                <span id="rata_rata_display" class="text-xl font-bold text-blue-900">30 KM</span>
                                                <span class="text-sm text-blue-600">per hari</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Electricity Price Card -->
                                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-6 border border-amber-200">
                                    <div class="flex items-center space-x-3 mb-6">
                                        <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">Tarif Listrik PLN</h3>
                                            <p class="text-sm text-gray-600">Sesuaikan dengan tarif listrik di daerah Anda</p>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between text-sm text-gray-500">
                                            <span class="flex items-center space-x-1">
                                                <span>💡</span>
                                                <span>Rp 1.000/kWh</span>
                                            </span>
                                            <span class="flex items-center space-x-1">
                                                <span>Rp 2.600/kWh</span>
                                                <span>⚡</span>
                                            </span>
                                        </div>
                                        <input type="range" 
                                            class="w-full h-3 bg-amber-200 rounded-full appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-amber-500" 
                                            min="1000" max="2600" step="5" id="harga_listrik" value="1445">
                                        <div class="text-center">
                                            <div class="inline-flex items-center space-x-2 px-6 py-3 bg-white rounded-xl shadow-sm border border-amber-200">
                                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                <span id="harga_listrik_display" class="text-xl font-bold text-amber-900">Rp 1.445</span>
                                                <span class="text-sm text-amber-600">per kWh</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Calculate Button -->
                                <div class="pt-4">
                                    <button type="submit"
                                        class="w-full bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 hover:from-emerald-700 hover:via-teal-700 hover:to-cyan-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 flex items-center justify-center space-x-3 text-lg">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Hitung Biaya Sekarang</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"/>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="xl:col-span-1 space-y-6">
                    <!-- Quick Benefits -->
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-6 border border-emerald-200">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Keunggulan EV</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">🌱 Ramah Lingkungan</p>
                                    <p class="text-xs text-gray-600 mt-1">Zero emisi saat berkendara, kurangi polusi udara</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">💰 Hemat Biaya</p>
                                    <p class="text-xs text-gray-600 mt-1">Biaya operasional hingga 70% lebih murah</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">🔧 Minim Perawatan</p>
                                    <p class="text-xs text-gray-600 mt-1">Komponen lebih sederhana, perawatan lebih mudah</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">🔇 Minim Suara</p>
                                    <p class="text-xs text-gray-600 mt-1">Berkendara lebih tenang dan nyaman</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cost Estimates -->
                    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Estimasi Biaya</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <span class="text-lg">🏍️</span>
                                    <span class="text-sm text-gray-700">Motor Listrik</span>
                                </div>
                                <span class="text-sm font-semibold text-emerald-600">Rp 500-800/hari</span>
                            </div>
                            <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <span class="text-lg">🚗</span>
                                    <span class="text-sm text-gray-700">Mobil Kompak</span>
                                </div>
                                <span class="text-sm font-semibold text-blue-600">Rp 2-4rb/hari</span>
                            </div>
                            <div class="flex justify-between items-center py-2 px-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <span class="text-lg">🚙</span>
                                    <span class="text-sm text-gray-700">SUV Listrik</span>
                                </div>
                                <span class="text-sm font-semibold text-purple-600">Rp 5-8rb/hari</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tips Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Tips Hemat</h3>
                        </div>
                        <div class="space-y-3 text-sm text-gray-700">
                            <p class="flex items-start space-x-2">
                                <span class="text-blue-500 mt-0.5">💡</span>
                                <span>Isi daya saat tarif listrik murah (malam hari)</span>
                            </p>
                            <p class="flex items-start space-x-2">
                                <span class="text-green-500 mt-0.5">🌿</span>
                                <span>Gunakan mode eco untuk efisiensi maksimal</span>
                            </p>
                            <p class="flex items-start space-x-2">
                                <span class="text-purple-500 mt-0.5">🔋</span>
                                <span>Jaga baterai antara 20-80% untuk umur optimal</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div id="hasilKalkulasi" class="hidden mt-12">
                <div class="bg-gradient-to-br from-gray-50 via-white to-gray-50 rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 px-8 py-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-white">Hasil Kalkulasi Biaya</h3>
                                <p class="text-emerald-100">Estimasi biaya operasional kendaraan listrik Anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-8">
                        <div id="hasilDetail" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <script>
        // Initialize TomSelect with custom styling
        new TomSelect("#vehicle", {
            placeholder: "🔍 Ketik untuk mencari kendaraan...",
            sortField: { field: "text", direction: "asc" },
            searchField: ['text'],
            maxOptions: null,
            render: {
                option: function(data, escape) {
                    return '<div class="flex items-center space-x-3 py-2">' +
                           '<div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center flex-shrink-0">' +
                           '<svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">' +
                           '<path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>' +
                           '<path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1V8a1 1 0 00-1-1h-3z"/>' +
                           '</svg></div>' +
                           '<div class="flex-1"><div class="font-medium text-gray-900">' + escape(data.text) + '</div></div>' +
                           '</div>';
                }
            }
        });

        // Range slider updates with smooth animations
        document.getElementById('rata_rata_berkendara').addEventListener('input', function () {
            const value = this.value;
            const display = document.getElementById('rata_rata_display');
            
            // Add bounce animation
            display.parentElement.classList.add('transform', 'scale-105');
            setTimeout(() => {
                display.parentElement.classList.remove('transform', 'scale-105');
            }, 150);
            
            display.textContent = value + ' KM';
            
            // Update color based on usage
            const card = this.closest('.bg-gradient-to-br');
            if (value <= 50) {
                card.className = 'bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200';
            } else if (value <= 150) {
                card.className = 'bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200';
            } else {
                card.className = 'bg-gradient-to-br from-orange-50 to-red-50 rounded-xl p-6 border border-orange-200';
            }
        });

        document.getElementById('harga_listrik').addEventListener('input', function () {
            const value = this.value;
            const display = document.getElementById('harga_listrik_display');
            const formatted = new Intl.NumberFormat('id-ID').format(value);
            
            // Add bounce animation
            display.parentElement.classList.add('transform', 'scale-105');
            setTimeout(() => {
                display.parentElement.classList.remove('transform', 'scale-105');
            }, 150);
            
            display.textContent = 'Rp ' + formatted;
        });

        // Form submission with enhanced UX
        document.getElementById('kalkulasiForm').addEventListener('submit', function (e) {
            e.preventDefault();
            
            const vehicleId = document.getElementById('vehicle').value;
            const rataRata = document.getElementById('rata_rata_berkendara').value;
            const listrik = document.getElementById('harga_listrik').value;

            if (!vehicleId) {
                // Enhanced alert
                showNotification('⚠️ Silakan pilih kendaraan terlebih dahulu', 'warning');
                return;
            }

            // Enhanced loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <svg class="animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menghitung Biaya...</span>
            `;
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75');

            fetch(`/kalkulasi/hitung/${vehicleId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    rata_rata_berkendara: rataRata,
                    harga_listrik: listrik
                })
            })
            .then(res => res.json())
            .then(data => {
                // Restore button
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75');
                
                if (data.success) {
                    const hasil = data.data.hasil;
                    displayResults(hasil);
                    showNotification('✅ Kalkulasi berhasil!', 'success');
                } else {
                    showNotification('❌ ' + (data.message || 'Terjadi kesalahan saat menghitung'), 'error');
                }
            })
            .catch(err => {
                // Restore button
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75');
                
                console.error(err);
                showNotification('❌ Terjadi kesalahan jaringan. Silakan coba lagi.', 'error');
            });
        });

        // Display results with enhanced styling
        function displayResults(hasil) {
            const container = document.getElementById('hasilDetail');
            const hasilSection = document.getElementById('hasilKalkulasi');
            
            // Format currency
            const formatCurrency = (amount) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(amount);
            };

            const resultCards = [
                {
                    title: 'Biaya per KM',
                    value: formatCurrency(hasil.biaya_per_kilometer),
                    icon: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                           </svg>`,
                    gradient: 'from-blue-500 to-blue-600',
                    bgGradient: 'from-blue-50 to-blue-100'
                },
                {
                    title: 'Biaya per 100 KM',
                    value: formatCurrency(hasil.biaya_per_100_kilometer),
                    icon: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                           </svg>`,
                    gradient: 'from-emerald-500 to-emerald-600',
                    bgGradient: 'from-emerald-50 to-emerald-100'
                },
                {
                    title: 'Biaya Isi Penuh',
                    value: formatCurrency(hasil.biaya_pengisian_penuh),
                    icon: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                           </svg>`,
                    gradient: 'from-purple-500 to-purple-600',
                    bgGradient: 'from-purple-50 to-purple-100'
                },
                {
                    title: 'Biaya Harian',
                    value: formatCurrency(hasil.biaya_harian),
                    icon: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364c-.707-.707-1.414-1.414-2.12-2.12M6.05 6.05L4.93 4.93m12.728 12.728L16.243 16.243M6.05 17.95l-1.414 1.414"/>
                           </svg>`,
                    gradient: 'from-orange-500 to-orange-600',
                    bgGradient: 'from-orange-50 to-orange-100'
                },
                {
                    title: 'Biaya Bulanan',
                    value: formatCurrency(hasil.biaya_bulanan),
                    icon: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                           </svg>`,
                    gradient: 'from-indigo-500 to-indigo-600',
                    bgGradient: 'from-indigo-50 to-indigo-100'
                },
                {
                    title: 'Jarak per Isi',
                    value: hasil.jarak_tempuh_per_pengisian + ' KM',
                    icon: `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                           </svg>`,
                    gradient: 'from-teal-500 to-teal-600',
                    bgGradient: 'from-teal-50 to-teal-100'
                }
            ];

            container.innerHTML = resultCards.map(card => `
                <div class="bg-gradient-to-br ${card.bgGradient} rounded-2xl p-6 border border-gray-200 transform hover:scale-105 hover:shadow-xl transition-all duration-300 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-r ${card.gradient} rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                            ${card.icon}
                        </div>
                        <div class="w-8 h-1 bg-gradient-to-r ${card.gradient} rounded-full"></div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">${card.title}</p>
                        <p class="text-2xl font-bold text-gray-900">${card.value}</p>
                    </div>
                </div>
            `).join('');

            // Show results with animation
            hasilSection.classList.remove('hidden');
            hasilSection.classList.add('animate-pulse');
            setTimeout(() => {
                hasilSection.classList.remove('animate-pulse');
            }, 500);

            // Smooth scroll to results
            setTimeout(() => {
                hasilSection.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }, 200);
        }

        // Enhanced notification system
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existing = document.querySelector('.notification-toast');
            if (existing) existing.remove();

            const colors = {
                success: 'from-green-500 to-emerald-600',
                error: 'from-red-500 to-pink-600',
                warning: 'from-yellow-500 to-orange-600',
                info: 'from-blue-500 to-indigo-600'
            };

            const notification = document.createElement('div');
            notification.className = `notification-toast fixed top-4 right-4 z-50 max-w-sm bg-gradient-to-r ${colors[type]} text-white px-6 py-4 rounded-xl shadow-2xl transform translate-x-full transition-transform duration-300 ease-out`;
            notification.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="flex-1 text-sm font-medium">${message}</div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => notification.remove(), 300);
                }
            }, 5000);
        }

        // Add some interactive touches
        document.addEventListener('DOMContentLoaded', function() {
            // Add floating animation to hero elements
            const heroIcon = document.querySelector('.bg-gradient-to-br.from-emerald-500.to-teal-600');
            if (heroIcon) {
                setInterval(() => {
                    heroIcon.classList.add('animate-bounce');
                    setTimeout(() => {
                        heroIcon.classList.remove('animate-bounce');
                    }, 1000);
                }, 5000);
            }

            // Add parallax effect to gradient backgrounds
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const parallax = document.querySelector('.bg-gradient-to-br.from-emerald-50');
                if (parallax) {
                    parallax.style.transform = `translateY(${scrolled * 0.1}px)`;
                }
            });
        });
    </script>
</x-layouts.main>