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

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-center mb-8">Kalkulator Biaya Kendaraan Listrik</h1>

            <div id="ev-calculator" class="bg-white rounded-lg shadow-lg p-6">
                <div id="alert-error" class="hidden bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p id="error-message"></p>
                </div>

                <form id="calculator-form">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="vehicle-type" class="block text-gray-700 font-bold mb-2">Pilih Tipe Kendaraan</label>
                            <select id="vehicle-type" class="block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">-- Pilih Tipe --</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="vehicle-brand" class="block text-gray-700 font-bold mb-2">Pilih Brand</label>
                            <select id="vehicle-brand" class="block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" disabled required>
                                <option value="">-- Pilih Brand --</option>
                            </select>
                        </div>

                        <div>
                            <label for="vehicle-model" class="block text-gray-700 font-bold mb-2">Pilih Model Kendaraan</label>
                            <select id="vehicle-model" class="block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" disabled required>
                                <option value="">-- Pilih Model --</option>
                            </select>
                        </div>

                        <div>
                            <label for="daily-distance" class="block text-gray-700 font-bold mb-2">Jarak Tempuh Harian (km)</label>
                            <input type="number" id="daily-distance" class="block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="50" min="0" required />
                        </div>

                        <div>
                            <label for="electricity-price" class="block text-gray-700 font-bold mb-2">Harga Listrik (Rp/kWh)</label>
                            <input type="number" id="electricity-price" class="block w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="1445" min="0" required />
                        </div>
                    </div>

                    <div id="vehicle-specs" class="hidden mb-6 p-4 bg-gray-50 rounded-md">
                        <h3 class="font-bold mb-2">Spesifikasi Kendaraan</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <p class="text-sm text-gray-600">Model: <span id="spec-model"></span></p>
                                <p class="text-sm text-gray-600">Brand: <span id="spec-brand"></span></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Kapasitas Baterai: <span id="spec-battery"></span> kWh</p>
                                <p class="text-sm text-gray-600">Konsumsi Energi: <span id="spec-consumption"></span> kWh/100km</p>
                                <p class="text-sm text-gray-600">Jarak Tempuh Maksimal: <span id="spec-range"></span> km</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" id="calculate-button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md shadow-md transition duration-300" disabled>
                            Hitung Biaya
                        </button>
                    </div>
                </form>

                <div id="calculation-results" class="hidden mt-8 p-6 bg-blue-50 rounded-lg">
                    <h2 class="text-xl font-bold text-center mb-4">Hasil Kalkulasi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-4 rounded shadow-sm">
                            <h3 class="font-bold text-gray-800">Biaya Per Kilometer</h3>
                            <p id="cost-per-km" class="text-2xl font-bold text-blue-600"></p>
                        </div>
                        <div class="bg-white p-4 rounded shadow-sm">
                            <h3 class="font-bold text-gray-800">Biaya Per 100 Kilometer</h3>
                            <p id="cost-per-100km" class="text-2xl font-bold text-blue-600"></p>
                        </div>
                        <div class="bg-white p-4 rounded shadow-sm">
                            <h3 class="font-bold text-gray-800">Biaya Pengisian Penuh</h3>
                            <p id="full-charge-cost" class="text-2xl font-bold text-blue-600"></p>
                        </div>
                        <div class="bg-white p-4 rounded shadow-sm">
                            <h3 class="font-bold text-gray-800">Biaya Harian</h3>
                            <p id="daily-cost" class="text-2xl font-bold text-blue-600"></p>
                        </div>
                        <div class="bg-white p-4 rounded shadow-sm">
                            <h3 class="font-bold text-gray-800">Biaya Bulanan</h3>
                            <p id="monthly-cost" class="text-2xl font-bold text-blue-600"></p>
                        </div>
                        <div class="bg-white p-4 rounded shadow-sm">
                            <h3 class="font-bold text-gray-800">Jarak Tempuh Per Pengisian</h3>
                            <p id="range-per-charge" class="text-2xl font-bold text-blue-600"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const vehicleTypeSelect = document.getElementById('vehicle-type');
    const vehicleBrandSelect = document.getElementById('vehicle-brand');
    const vehicleModelSelect = document.getElementById('vehicle-model');
    const dailyDistanceInput = document.getElementById('daily-distance');
    const electricityPriceInput = document.getElementById('electricity-price');
    const calculateButton = document.getElementById('calculate-button');
    const calculatorForm = document.getElementById('calculator-form');
    const alertError = document.getElementById('alert-error');
    const errorMessage = document.getElementById('error-message');
    const vehicleSpecs = document.getElementById('vehicle-specs');
    const calculationResults = document.getElementById('calculation-results');

    // ketika ganti tipe kendaraan
    vehicleTypeSelect.addEventListener('change', function() {
        const typeId = this.value;
        vehicleBrandSelect.innerHTML = '<option value="">-- Pilih Brand --</option>';
        vehicleBrandSelect.disabled = !typeId;
        vehicleModelSelect.innerHTML = '<option value="">-- Pilih Model --</option>';
        vehicleModelSelect.disabled = true;
        calculateButton.disabled = true;
        vehicleSpecs.classList.add('hidden');
        calculationResults.classList.add('hidden');

        if (!typeId) return;

        // cari type yang dipilih
        const selectedType = dataTypes.find(type => type.id == typeId);
        if (!selectedType) return;

        // isi brand berdasarkan type
        if (selectedType.brands && selectedType.brands.length > 0) {
            selectedType.brands.forEach(brand => {
                const option = document.createElement('option');
                option.value = brand.id;
                option.textContent = brand.name;
                vehicleBrandSelect.appendChild(option);
            });
        }
    });

    // ketika ganti brand
    vehicleBrandSelect.addEventListener('change', function() {
        const brandId = this.value;
        vehicleModelSelect.innerHTML = '<option value="">-- Pilih Model --</option>';
        vehicleModelSelect.disabled = !brandId;
        calculateButton.disabled = true;
        vehicleSpecs.classList.add('hidden');
        calculationResults.classList.add('hidden');

        if (!brandId) return;

        // cari brand di dalam semua types
        let selectedBrand = null;
        for (const type of dataTypes) {
            if (type.brands) {
                selectedBrand = type.brands.find(b => b.id == brandId);
                if (selectedBrand) break;
            }
        }
        if (!selectedBrand) return;

        // isi model kendaraan dari brand
        if (selectedBrand.vehicles && selectedBrand.vehicles.length > 0) {
            selectedBrand.vehicles.forEach(vehicle => {
                const option = document.createElement('option');
                option.value = vehicle.id;
                option.textContent = vehicle.name;
                vehicleModelSelect.appendChild(option);
            });
        }
    });

    // ketika ganti model
    vehicleModelSelect.addEventListener('change', function() {
        const vehicleId = this.value;
        calculateButton.disabled = !vehicleId;
        vehicleSpecs.classList.add('hidden');
        calculationResults.classList.add('hidden');

        if (!vehicleId) return;

        // cari spesifikasi kendaraan di semua vehicles
        let selectedVehicle = null;
        outerLoop:
        for (const type of dataTypes) {
            if (type.brands) {
                for (const brand of type.brands) {
                    if (brand.vehicles) {
                        selectedVehicle = brand.vehicles.find(v => v.id == vehicleId);
                        if (selectedVehicle) break outerLoop;
                    }
                }
            }
        }
        if (!selectedVehicle) {
            showError('Data spesifikasi kendaraan tidak ditemukan.');
            return;
        }

        // tampilkan spesifikasi kendaraan
        document.getElementById('spec-model').textContent = selectedVehicle.name;
        document.getElementById('spec-brand').textContent = selectedVehicle.brand_name || selectedVehicle.brand?.name || 'N/A';
        document.getElementById('spec-battery').textContent = selectedVehicle.battery_capacity ?? 'N/A';
        document.getElementById('spec-consumption').textContent = selectedVehicle.energy_consumption ?? 'N/A';
        document.getElementById('spec-range').textContent = selectedVehicle.max_range ?? 'N/A';
        vehicleSpecs.classList.remove('hidden');
    });

    // submit form kalkulasi tanpa fetch, kalkulasi langsung di frontend
    calculatorForm.addEventListener('submit', function(e) {
        e.preventDefault();
        alertError.classList.add('hidden');
        calculationResults.classList.add('hidden');

        const vehicleId = vehicleModelSelect.value;
        const dailyDistance = Number(dailyDistanceInput.value);
        const electricityPrice = Number(electricityPriceInput.value);

        if (!vehicleId || dailyDistance < 0 || electricityPrice < 0) {
            showError('Mohon lengkapi form dengan benar.');
            return;
        }

        // cari kendaraan
        let vehicle = null;
        outerLoop:
        for (const type of dataTypes) {
            if (type.brands) {
                for (const brand of type.brands) {
                    if (brand.vehicles) {
                        vehicle = brand.vehicles.find(v => v.id == vehicleId);
                        if (vehicle) break outerLoop;
                    }
                }
            }
        }
        if (!vehicle) {
            showError('Kendaraan tidak ditemukan.');
            return;
        }

        // Kalkulasi biaya
        // Asumsi rumus sederhana (sesuaikan dengan logika kamu)
        // cost_per_km = (energy_consumption / 100) * electricity_price
        const consumption = vehicle.energy_consumption ?? 0; // kWh/100km
        const battery = vehicle.battery_capacity ?? 0; // kWh
        const maxRange = vehicle.max_range ?? 0; // km

        const costPerKm = (consumption / 100) * electricityPrice;
        const costPer100km = costPerKm * 100;
        const fullChargeCost = battery * electricityPrice;
        const dailyCost = costPerKm * dailyDistance;
        const monthlyCost = dailyCost * 30;
        const rangePerCharge = maxRange;

        // tampilkan hasil
        document.getElementById('cost-per-km').textContent = `Rp ${formatNumber(costPerKm)}`;
        document.getElementById('cost-per-100km').textContent = `Rp ${formatNumber(costPer100km)}`;
        document.getElementById('full-charge-cost').textContent = `Rp ${formatNumber(fullChargeCost)}`;
        document.getElementById('daily-cost').textContent = `Rp ${formatNumber(dailyCost)}`;
        document.getElementById('monthly-cost').textContent = `Rp ${formatNumber(monthlyCost)}`;
        document.getElementById('range-per-charge').textContent = `${formatNumber(rangePerCharge)} km`;

        calculationResults.classList.remove('hidden');
        calculationResults.scrollIntoView({ behavior: 'smooth' });
    });

    function showError(msg) {
        alertError.classList.remove('hidden');
        errorMessage.textContent = msg;
    }

    function formatNumber(num) {
        return num.toLocaleString('id-ID', { maximumFractionDigits: 0 });
    }
});

    </script>
    @endpush

</x-layouts.main>
