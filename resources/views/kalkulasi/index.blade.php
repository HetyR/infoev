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

    <!-- Main Content -->
    <section class="w-full min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">EV Cost Calculator</h1>
                            <p class="text-sm text-gray-600">Hitung estimasi biaya operasional kendaraan listrik Anda</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Calculator Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Parameter Kalkulasi</h2>
                        </div>
                        
                        <div class="p-6">
                            <form id="kalkulasiForm" class="space-y-6">
                                <!-- Vehicle Selection -->
                                <div class="space-y-2">
                                    <label for="vehicle" class="block text-sm font-medium text-gray-700">
                                        Model Kendaraan
                                    </label>
                                    <select id="vehicle" name="vehicle" required
                                        class="tom-select w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="">Pilih kendaraan listrik...</option>
                                        @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->brand->name }} {{ $vehicle->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Daily Driving -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Rata-rata Jarak Tempuh Harian
                                    </label>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs text-gray-500">1 KM</span>
                                            <span class="text-xs text-gray-500">300 KM</span>
                                        </div>
                                        <input type="range" 
                                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider-green" 
                                            min="1" max="300" id="rata_rata_berkendara" value="30">
                                        <div class="mt-3 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <span id="rata_rata_display">30 KM</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Electricity Price -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Tarif Listrik per kWh
                                    </label>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs text-gray-500">Rp 1.000</span>
                                            <span class="text-xs text-gray-500">Rp 2.600</span>
                                        </div>
                                        <input type="range" 
                                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider-green" 
                                            min="1000" max="2600" step="5" id="harga_listrik" value="1445">
                                        <div class="mt-3 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <span id="harga_listrik_display">Rp 1.445/kWh</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Calculate Button -->
                                <div class="pt-4">
                                    <button type="submit"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-md transition duration-200 flex items-center justify-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Hitung Biaya</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Info Panel -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Informasi</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Hemat Lingkungan</p>
                                    <p class="text-xs text-gray-600">Kendaraan listrik menghasilkan emisi nol saat berkendara</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Biaya Operasional Rendah</p>
                                    <p class="text-xs text-gray-600">Listrik lebih murah dibanding bahan bakar fosil</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Perawatan Minimal</p>
                                    <p class="text-xs text-gray-600">Motor listrik memiliki komponen bergerak yang lebih sedikit</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Average Costs -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Estimasi Rata-rata</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Motor listrik</span>
                                <span class="text-sm font-medium text-gray-900">Rp 500-800/hari</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Mobil kompak</span>
                                <span class="text-sm font-medium text-gray-900">Rp 2.000-4.000/hari</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-600">SUV listrik</span>
                                <span class="text-sm font-medium text-gray-900">Rp 5.000-8.000/hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div id="hasilKalkulasi" class="hidden mt-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Hasil Kalkulasi Biaya</span>
                        </h3>
                    </div>
                    <div class="p-6">
                        <div id="hasilDetail" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <style>
        .slider-green::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #10b981;
            cursor: pointer;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .slider-green::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #10b981;
            cursor: pointer;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .ts-wrapper.single .ts-control {
            border-radius: 6px;
            border: 1px solid #d1d5db;
            padding: 8px 12px;
            font-size: 14px;
        }

        .ts-wrapper.single .ts-control:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        .cost-card {
            transition: all 0.2s ease;
        }

        .cost-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
    </style>

    <script>
        // Initialize TomSelect
        new TomSelect("#vehicle", {
            placeholder: "Ketik untuk mencari kendaraan...",
            sortField: { field: "text", direction: "asc" },
            searchField: ['text'],
            maxOptions: null
        });

        // Range slider updates
        document.getElementById('rata_rata_berkendara').addEventListener('input', function () {
            document.getElementById('rata_rata_display').textContent = this.value + ' KM';
        });

        document.getElementById('harga_listrik').addEventListener('input', function () {
            const formatted = new Intl.NumberFormat('id-ID').format(this.value);
            document.getElementById('harga_listrik_display').textContent = 'Rp ' + formatted + '/kWh';
        });

        // Form submission
        document.getElementById('kalkulasiForm').addEventListener('submit', function (e) {
            e.preventDefault();
            
            const vehicleId = document.getElementById('vehicle').value;
            const rataRata = document.getElementById('rata_rata_berkendara').value;
            const listrik = document.getElementById('harga_listrik').value;

            if (!vehicleId) {
                alert('Silakan pilih kendaraan terlebih dahulu');
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Menghitung...
            `;

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
                submitBtn.innerHTML = originalText;
                
                if (data.success) {
                    const hasil = data.data.hasil;
                    const container = document.getElementById('hasilDetail');
                    document.getElementById('hasilKalkulasi').classList.remove('hidden');

                    // Format currency
                    const formatCurrency = (amount) => {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(amount);
                    };

                    container.innerHTML = `
                        <div class="cost-card bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-medium text-blue-700 uppercase tracking-wide">Per Kilometer</p>
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-xl font-bold text-blue-900">${formatCurrency(hasil.biaya_per_kilometer)}</p>
                        </div>
                        <div class="cost-card bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-medium text-green-700 uppercase tracking-wide">Per 100 KM</p>
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-xl font-bold text-green-900">${formatCurrency(hasil.biaya_per_100_kilometer)}</p>
                        </div>
                        <div class="cost-card bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-200">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-medium text-purple-700 uppercase tracking-wide">Isi Penuh</p>
                                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-xl font-bold text-purple-900">${formatCurrency(hasil.biaya_pengisian_penuh)}</p>
                        </div>
                        <div class="cost-card bg-gradient-to-br from-orange-50 to-orange-100 p-4 rounded-lg border border-orange-200">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-medium text-orange-700 uppercase tracking-wide">Harian</p>
                                <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-xl font-bold text-orange-900">${formatCurrency(hasil.biaya_harian)}</p>
                        </div>
                        <div class="cost-card bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-lg border border-indigo-200">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-medium text-indigo-700 uppercase tracking-wide">Bulanan</p>
                                <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-xl font-bold text-indigo-900">${formatCurrency(hasil.biaya_bulanan)}</p>
                        </div>
                        <div class="cost-card bg-gradient-to-br from-teal-50 to-teal-100 p-4 rounded-lg border border-teal-200">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-medium text-teal-700 uppercase tracking-wide">Jarak/Isi</p>
                                <svg class="w-4 h-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-xl font-bold text-teal-900">${hasil.jarak_tempuh_per_pengisian} KM</p>
                        </div>
                    `;

                    // Smooth scroll to results
                    document.getElementById('hasilKalkulasi').scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                } else {
                    alert(data.message || 'Terjadi kesalahan saat menghitung');
                }
            })
            .catch(err => {
                submitBtn.innerHTML = originalText;
                console.error(err);
                alert('Terjadi kesalahan saat menghitung. Silakan coba lagi.');
            });
        });
    </script>
</x-layouts.main>