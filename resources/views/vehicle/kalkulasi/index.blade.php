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

    @if (isset($banner))
        <x-menu.title-header :img="$banner" title="Kalkulasi Biaya Kendaraan Listrik" />
    @else
        <x-menu.title-header title="Kalkulasi Biaya Kendaraan Listrik" />
    @endif

    {{-- Konten Form Kalkulasi --}}
    <div class="mt-4 px-3 md:px-6 max-w-2xl">
        <form method="POST" action="{{ route('vehicle.process-kalkulasi') }}" class="bg-white p-6 rounded shadow">
            @csrf

            {{-- Jenis Kendaraan --}}
            <div class="mb-4">
                <label class="block text-sm font-bold mb-1">Jenis Kendaraan</label>
                <select id="jenis_kendaraan" class="w-full border px-3 py-2" name="jenis_kendaraan" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Mobil" {{ old('jenis_kendaraan') == 'Mobil' ? 'selected' : '' }}>Mobil Listrik</option>
                    <option value="Motor" {{ old('jenis_kendaraan') == 'Motor' ? 'selected' : '' }}>Motor Listrik</option>
                </select>
            </div>

            {{-- Model Kendaraan --}}
            <div class="mb-4">
                <label class="block text-sm font-bold mb-1">Model Kendaraan</label>
                <select name="vehicle_id" id="vehicle_id" class="w-full border px-3 py-2" required>
                    <option value="">-- Pilih Model --</option>
                    @foreach ($vehicles as $v)
                        <option value="{{ $v->id }}" data-jenis="{{ $v->category }}" 
                            {{ old('vehicle_id') == $v->id ? 'selected' : '' }}>
                            {{ $v->brand->name }} - {{ $v->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Harga Listrik --}}
            <div class="mb-4">
                <label class="block text-sm font-bold mb-1">Harga Listrik per kWh (Rp)</label>
                <input type="number" name="harga_listrik" id="harga_listrik" class="w-full border px-3 py-2" 
                    value="{{ old('harga_listrik', 1444) }}" required min="0">
            </div>

            {{-- Jarak Tempuh Bulanan --}}
            <div class="mb-4">
                <label class="block text-sm font-bold mb-1">Jarak Tempuh per Bulan (km) <span class="text-gray-500 text-sm">(opsional)</span></label>
                <input type="number" name="jarak" id="jarak" class="w-full border px-3 py-2" 
                    value="{{ old('jarak', 1000) }}" min="1">
            </div>

            <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-800">Hitung</button>
        </form>

        {{-- JavaScript Filter Model Kendaraan --}}
        <script>
            document.getElementById('jenis_kendaraan').addEventListener('change', function () {
                const jenis = this.value;
                const modelSelect = document.getElementById('vehicle_id');

                Array.from(modelSelect.options).forEach(option => {
                    if (option.value === "") return;
                    const kategori = option.getAttribute('data-jenis');
                    option.style.display = kategori === jenis ? 'block' : 'none';
                });

                modelSelect.value = "";
            });

            // Trigger filter awal jika data lama masih terisi
            window.addEventListener('DOMContentLoaded', () => {
                document.getElementById('jenis_kendaraan').dispatchEvent(new Event('change'));
            });
        </script>

        {{-- Hasil Kalkulasi --}}
        @isset($result)
        <div class="mt-6 bg-gray-100 p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">Hasil Kalkulasi</h3>
            <ul class="space-y-1">
                <li><strong>Jarak Tempuh:</strong> {{ $result['jarak_bulanan'] }} km</li>
                <li><strong>Konsumsi Listrik per Km:</strong> {{ $result['kwh_per_km'] }} kWh/km</li>
                <li><strong>Biaya per Km:</strong> Rp {{ number_format($result['biaya_per_km'], 0, ',', '.') }}</li>
                <li><strong>Biaya per 100 Km:</strong> Rp {{ number_format($result['biaya_per_100_km'], 0, ',', '.') }}</li>
                @if ($result['biaya_bulanan'])
                    <li><strong>Biaya Bulanan:</strong> Rp {{ number_format($result['biaya_bulanan'], 0, ',', '.') }}</li>
                @endif
            </ul>
        </div>
        @endisset

        {{-- Spesifikasi Kendaraan --}}
        @isset($vehicle)
        <div class="mt-6 bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold mb-2">Spesifikasi Kendaraan</h3>
            @php
                $specs = $vehicle->specs->keyBy('name');
                $kapasitas = optional($specs->get('Kapasitas'))->pivot->value;
                $jarakMax = optional($specs->get('Jarak Tempuh'))->pivot->value;
                $lamaPengisian = optional($specs->get('Lama Pengisian'))->pivot->value;
                $harga = optional($specs->get('Harga'))->pivot->value;
                $kwhPerKm = $kapasitas && $jarakMax ? round($kapasitas / $jarakMax, 3) : 0;
            @endphp
            <ul class="space-y-1">
                <li><strong>Kapasitas Baterai:</strong> {{ $kapasitas }} kWh</li>
                <li><strong>Jarak Tempuh Maksimal:</strong> {{ $jarakMax }} km</li>
                <li><strong>Konsumsi Listrik per Km:</strong> {{ $kwhPerKm }} kWh/km</li>
                <li><strong>Lama Pengisian:</strong> {{ $lamaPengisian }}</li>
                <li><strong>Harga Kendaraan:</strong> Rp {{ number_format($harga, 0, ',', '.') }}</li>
            </ul>
        </div>
        @endisset
    </div>

    <x-slot:footer>
        <x-menu.footer :logo="$logo" />
    </x-slot:footer>
</x-layouts.main>
