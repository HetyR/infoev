<x-layouts.main>
    <x-slot:title>EV Finder</x-slot>

    <x-slot:meta>
    </x-slot>

    {{-- Header --}}
    <x-slot:header>
        @if (isset($bikeBrands) && isset($carBrands))
            <x-menu.navbar :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
        @else
            <x-menu.navbar :logo="$logo" />
        @endif
        <x-menu.menu />
    </x-slot>
    {{-- End Header --}}

    {{-- Sidebar --}}
    <x-slot:sidebar>
        {{-- Brand Menu --}}
        <x-sidebar.brand-menu :bikeBrands="$bikeBrands" :carBrands="$carBrands" />

        {{-- Latest Models --}}
        <x-sidebar.latest :recentVehicles="$recentVehicles" />

        {{-- Top 10 --}}
        <x-sidebar.top :popularVehicles="$popularVehicles" />

        {{-- Featured --}}
        <x-sidebar.featured :featuredArticles="$stickies" />
    </x-slot>
    {{-- End Sidebar --}}


{{-- Title Header --}}
@if (isset($banner) && !is_null($banner))
<x-menu.title-header-finder :img="$banner" title="Finder" />
@else
<x-menu.title-header-finder title="Finder" />
@endif

    {{-- Content Section --}}
    <div class="flex-1 md:mt-0 md:p-3">
        {{-- Mobile Title --}}
        <div class="flex justify-between items-center md:hidden">
            <h2 class="pl-3 py-1 border-l-8 border-purple-900 uppercase font-bold text-slate-800">Berita</h2>
        </div>
        {{-- End Mobile Title --}}

        {{-- Form Section --}}
        <form id="searchForm" class="space-y-6" action="{{ route('finder.search') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 space-x-4 space-x-reverse">
                {{-- General --}}
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">General</h3>
                    <div class="mt-4 space-y-4">

                        {{-- tipe kendaraan --}}
                        <div class="relative">
                            <label for="vehicleType" class="block text-sm font-medium text-gray-700">Tipe
                                Kendaraan:</label>
                            <select id="vehicleType" name="vehicleType"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Pilih Tipe Kendaraan</option>
                                {{-- {{ dd($vehicleTypes) }} --}}
                                @foreach ($vehicleTypes as $type)
                                    <option value="{{ $type->id }}"
                                        {{ request()->input('vehicleType') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}</option>
                                @endforeach
                            </select>
                            <span id="selected"
                                class="block text-sm font-medium text-white mt-2 min-h-[20px]">.</span>
                        </div>

                        {{-- kendaraan --}}
                        <div class="relative">
                            <label for="brands" class="block text-sm font-medium text-gray-700">Merk:</label>
                            <button id="brandDropdownButton" type="button"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                Pilih Merk
                            </button>
                            <div id="brandDropdownContent"
                                class="absolute mt-1 w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm hidden z-10">
                                <div class="p-2 max-h-48 overflow-auto">
                                    {{-- {{ dd($bikeBrands) }} --}}
                                    @foreach ($bikeBrands as $brand)
                                        <label class="flex items-center mt-2">
                                            <input type="checkbox" name="brands[]" value="{{ $brand->id }}"
                                                class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                                onchange="updateSelectedBrands()"
                                                {{ in_array($brand->id, request()->input('brands', [])) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ $brand->name }}</span>
                                        </label>
                                    @endforeach
                                    @foreach ($carBrands as $brand)
                                        <label class="flex items-center mt-2">
                                            <input type="checkbox" name="brands[]" value="{{ $brand->id }}"
                                                class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                                onchange="updateSelectedBrands()"
                                                {{ in_array($brand->id, request()->input('brands', [])) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ $brand->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <span id="selectedBrands"
                                class="block text-sm font-medium text-gray-700 mt-2 min-h-[20px]">Tidak ada yang
                                dipilih</span>
                        </div>

                        {{-- Tahun --}}
                        <div class="relative">
                            <label for="years" class="block text-sm font-medium text-gray-700">Tahun:</label>
                            <button id="dropdownButton" type="button"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                Pilih Tahun
                            </button>
                            <div id="dropdownContent"
                                class="absolute z-10 mt-1 w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm hidden">
                                <div class="p-2 max-h-48 overflow-auto">
                                    @php
                                        $uniqueYears = $years->unique('value')->sortByDesc('value');
                                    @endphp
                                    @foreach ($uniqueYears as $year)
                                        <label class="flex items-center mt-2">
                                            <input type="checkbox" name="years[]" value="{{ (int)$year->value }}"
                                                class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                                onchange="updateSelectedYears()"
                                                {{ in_array((int)$year->value, request()->input('years', [])) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ (int)$year->value }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <span id="selectedYears"
                                class="block text-sm font-medium text-gray-700 mt-2 min-h-[20px]">Tidak ada yang
                                dipilih</span>
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
                            <input type="range" name="price" id="price" min="0" max="1000000000"
                                class="mt-1 block w-full text-gray-600" oninput="updatePriceValue(this.value)"
                                value="{{ request()->input('price', 0) }}">
                            <span id="priceValue" class="block text-sm font-medium text-gray-700 mt-2">Rp 0</span>
                        </div>
                        <div>
                            <label for="maxspeed" class="block text-sm font-medium text-gray-700">Kecepatan
                                Maksimal</label>
                            <input type="range" name="maxspeed" id="maxspeed" min="0" max="1000"
                                class="mt-1 block w-full" oninput="updateMaxSpeedValue(this.value)"
                                value="{{ request()->input('maxspeed', 0) }}">
                            <span id="maxValue" class="block text-sm font-medium text-gray-700 mt-2">0</span>
                        </div>



                    </div>
                </div>

                {{-- Space Divider --}}
                <hr class="border-t border-black-200 md:hidden">

                {{-- Performance --}}
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Performance</h3>
                    <div class="mt-4 space-y-4">



                        <div class="relative">

                            <label for="drive" class="block text-sm font-medium text-gray-700">Sistem
                                Penggerak:</label>
                            <button id="driveDropdownButton" type="button"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                Pilih Sistem Penggerak
                            </button>
                            <div id="driveDropdownContent"
                                class="absolute z-10 mt-1 w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm hidden">
                                <div class="p-2 max-h-48 overflow-auto">
                                    @foreach ($driveSystems as $drive)
                                        <label class="flex items-center mt-2">
                                            <input type="checkbox" name="driveSystems[]" value="{{ $drive->list }}"
                                                class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                                onchange="updateSelectedDrive()"
                                                {{ in_array($drive->list, request()->input('driveSystems', [])) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ $drive->list }}</span>
                                        </label>
                                    @endforeach

                                </div>
                            </div>
                            <span id="selectedDrive"
                                class="block text-sm font-medium text-gray-700 mt-2 min-h-[20px]">Tidak ada yang
                                dipilih</span>
                        </div>

                        <div class="relative">
                            <label for="teknologibaterai" class="block text-sm font-medium text-gray-700">Teknologi
                                Baterai:</label>
                            <button id="teknologibateraiDropdownButton" type="button"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                Pilih Teknologi Baterai
                            </button>
                            <div id="teknologibateraiDropdownContent"
                                class="absolute z-10 mt-1 w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm hidden">
                                <div class="p-2 max-h-48 overflow-auto">
                                    @foreach ($teknologibaterai as $tech)
                                        <label class="flex items-center mt-2">
                                            <input type="checkbox" name="teknologibaterai[]"
                                                value="{{ $tech->list }}"
                                                class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"
                                                onchange="updateSelectedTeknologiBaterai()"
                                                {{ in_array($tech->list, request()->input('teknologibaterai', [])) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ $tech->list }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <span id="selectedTeknologiBaterai"
                                class="block text-sm font-medium text-gray-700 mt-2 min-h-[20px]">Tidak ada yang
                                dipilih</span>
                        </div>

                        <div class="py-3 ">
                            <label for="batterykapasitas" class="block text-sm font-medium text-gray-700">Kapasitas
                                Baterai:</label>
                            <input type="range" name="batterykapasitas" id="batterykapasitas" min="0"
                                max="200" step="0.1" class="mt-1 block w-full"
                                oninput="updateBatteryKapasitasValue(this.value)"
                                value="{{ request()->input('batterykapasitas', 0) }}">
                            <span id="batterykapasitasValue"
                                class="block text-sm font-medium text-gray-700 mt-2">0.0</span>
                        </div>

                        <div>
                            <label for="chargingtime" class="block text-sm font-medium  text-gray-700">Waktu
                                Pengisian:</label>
                            <input type="range" name="chargingtime" id="chargingtime" min="0"
                                max="24" class="mt-1 block w-full"
                                oninput="updateChargingTimeValue(this.value)"
                                value="{{ request()->input('chargingtime', 0) }}">
                            <span id="chargingtimeValue" class="block text-sm font-medium text-gray-700 mt-2">0</span>
                        </div>

                        <div>
                            <label for="distance" class="block text-sm font-medium text-gray-700">Jarak Tempuh</label>
                            <input type="range" name="distance" id="distance" min="0" max="1000"
                                class="mt-1 block w-full" oninput="updateDistanceValue(this.value)"
                                value="{{ request()->input('distance', 0) }}">
                            <span id="distanceValue" class="block text-sm font-medium text-gray-700 mt-2">0</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- New Features Checkboxes -->
            <div class="flex space-x-4 mt-5">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="features[]" value="alarm" id="alarm"
                        class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                    <label for="alarm" class="block text-sm font-medium text-gray-700"> Alarm</label>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="features[]" value="lampudepan" id="lampudepan"
                        class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                    <label for="lampudepan" class="block text-sm font-medium text-gray-700"> Lampu Depan</label>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="features[]" value="lampubelakang" id="lampubelakang"
                        class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                    <label for="lampubelakang" class="block text-sm font-medium text-gray-700"> Lampu Belakang</label>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="features[]" value="lampuhazard" id="lampuhazard"
                        class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                    <label for="lampuhazard" class="block text-sm font-medium text-gray-700"> Lampu Hazard</label>
                </div>
            </div>


            <br>
            <div class="flex justify-start relative">
                <button type="submit"
                    class="px-4 py-2 border border-indigo-600 text-indigo-600 rounded-md hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cari
                </button>
            </div>
        </form>

        {{-- Results Section --}}
        @if (isset($vehicles) && $vehicles->isNotEmpty())
            <div class="flex-1 [&>:last-child]:mb-3 md:mt-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Hasil Pencarian</h3>
                <div class="grid mt-2 md:mt-0 md:px-6 md:py-3 md:grid-cols-3">
                    @foreach ($vehicles as $vehicle)
                        <a href="{{ route('vehicle.show', ['vehicle' => $vehicle->slug]) }}"
                            class="group flex items-stretch px-4 py-3 border-b border-zinc-200 first:border-y hover:z-30 md:block md:px-0 md:py-0 md:text-gray-400 md:border-b-0 md:first:border-y-0 md:hover:scale-105 md:transition-transform md:ease-out">
                            <div class="flex justify-center items-center max-w-10 md:max-w-full">
                                @if ($vehicle->pictures->isEmpty())
                                    <img src="{{ asset('img/placeholder-md.png') }}"
                                        class="w-full object-cover group-hover:brightness-105" alt="">
                                @else
                                    <img src="{{ asset('storage/' . $vehicle->pictures->first()->path) }}"
                                        class="w-full object-cover group-hover:brightness-105" alt="">
                                @endif
                            </div>
                            <div
                                class="flex flex-1 flex-col justify-center items-start px-3 py-1 text-slate-700 group-hover:text-white group-hover:bg-purple-900 md:justify-start md:items-center md:px-2 md:bg-stone-50">
                                <span class="font-semibold text-lg">{{ $vehicle->brand->name }}
                                    {{ $vehicle->name }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="mt-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Hasil Pencarian</h3>
                <p class="mt-4 text-gray-500">Tidak ada hasil yang ditemukan.</p>
            </div>
        @endif
        {{-- End Results Section --}}
    </div>

    {{-- Footer --}}
    <x-slot:footer>
        <x-menu.footer :logo="$logo"
            :bikeBrands="$bikeBrands"
            :carBrands="$carBrands"
            :recentVehicles="$recentVehicles"
            :popularVehicles="$popularVehicles"
            :featuredArticles="$stickies" />
    </x-slot>
    {{-- End Footer --}}

    {{-- End Content Section --}}
</x-layouts.main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedVehicleType();
        updateSelectedBrands();
        updateSelectedYears();
        updateSelectedDrive();
        updateSelectedTeknologiBaterai();
        updatePriceValue(document.getElementById('price').value);
        updateMaxSpeedValue(document.getElementById('maxspeed').value);
        updateBatteryKapasitasValue(document.getElementById('batterykapasitas').value);
        updateChargingTimeValue(document.getElementById('chargingtime').value);
        updateDistanceValue(document.getElementById('distance').value);

        document.getElementById('vehicleType').addEventListener('change', function() {
            const vehicleType = this.value;
            fetchBrands(vehicleType);
        });
    });

    function updateSelectedVehicleType() {
        const $vehicleTypeselect = document.getElementById('vehicleType');
        const selectedOption = $vehicleTypeselect.options[$vehicleTypeselect.selectedIndex].text;
        document.getElementById('selected').textContent = `Tipe Kendaraan: ${selectedOption}`;
    }

    document.getElementById('vehicleType').addEventListener('change', updateSelectedVehicleType);

    document.getElementById('dropdownButton').addEventListener('click', function() {
        document.getElementById('dropdownContent').classList.toggle('hidden');
    });

    function updateSelectedYears() {
        const selectedYears = Array.from(document.querySelectorAll('input[name="years[]"]:checked')).map(checkbox =>
            checkbox.nextElementSibling.textContent);
        document.getElementById('selectedYears').textContent = `Tahun: ${selectedYears.join(', ')}`;
    }

    document.getElementById('brandDropdownButton').addEventListener('click', function() {
        document.getElementById('brandDropdownContent').classList.toggle('hidden');
    });

    function updateSelectedBrands() {
        const selectedBrands = Array.from(document.querySelectorAll('input[name="brands[]"]:checked')).map(checkbox =>
            checkbox.nextElementSibling.textContent);
        document.getElementById('selectedBrands').textContent = `Merk: ${selectedBrands.join(', ')}`;
    }

    document.getElementById('driveDropdownButton').addEventListener('click', function() {
        document.getElementById('driveDropdownContent').classList.toggle('hidden');
    });

    function updateSelectedDrive() {
        const selectedDrives = document.querySelectorAll('input[name="driveSystems[]"]:checked');
        const selectedDriveText = Array.from(selectedDrives).map(input => input.nextElementSibling.textContent).join(
            ', ');
        document.getElementById('selectedDrive').textContent = selectedDriveText || 'Tidak ada yang dipilih';
    }

    document.getElementById('teknologibateraiDropdownButton').addEventListener('click', function() {
        document.getElementById('teknologibateraiDropdownContent').classList.toggle('hidden');
    });

    function updateSelectedTeknologiBaterai() {
        const selectedTeknologiBaterai = Array.from(document.querySelectorAll(
            'input[name="teknologibaterai[]"]:checked')).map(checkbox => checkbox.nextElementSibling.textContent);
        document.getElementById('selectedTeknologiBaterai').textContent =
            `Teknologi Baterai: ${selectedTeknologiBaterai.join(', ')}`;
    }

    function updatePriceValue(value) {
        document.getElementById('priceValue').textContent = `Rp` + new Intl.NumberFormat().format(value);
    }

    function updateMaxSpeedValue(value) {
        const formattedValue = parseFloat(value).toFixed(2);
        document.getElementById('maxValue').textContent = formattedValue + ' Km/Jam';
    }

    function updateBatteryKapasitasValue(value) {
        const formattedValue = parseFloat(value).toFixed(2);
        document.getElementById('batterykapasitasValue').textContent = formattedValue + ' kWh';
    }

    function updateChargingTimeValue(value) {
        document.getElementById('chargingtimeValue').textContent = value + ' jam';
    }

    function updateDistanceValue(value) {
        document.getElementById('distanceValue').textContent = value + ' Km';
    }

    function fetchBrands(vehicleType) {
        fetch(`{{ route('finder.getBrandsByType') }}?vehicleType=${vehicleType}`)
            .then(response => response.json())
            .then(data => {
                const brandDropdownContent = document.getElementById('brandDropdownContent');
                brandDropdownContent.innerHTML = ''; // Clear existing options

                data.forEach(brand => {
                    const label = document.createElement('label');
                    label.classList.add('flex', 'items-center', 'mt-2');

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'brands[]';
                    checkbox.value = brand.id;
                    checkbox.classList.add('form-checkbox', 'h-4', 'w-4', 'text-indigo-600', 'transition', 'duration-150', 'ease-in-out');
                    checkbox.onchange = updateSelectedBrands;

                    const span = document.createElement('span');
                    span.classList.add('ml-2');
                    span.textContent = brand.name;

                    label.appendChild(checkbox);
                    label.appendChild(span);
                    brandDropdownContent.appendChild(label);
                });

                updateSelectedBrands();
            })
            .catch(error => console.error('Error fetching brands:', error));
    }
</script>
