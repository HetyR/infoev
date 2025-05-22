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
    <div class="bg-gray-50 min-h-screen">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-slate-50 to-gray-100 border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">Kalkulator Biaya EV</h1>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Hitung estimasi biaya operasional kendaraan listrik Anda dengan akurat berdasarkan data terkini</p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Calculator Section - 2 Kolom -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 mb-12">
                
                <!-- Form Section - 2 kolom dari 5 -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Header dengan gradient -->
                        <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Parameter Kalkulasi</h2>
                                    <p class="text-sm text-gray-600">Atur pengaturan untuk perhitungan</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <form id="kalkulasiForm" class="space-y-8">
                                <!-- Vehicle Selection -->
                                <div class="space-y-3">
                                    <label for="vehicle" class="block text-sm font-semibold text-gray-800">
                                        Pilih Kendaraan Listrik
                                    </label>
                                    <select id="vehicle" name="vehicle" required
                                        class="tom-select w-full border border-gray-300 rounded-lg shadow-sm px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                        <option value="">Pilih kendaraan listrik...</option>
                                        @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->brand->name }} {{ $vehicle->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Slider Harga Listrik -->
                                <div class="space-y-4">
                                    <label class="block text-sm font-semibold text-gray-800">
                                        Harga Listrik per kWh
                                    </label>
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                                        <div class="flex items-center justify-between text-xs text-gray-500 font-medium">
                                            <span>Rp 1.000</span>
                                            <span>Rp 2.600</span>
                                        </div>
                                        <div class="relative">
                                            <input type="range" 
                                                class="w-full h-3 bg-gray-200 rounded-full appearance-none cursor-pointer slider-thumb" 
                                                min="1000" max="2600" step="5" id="harga_listrik" value="1445">
                                        </div>
                                        <div class="text-center">
                                            <span class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                <span id="harga_listrik_display">Rp 1.445/kWh</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Slider Jarak Harian -->
                                <div class="space-y-4">
                                    <label class="block text-sm font-semibold text-gray-800">
                                        Jarak Tempuh Harian
                                    </label>
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                                        <div class="flex items-center justify-between text-xs text-gray-500 font-medium">
                                            <span>1 KM</span>
                                            <span>300 KM</span>
                                        </div>
                                        <div class="relative">
                                            <input type="range" 
                                                class="w-full h-3 bg-gray-200 rounded-full appearance-none cursor-pointer slider-thumb" 
                                                min="1" max="300" id="rata_rata_berkendara" value="30">
                                        </div>
                                        <div class="text-center">
                                            <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                                </svg>
                                                <span id="rata_rata_display">30 KM/hari</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Hitung -->
                                <div class="pt-2">
                                    <button type="submit"
                                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-4 px-6 rounded-lg transition-all duration-200 flex items-center justify-center space-x-3 shadow-md hover:shadow-lg transform hover:scale-105">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Hitung Biaya Sekarang</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Results Section - 3 kolom dari 5 -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-fit">
                        <!-- Header dengan gradient -->
                        <div class="px-6 py-5 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Hasil Kalkulasi</h2>
                                    <p class="text-sm text-gray-600">Estimasi biaya operasional kendaraan listrik</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <!-- Placeholder - ditampilkan sebelum ada hasil -->
                            <div id="placeholder" class="text-center py-12">
                                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-700 mb-3">Menunggu Input</h3>
                                <p class="text-gray-500 max-w-md mx-auto leading-relaxed">Pilih kendaraan listrik dan atur parameter kalkulasi, kemudian klik tombol "Hitung Biaya" untuk melihat estimasi biaya operasional</p>
                            </div>
                            
                            <!-- Results - ditampilkan setelah kalkulasi -->
                            <div id="hasilKalkulasi" class="hidden">
                                <div id="hasilDetail" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Section - Full Width di bawah -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Keunggulan EV -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-emerald-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center space-x-2">
                            <span class="text-xl">🌱</span>
                            <span>Keunggulan EV</span>
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="flex items-start space-x-4 p-3 bg-green-50 rounded-lg border border-green-100">
                            <span class="text-2xl">🌍</span>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Ramah Lingkungan</p>
                                <p class="text-sm text-gray-600">Zero emisi saat berkendara, kurangi polusi udara</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <span class="text-2xl">💰</span>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Hemat Biaya</p>
                                <p class="text-sm text-gray-600">Biaya operasional hingga 70% lebih murah</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 p-3 bg-purple-50 rounded-lg border border-purple-100">
                            <span class="text-2xl">🔧</span>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Minim Perawatan</p>
                                <p class="text-sm text-gray-600">Komponen lebih sederhana, perawatan lebih mudah</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                            <span class="text-2xl">🔇</span>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Minim Suara</p>
                                <p class="text-sm text-gray-600">Berkendara lebih tenang dan nyaman</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estimasi Biaya -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center space-x-2">
                            <span class="text-xl">💵</span>
                            <span>Estimasi Biaya</span>
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-100">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl">🏍️</span>
                                <span class="text-gray-800 font-medium">Motor Listrik</span>
                            </div>
                            <span class="font-bold text-green-600 text-lg">Rp 500-800/hari</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl">🚗</span>
                                <span class="text-gray-800 font-medium">Mobil Kompak</span>
                            </div>
                            <span class="font-bold text-blue-600 text-lg">Rp 2-4rb/hari</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg border border-purple-100">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl">🚙</span>
                                <span class="text-gray-800 font-medium">SUV Listrik</span>
                            </div>
                            <span class="font-bold text-purple-600 text-lg">Rp 5-8rb/hari</span>
                        </div>
                    </div>
                </div>

                <!-- Tips Hemat -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-orange-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center space-x-2">
                            <span class="text-xl">💡</span>
                            <span>Tips Hemat</span>
                        </h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="flex items-start space-x-4 p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                            <span class="text-2xl">🌙</span>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Waktu Isi Daya</p>
                                <p class="text-sm text-gray-600">Isi daya saat tarif listrik murah (malam hari)</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 p-3 bg-green-50 rounded-lg border border-green-100">
                            <span class="text-2xl">🌿</span>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Mode Eco</p>
                                <p class="text-sm text-gray-600">Gunakan mode eco untuk efisiensi maksimal</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <span class="text-2xl">🔋</span>
                            <div>
                                <p class="font-semibold text-gray-900 mb-1">Perawatan Baterai</p>
                                <p class="text-sm text-gray-600">Jaga baterai antara 20-80% untuk umur optimal</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS untuk slider -->
    <style>
        .slider-thumb::-webkit-slider-thumb {
            appearance: none;
            height: 24px;
            width: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            cursor: pointer;
            border: 3px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
        
        .slider-thumb::-webkit-slider-thumb:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .slider-thumb::-moz-range-thumb {
            height: 24px;
            width: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            cursor: pointer;
            border: 3px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
    </style>

    <!-- Scripts -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

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
            document.getElementById('rata_rata_display').textContent = this.value + ' KM/hari';
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
                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Menghitung...</span>
            `;
            submitBtn.disabled = true;

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
                submitBtn.disabled = false;
                
                if (data.success) {
                    const hasil = data.data.hasil;
                    displayResults(hasil);
                } else {
                    alert(data.message || 'Terjadi kesalahan saat menghitung');
                }
            })
            .catch(err => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                console.error(err);
                alert('Terjadi kesalahan saat menghitung. Silakan coba lagi.');
            });
        });

        // Display results
        function displayResults(hasil) {
            const container = document.getElementById('hasilDetail');
            const hasilSection = document.getElementById('hasilKalkulasi');
            const placeholder = document.getElementById('placeholder');
            
            // Format currency
            const formatCurrency = (amount) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(amount);
            };

            // Array hasil dengan desain yang lebih modern
            const results = [
                {
                    label: 'Biaya per KM',
                    value: formatCurrency(hasil.biaya_per_kilometer),
                    icon: '📏',
                    bgColor: 'bg-gradient-to-br from-blue-50 to-blue-100',
                    borderColor: 'border-blue-200',
                    textColor: 'text-blue-900',
                    valueColor: 'text-blue-700'
                },
                {
                    label: 'Biaya Harian',
                    value: formatCurrency(hasil.biaya_harian),
                    icon: '📅',
                    bgColor: 'bg-gradient-to-br from-green-50 to-green-100',
                    borderColor: 'border-green-200',
                    textColor: 'text-green-900',
                    valueColor: 'text-green-700'
                },
                {
                    label: 'Biaya per 100 KM',
                    value: formatCurrency(hasil.biaya_per_100_kilometer),
                    icon: '🛣️',
                    bgColor: 'bg-gradient-to-br from-purple-50 to-purple-100',
                    borderColor: 'border-purple-200',
                    textColor: 'text-purple-900',
                    valueColor: 'text-purple-700'
                },
                {
                    label: 'Biaya Isi Penuh',
                    value: formatCurrency(hasil.biaya_pengisian_penuh),
                    icon: '🔌',
                    bgColor: 'bg-gradient-to-br from-orange-50 to-orange-100',
                    borderColor: 'border-orange-200',
                    textColor: 'text-orange-900',
                    valueColor: 'text-orange-700'
                },
                {
                    label: 'Biaya Bulanan',
                    value: formatCurrency(hasil.biaya_bulanan),
                    icon: '📊',
                    bgColor: 'bg-gradient-to-br from-indigo-50 to-indigo-100',
                    borderColor: 'border-indigo-200',
                    textColor: 'text-indigo-900',
                    valueColor: 'text-indigo-700'
                },
                {
                    label: 'Jarak per Isi',
                    value: hasil.jarak_tempuh_per_pengisian + ' KM',
                    icon: '⚡',
                    bgColor: 'bg-gradient-to-br from-teal-50 to-teal-100',
                    borderColor: 'border-teal-200',
                    textColor: 'text-teal-900',
                    valueColor: 'text-teal-700'
                }
            ];

            // Generate HTML untuk hasil dengan desain yang lebih modern
            container.innerHTML = results.map(item => `
                <div class="${item.bgColor} ${item.borderColor} border rounded-xl p-5 hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">${item.icon}</span>
                            <span class="text-sm font-semibold ${item.textColor}">${item.label}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-bold ${item.valueColor}">${item.value}</span>
                    </div>
                </div>
            `).join('');

            // Show/hide sections dengan animasi
            placeholder.classList.add('hidden');
            hasilSection.classList.remove('hidden');
            
            // Animate results cards
            const cards = container.querySelectorAll('div');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }
    </script>
</x-layouts.main>