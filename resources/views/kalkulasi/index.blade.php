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

    <section class="max-w-6xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-900 px-6 py-4">
                <h2 class="text-2xl font-bold text-white">Pilih Kendaraan untuk Kalkulasi</h2>
            </div>

            <div class="p-6 space-y-6">
                <form id="kalkulasiForm" class="space-y-6">
                    <div>
                        <label for="vehicle" class="block text-sm font-medium text-gray-700 mb-1">Model Kendaraan</label>
                        <select id="vehicle" name="vehicle" required
                            class="w-full border border-gray-300 rounded-md shadow-sm text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                            <option value="">Pilih Kendaraan</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}">{{ $vehicle->brand->name }} - {{ $vehicle->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="rata_rata_berkendara" class="block text-sm font-medium text-gray-700 mb-1">Rata-rata Berkendara per Hari</label>
                        <div class="flex items-center space-x-4">
                            <input type="range" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                   min="1" max="300" id="rata_rata_berkendara" value="30">
                            <div class="w-32">
                                <input type="text" id="rata_rata_display"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-right bg-gray-50"
                                       value="30 KM" readonly>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="harga_listrik" class="block text-sm font-medium text-gray-700 mb-1">Harga Listrik</label>
                        <div class="flex items-center space-x-4">
                            <input type="range" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                   min="1000" max="2600" step="5" id="harga_listrik" value="1445">
                            <div class="w-32">
                                <input type="text" id="harga_listrik_display"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md text-right bg-gray-50"
                                       value="Rp 1445 KWH" readonly>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded shadow-sm transition">
                            Hitung
                        </button>
                    </div>
                </form>

                <div id="hasilKalkulasi" class="hidden bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h4 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Hasil Kalkulasi</h4>
                    <div id="hasilDetail" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <script>
        // Tom Select init
        new TomSelect("#vehicle", {
            create: false,
            placeholder: "Ketik nama kendaraan...",
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        // Display updates
        document.getElementById('rata_rata_berkendara').addEventListener('input', function () {
            document.getElementById('rata_rata_display').value = this.value + ' KM';
        });

        document.getElementById('harga_listrik').addEventListener('input', function () {
            document.getElementById('harga_listrik_display').value = 'Rp ' + this.value + ' KWH';
        });

        // Kalkulasi AJAX
        document.getElementById('kalkulasiForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const vehicleId = document.getElementById('vehicle').value;
            const rataRata = document.getElementById('rata_rata_berkendara').value;
            const listrik = document.getElementById('harga_listrik').value;

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
                    if (data.success) {
                        const hasil = data.data.hasil;
                        const container = document.getElementById('hasilDetail');
                        document.getElementById('hasilKalkulasi').classList.remove('hidden');

                        container.innerHTML = `
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                <p class="text-sm text-gray-500">Biaya per KM</p>
                                <p class="text-lg font-semibold text-blue-600">Rp ${hasil.biaya_per_kilometer}</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                <p class="text-sm text-gray-500">Biaya per 100 KM</p>
                                <p class="text-lg font-semibold text-blue-600">Rp ${hasil.biaya_per_100_kilometer}</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                <p class="text-sm text-gray-500">Biaya Isi Penuh</p>
                                <p class="text-lg font-semibold text-blue-600">Rp ${hasil.biaya_pengisian_penuh}</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                <p class="text-sm text-gray-500">Biaya Harian</p>
                                <p class="text-lg font-semibold text-blue-600">Rp ${hasil.biaya_harian}</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                <p class="text-sm text-gray-500">Biaya Bulanan</p>
                                <p class="text-lg font-semibold text-blue-600">Rp ${hasil.biaya_bulanan}</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                                <p class="text-sm text-gray-500">Jarak Tempuh / Isi</p>
                                <p class="text-lg font-semibold text-blue-600">${hasil.jarak_tempuh_per_pengisian} KM</p>
                            </div>
                        `;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat menghitung.');
                });
        });
    </script>
</x-layouts.main>
