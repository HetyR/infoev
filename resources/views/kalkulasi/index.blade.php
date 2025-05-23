<x-layouts.main>
    <x-slot:title>Kalkulasi Biaya Kendaraan Listrik - InfoEV</x-slot:title>

    <x-slot:meta>
        <meta name="description" content="Hitung biaya penggunaan kendaraan listrik berdasarkan model dan harga listrik terkini.">
    </x-slot:meta>

    <x-slot:header>
        <x-menu.navbar :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" class="bg-white border-b border-gray-200 py-4" />
        <x-menu.menu class="bg-gray-50 py-2" />
    </x-slot:header>

    <x-slot:sidebar>
        <x-sidebar.brand-menu :bikeBrands="$bikeBrands" :carBrands="$carBrands" class="bg-white rounded-lg shadow-xs border border-gray-100 mb-4" />
        <x-sidebar.latest :recentVehicles="$recentVehicles" class="bg-white rounded-lg shadow-xs border border-gray-100 mb-4" />
        <x-sidebar.top :popularVehicles="$popularVehicles" class="bg-white rounded-lg shadow-xs border border-gray-100 mb-4" />
        <x-sidebar.featured :featuredArticles="$stickies" class="bg-white rounded-lg shadow-xs border border-gray-100" />
    </x-slot:sidebar>

    <x-slot:footer>
        <x-menu.footer :logo="$logo" class="bg-gray-800 text-white py-8" />
    </x-slot:footer>

    @if (isset($banner))
        <x-menu.title-header :img="$banner" title="Kalkulasi Biaya Kendaraan Listrik" class="bg-white border-b border-gray-200" />
    @else
        <x-menu.title-header title="Kalkulasi Biaya Kendaraan Listrik" class="bg-white border-b border-gray-200" />
    @endif

    <!-- Main Content -->
    <div class="bg-white min-h-screen">
        <!-- Hero Section -->
        <div class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center">
                    <h1 class="text-2xl font-semibold text-gray-900 mb-2 mt-4">Kalkulator Biaya EV</h1>
                    <p class="text-base text-gray-600 max-w-2xl mx-auto">Hitung estimasi biaya operasional kendaraan listrik Anda dengan akurat berdasarkan data terkini</p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Calculator Section - 2 Kolom -->
            <div class="flex flex-col lg:flex-row gap-6 mb-8">
                <!-- Form Section -->
                <div class="w-full lg:w-1/2 flex-shrink-0">
                    <div class="bg-white rounded-lg shadow-xs border border-gray-100">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">Parameter Kalkulasi</h2>
                                    <p class="text-sm text-gray-500">Atur pengaturan untuk perhitungan</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <form id="kalkulasiForm" class="space-y-6">
                                <!-- Vehicle Selection -->
                                <div class="space-y-2">
                                    <label for="vehicle" class="block text-sm font-medium text-gray-800">
                                        Pilih Kendaraan Listrik
                                    </label>
                                    <select id="vehicle" name="vehicle" required class="tom-select w-full border border-gray-200 rounded-lg shadow-xs px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white">
                                        <option value="">Pilih kendaraan listrik...</option>
                                        @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->brand->name }} {{ $vehicle->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Slider Jarak Harian -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-800">
                                        Jarak Tempuh Harian
                                    </label>
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                        <div class="flex items-center justify-between text-xs text-gray-500 font-medium">
                                            <span>1 KM</span>
                                            <span>300 KM</span>
                                        </div>
                                        <div class="flex items-center space-x-3 sm:space-x-4">
                                            <input type="range" class="w-full h-2 bg-gray-200 rounded-full appearance-none cursor-pointer slider-thumb" min="1" max="300" id="rata_rata_berkendara" value="30">
                                            <input type="number" class="w-20 sm:w-24 p-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" id="rata_rata_input" min="1" max="300" value="30">
                                        </div>
                                        <div class="text-center">
                                            <span class="inline-flex items-center px-3 py-1 bg-green-50 text-green-800 rounded-full text-sm font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                </svg>
                                                <span id="rata_rata_display">30 KM/hari</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Slider Harga Listrik -->
                                <div class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-800">
                                        Harga Listrik per kWh
                                    </label>
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                        <div class="flex items-center justify-between text-xs text-gray-500 font-medium">
                                            <span>Rp 1.000</span>
                                            <span>Rp 2.600</span>
                                        </div>
                                        <div class="flex items-center space-x-3 sm:space-x-4">
                                            <input type="range" class="w-full h-2 bg-gray-200 rounded-full appearance-none cursor-pointer slider-thumb" min="1000" max="2600" step="5" id="harga_listrik" value="1445">
                                            <input type="number" class="w-20 sm:w-24 p-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" id="harga_listrik_input" min="1000" max="2600" step="5" value="1445">
                                        </div>
                                        <div class="text-center">
                                            <span class="inline-flex items-center px-3 py-1 bg-green-50 text-green-800 rounded-full text-sm font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                                <span id="harga_listrik_display">Rp 1.445/kWh</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tombol Hitung -->
                                <div class="pt-2">
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 shadow-xs hover:shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span>Hitung Biaya Sekarang</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Results Section -->
                <div class="w-full lg:w-1/2 flex-shrink-0 min-h-fit mr-8">
                    <div class="bg-white rounded-lg shadow-xs border border-gray-100">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">Hasil Kalkulasi</h2>
                                    <p class="text-sm text-gray-500">Estimasi biaya operasional kendaraan listrik</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div id="placeholder" class="text-center py-10">
                                <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Menunggu Input</h3>
                                <p class="text-gray-500 max-w-md mx-auto text-sm">Pilih kendaraan listrik dan atur parameter kalkulasi, kemudian klik tombol "Hitung Biaya" untuk melihat estimasi biaya operasional</p>
                            </div>
                            <div id="hasilKalkulasi" class="hidden">
                                <div id="hasilDetail" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Information Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Keunggulan EV -->
                <div class="bg-white rounded-lg shadow-xs border border-gray-100">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                            <span class="text-base">🌱</span>
                            <span>Keunggulan EV</span>
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg border border-green-100">
                            <span class="text-base">🌍</span>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Ramah Lingkungan</p>
                                <p class="text-xs text-gray-500">Zero emisi saat berkendara, kurangi polusi udara</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg border border-green-100">
                            <span class="text-base">💰</span>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Hemat Biaya</p>
                                <p class="text-xs text-gray-500">Biaya operasional hingga 70% lebih murah</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg border border-green-100">
                            <span class="text-base">🔧</span>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Minim Perawatan</p>
                                <p class="text-xs text-gray-500">Komponen lebih sederhana, perawatan lebih mudah</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg border border-green-100">
                            <span class="text-base">🔇</span>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Minim Suara</p>
                                <p class="text-xs text-gray-500">Berkendara lebih tenang dan nyaman</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Estimasi Biaya -->
                <div class="bg-white rounded-lg shadow-xs border border-gray-100">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                            <span class="text-base">💵</span>
                            <span>Estimasi Biaya</span>
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                            <div class="flex items-center space-x-3">
                                <span class="text-base">🏍️</span>
                                <span class="text-gray-800 font-medium text-sm">Motor Listrik</span>
                            </div>
                            <span class="font-semibold text-green-600 text-sm">Rp 500-800/hari</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                            <div class="flex items-center space-x-3">
                                <span class="text-base">🚗</span>
                                <span class="text-gray-800 font-medium text-sm">Mobil Kompak</span>
                            </div>
                            <span class="font-semibold text-green-600 text-sm">Rp 2-4rb/hari</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                            <div class="flex items-center space-x-3">
                                <span class="text-base">🚙</span>
                                <span class="text-gray-800 font-medium text-sm">SUV Listrik</span>
                            </div>
                            <span class="font-semibold text-green-600 text-sm">Rp 5-8rb/hari</span>
                        </div>
                    </div>
                </div>
                <!-- Tips Hemat -->
                <div class="bg-white rounded-lg shadow-xs border border-gray-100">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2">
                            <span class="text-base">💡</span>
                            <span>Tips Hemat</span>
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg border border-green-100">
                            <span class="text-base">🌙</span>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Waktu Isi Daya</p>
                                <p class="text-xs text-gray-500">Isi daya saat tarif listrik murah (malam hari)</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg border border-green-100">
                            <span class="text-base">🌿</span>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Mode Eco</p>
                                <p class="text-xs text-gray-500">Gunakan mode eco untuk efisiensi maksimal</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg border border-green-100">
                            <span class="text-base">🔋</span>
                            <div>
                                <p class="font-medium text-gray-900 text-sm">Perawatan Baterai</p>
                                <p class="text-xs text-gray-500">Jaga baterai antara 20-80% untuk umur optimal</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <script>
        // Initialize TomSelect
        new TomSelect("#vehicle", {
            placeholder: "Ketik untuk mencari kendaraan...",
            sortField: {
                field: "text",
                direction: "asc"
            },
            searchField: ['text'],
            maxOptions: null
        });

        // Sinkronisasi Jarak Harian
        const rataRataSlider = document.getElementById('rata_rata_berkendara');
        const rataRataInput = document.getElementById('rata_rata_input');
        const rataRataDisplay = document.getElementById('rata_rata_display');

        rataRataSlider.addEventListener('input', function() {
            rataRataInput.value = this.value;
            rataRataDisplay.textContent = `${this.value} KM/hari`;
        });

        rataRataInput.addEventListener('input', function() {
            let value = parseInt(this.value);
            if (value < 1) value = 1;
            if (value > 300) value = 300;
            rataRataSlider.value = value;
            rataRataDisplay.textContent = `${value} KM/hari`;
        });

        // Sinkronisasi Harga Listrik
        const hargaListrikSlider = document.getElementById('harga_listrik');
        const hargaListrikInput = document.getElementById('harga_listrik_input');
        const hargaListrikDisplay = document.getElementById('harga_listrik_display');

        hargaListrikSlider.addEventListener('input', function() {
            hargaListrikInput.value = this.value;
            const formatted = new Intl.NumberFormat('id-ID').format(this.value);
            hargaListrikDisplay.textContent = `Rp ${formatted}/kWh`;
        });

        hargaListrikInput.addEventListener('input', function() {
            let value = parseInt(this.value);
            if (value < 1000) value = 1000;
            if (value > 2600) value = 2600;
            hargaListrikSlider.value = value;
            const formatted = new Intl.NumberFormat('id-ID').format(value);
            hargaListrikDisplay.textContent = `Rp ${formatted}/kWh`;
        });

        // Form submission
        document.getElementById('kalkulasiForm').addEventListener('submit', function(e) {
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
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
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
                const rounded = Math.round(amount);
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(rounded);
            };

            // Array hasil dengan desain yang lebih mirip GSMArena
            const results = [{
                label: 'Biaya per KM',
                value: formatCurrency(hasil.biaya_per_kilometer),
                icon: '📏',
                bgColor: 'bg-green-50',
                borderColor: 'border-green-100',
                textColor: 'text-gray-900',
                valueColor: 'text-green-600'
            }, {
                label: 'Biaya Harian',
                value: formatCurrency(hasil.biaya_harian),
                icon: '📅',
                bgColor: 'bg-green-50',
                borderColor: 'border-green-100',
                textColor: 'text-gray-900',
                valueColor: 'text-green-600'
            }, {
                label: 'Biaya per 100 KM',
                value: formatCurrency(hasil.biaya_per_100_kilometer),
                icon: '🛣️',
                bgColor: 'bg-green-50',
                borderColor: 'border-green-100',
                textColor: 'text-gray-900',
                valueColor: 'text-green-600'
            }, {
                label: 'Biaya Isi Penuh',
                value: formatCurrency(hasil.biaya_pengisian_penuh),
                icon: '🔌',
                bgColor: 'bg-green-50',
                borderColor: 'border-green-100',
                textColor: 'text-gray-900',
                valueColor: 'text-green-600'
            }, {
                label: 'Biaya Bulanan',
                value: formatCurrency(hasil.biaya_bulanan),
                icon: '📊',
                bgColor: 'bg-green-50',
                borderColor: 'border-green-100',
                textColor: 'text-gray-900',
                valueColor: 'text-green-600'
            }, {
                label: 'Jarak per Isi',
                value: hasil.jarak_tempuh_per_pengisian + ' KM',
                icon: '⚡',
                bgColor: 'bg-green-50',
                borderColor: 'border-green-100',
                textColor: 'text-gray-900',
                valueColor: 'text-green-600'
            }];

            // Generate HTML untuk hasil
            container.innerHTML = results.map(item => `
                <div class="${item.bgColor} ${item.borderColor} border rounded-lg p-4 hover:bg-green-100 transition-all duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-2">
                            <span class="text-base">${item.icon}</span>
                            <span class="text-sm font-medium ${item.textColor}">${item.label}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-base font-semibold ${item.valueColor}">${item.value}</span>
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
                card.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }
    </script>
</x-layouts.main>
```