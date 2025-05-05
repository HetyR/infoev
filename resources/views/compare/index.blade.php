<x-layouts.main>
    <!-- Menyusun layout utama -->
    <x-slot:title>Compare Vehicles - InfoEV</x-slot>
    <!-- Menyisipkan judul halaman "Compare Vehicles - InfoEV" ke dalam slot title -->
    <x-slot:meta></x-slot>
    <!-- Menyisipkan meta tag kosong ke dalam slot meta -->

    <x-slot:header>
        @if (isset($bikeBrands) && isset($carBrands))
            <!-- Jika $bikeBrands dan $carBrands tersedia -->
            <x-menu.navbar :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
            <!-- Menyertakan komponen navbar dengan logo, merek motor, dan merek mobil -->
        @else
            <x-menu.navbar :logo="$logo" />
            <!-- Jika $bikeBrands dan $carBrands tidak tersedia, hanya menyertakan komponen navbar dengan logo -->
        @endif
        <x-menu.menu />
        <!-- Menyertakan komponen menu -->
    </x-slot>

    <x-slot:sidebar>
        <!-- Menyusun sidebar -->
        <x-sidebar.brand-menu :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
        <!-- Menyertakan komponen menu merek dengan merek motor dan mobil -->
        <x-sidebar.latest :recentVehicles="$recentVehicles" />
        <!-- Menyertakan komponen terbaru dengan kendaraan terbaru -->
        <x-sidebar.top :popularVehicles="$popularVehicles" />
        <!-- Menyertakan komponen populer dengan kendaraan populer -->
        <x-sidebar.featured :featuredArticles="$stickies" />
        <!-- Menyertakan komponen artikel unggulan dengan artikel yang ditandai -->
    </x-slot>

    @if (isset($banner) && !is_null($banner))
        <!-- Jika $banner tersedia dan tidak null -->
        <x-menu.title-header-compare :img="$banner" title="Compare" />
        <!-- Menyertakan komponen header dengan gambar banner dan judul "Compare" -->
    @else
        <x-menu.title-header-compare title="Compare" />
        <!-- Jika $banner tidak tersedia atau null, hanya menyertakan komponen header dengan judul "Compare" -->
    @endif

    <form action="{{ route('compare.index') }}" method="POST" class="relative grid grid-cols-1 mt-4 py-4 pr-4 md:grid-cols-2 gap-4 mb-4 space-x-4 text-left">
        <!-- Form untuk membandingkan kendaraan dengan metode POST -->
        @csrf
        <!-- Token CSRF untuk keamanan -->
        <div class="relative">
            <!-- Input untuk kendaraan 1 -->
            <label for="vehicle1" class="text-lg font-semibold mb-4">Kendaraan 1 :</label>
            <input type="text" name="vehicle1" id="vehicle1" placeholder="Masukan Nama Merek dan Kendaraan" required autocomplete="on" class="w-full p-2 border border-gray-300 rounded" value="{{ old('vehicle1', $vehicle1 ? $vehicle1->brand->name . ' ' . $vehicle1->name : '') }}">
            <!-- Menampilkan nilai lama jika ada atau menampilkan nama merek dan kendaraan dari $vehicle1 -->
            <div id="vehicle1Suggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded mt-1"></div>
            <!-- Kotak saran untuk kendaraan 1 -->
        </div>
        <div class="relative">
            <!-- Input untuk kendaraan 2 -->
            <label for="vehicle2" class="text-lg font-semibold mb-4">Kendaraan 2 :</label>
            <input type="text" name="vehicle2" id="vehicle2" placeholder="Masukan Nama Merek dan Kendaraan" required autocomplete="on" class="w-full p-2 border border-gray-300 rounded" value="{{ old('vehicle2', $vehicle2 ? $vehicle2->brand->name . ' ' . $vehicle2->name : '') }}">
            <!-- Menampilkan nilai lama jika ada atau menampilkan nama merek dan kendaraan dari $vehicle2 -->
            <div id="vehicle2Suggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded mt-1"></div>
            <!-- Kotak saran untuk kendaraan 2 -->
        </div>
        <div class="mt-4">
            <!-- Tombol untuk membandingkan -->
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-black bg-indigo-600 hover:bg-purple-900 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Compare
            </button>
        </div>

    </form>

    @if ($errorMessage)
        <!-- Tampilkan pesan kesalahan jika ada -->
        <div class="relative">
            <div class="px-5 text-red-500 text-lg mt-2">{{ $errorMessage }}</div>
        </div>
    @endif

    @if ($vehicle1 && $vehicle2)
        <!-- Jika kedua kendaraan tersedia -->
        <h1 class="px-4 text-left">Hasil Perbandingan:
            <!-- Menampilkan hasil perbandingan -->
            <a href="{{ route('vehicle.show', ['vehicle' => $vehicle1->slug]) }}" class="text-blue-500 hover:underline">{{ $vehicle1->brand->name }} {{ $vehicle1->name }}</a>
            <!-- Link ke detail kendaraan 1 -->
            dan
            <a href="{{ route('vehicle.show',['vehicle' => $vehicle2->slug]) }}" class="text-blue-500 hover:underline">{{ $vehicle2->brand->name }} {{ $vehicle2->name }}</a>
            <!-- Link ke detail kendaraan 2 -->
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 py-2 px-4 gap-4 text-left">
            <div>
                <!-- Bagian untuk kendaraan 1 -->
                <h2 class="text-lg font-semibold mb-4">
                    <a href="{{ route('vehicle.show', $vehicle1->slug) }}" class="text-blue-500 hover:underline">{{ $vehicle1->brand->name }} {{ $vehicle1->name }}</a>
                    <!-- Link ke detail kendaraan 1 -->
                </h2>
                @if ($vehicle1->pictures->isNotEmpty())
                    <!-- Jika ada gambar kendaraan 1 -->
                    @if ($vehicle1->pictures->where('thumbnail', 1)->isNotEmpty())
                        <img src="{{ asset('storage/' . $vehicle1->pictures->where('thumbnail', 1)->first()->path) }}" alt="thumbnail" class="img-fluid w-full max-w-xs mx-auto cursor-pointer">
                        <!-- Menampilkan thumbnail kendaraan 1 -->
                    @else
                        <img src="{{ asset('storage/' . $vehicle1->pictures->first()->path) }}" alt="thumbnail" class="img-fluid w-full max-w-xs mx-auto cursor-pointer">
                        <!-- Menampilkan gambar pertama jika tidak ada thumbnail -->
                    @endif
                @endif
            </div>
            <div>
                <!-- Bagian untuk kendaraan 2 -->
                <h2 class="text-lg font-semibold mb-4">
                    <a href="{{ route('vehicle.show', $vehicle2->slug) }}" class="text-blue-500 hover:underline">{{ $vehicle2->brand->name }} {{ $vehicle2->name }}</a>
                    <!-- Link ke detail kendaraan 2 -->
                </h2>
                @if ($vehicle2->pictures->isNotEmpty())
                    <!-- Jika ada gambar kendaraan 2 -->
                    @if ($vehicle2->pictures->where('thumbnail', 1)->isNotEmpty())
                        <img src="{{ asset('storage/' . $vehicle2->pictures->where('thumbnail', 1)->first()->path) }}" alt="thumbnail" class="img-fluid w-full max-w-xs mx-auto cursor-pointer">
                        <!-- Menampilkan thumbnail kendaraan 2 -->
                    @else
                        <img src="{{ asset('storage/' . $vehicle2->pictures->first()->path) }}" alt="thumbnail" class="img-fluid w-full max-w-xs mx-auto cursor-pointer">
                        <!-- Menampilkan gambar pertama jika tidak ada thumbnail -->
                    @endif
                @endif
            </div>
        </div>

        @foreach ($specCategories as $cat)
            @if ($cat->specs->filter(fn($value) => $value->vehicles->isNotEmpty())->isNotEmpty())
                <!-- Jika ada spesifikasi yang tersedia di kategori -->
                <div class="mb-4 space-x-4">
                    <h3 class="bg-slate-200 uppercase text-purple-900 font-bold p-2">{{ $cat->name }}</h3>
                    <!-- Menampilkan nama kategori spesifikasi -->
                    <div class="bg-slate-100 p-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($cat->specs as $spec)
                            @if ($spec->vehicles->isNotEmpty())
                                <!-- Jika spesifikasi tersedia untuk kendaraan -->
                                @php
                                    $pivot1 = $spec->vehicles->find($vehicle1->id)->pivot ?? null;
                                    $pivot2 = $spec->vehicles->find($vehicle2->id)->pivot ?? null;
                                    $value1 = $pivot1 ? rtrim(rtrim($pivot1->value, '0'), '.') : null;
                                    $value2 = $pivot2 ? rtrim(rtrim($pivot2->value, '0'), '.') : null;
                                    $isDifferent = $value1 != $value2;
                                    $formattedValue1 = $spec->unit === 'Rp' ? 'Rp ' . number_format($value1, 0, ',', '.') : $value1;
                                    $formattedValue2 = $spec->unit === 'Rp' ? 'Rp ' . number_format($value2, 0, ',', '.') : $value2;
                                @endphp
                                <div class="py-1 border-b border-zinc-300 text-left">
                                    <strong>{{ $spec->name }}:</strong>
                                    <!-- Menampilkan nama spesifikasi -->
                                    <span style="color: {{ $isDifferent ? 'red' : 'black' }}">
                                        @switch($spec->type)
                                            @case('price')
                                                {{ $spec->unit }} {{ number_format($value1, 0, ',', '.') . ',-' }}{{ $pivot1 && $pivot1->value_desc != null ? ' (' . $pivot1->value_desc . ')' : '' }}
                                                <!-- Menampilkan harga dengan format Rp -->
                                                @break
                                            @case('unit')
                                                {{ Str::replace('.', ',', (float) $value1) }} {{ $spec->unit }}{{ $pivot1 && $pivot1->value_desc != null ? ' (' . $pivot1->value_desc . ')' : '' }}
                                                <!-- Menampilkan nilai dengan satuan -->
                                                @break
                                            @case('list')
                                                @php
                                                    $pivotLists1 = $pivot1 ? App\Models\SpecVehicle::with('lists')->find($pivot1->id)->lists : collect();
                                                    $pivotLists2 = $pivot2 ? App\Models\SpecVehicle::with('lists')->find($pivot2->id)->lists : collect();
                                                    $isDifferent = $pivotLists1->implode('list', ', ') != $pivotLists2->implode('list', ', ');
                                                @endphp
                                                <span style="color: {{ $isDifferent ? 'red' : 'black' }}">
                                                    {{ $pivotLists1->implode('list', ', ') }}{{ $pivot1 && $pivot1->value_desc != null ? ' (' . $pivot1->value_desc . ')' : '' }}
                                                </span>
                                                <!-- Menampilkan list -->
                                                @break
                                            @case('description')
                                                @php
                                                    $desc1 = $pivot1 ? $pivot1->value_desc : '';
                                                    $desc2 = $pivot2 ? $pivot2->value_desc : '';
                                                    $isDifferent = $desc1 != $desc2;
                                                @endphp
                                                <span style="color: {{ $isDifferent ? 'red' : 'black' }}">
                                                    {{ $desc1 }}
                                                </span>
                                                <!-- Menampilkan deskripsi -->
                                                @break
                                            @case('availability')
                                                @if ($pivot1 && $pivot1->value_bool == 1)
                                                    Tersedia
                                                @else
                                                    Tidak tersedia
                                                @endif
                                                <!-- Menampilkan ketersediaan -->
                                                @break
                                            @default
                                                {{ $formattedValue1 }} {{ $spec->unit !== 'Rp' ? $spec->unit : '' }}
                                                <!-- Menampilkan nilai default -->
                                        @endswitch
                                    </span>
                                </div>
                                <div class="py-1 border-b border-zinc-300 text-left">
                                    <span style="color: {{ $isDifferent ? 'blue' : 'black' }}">
                                        @switch($spec->type)
                                            @case('price')
                                                {{ $spec->unit }} {{ number_format($value2, 0, ',', '.') . ',-' }}{{ $pivot2 && $pivot2->value_desc != null ? ' (' . $pivot2->value_desc . ')' : '' }}
                                                <!-- Menampilkan harga dengan format Rp -->
                                                @break
                                            @case('unit')
                                                {{ Str::replace('.', ',', (float) $value2) }} {{ $spec->unit }}{{ $pivot2 && $pivot2->value_desc != null ? ' (' . $pivot2->value_desc . ')' : '' }}
                                                <!-- Menampilkan nilai dengan satuan -->
                                                @break
                                            @case('list')
                                                <span style="color: {{ $isDifferent ? 'blue' : 'black' }}">
                                                    {{ $pivotLists2->implode('list', ', ') }}{{ $pivot2 && $pivot2->value_desc != null ? ' (' . $pivot2->value_desc . ')' : '' }}
                                                </span>
                                                <!-- Menampilkan list -->
                                                @break
                                            @case('description')
                                                <span style="color: {{ $isDifferent ? 'blue' : 'black' }}">
                                                    {{ $desc2 }}
                                                </span>
                                                <!-- Menampilkan deskripsi -->
                                                @break
                                            @case('availability')
                                                @if ($pivot2 && $pivot2->value_bool == 1)
                                                    Tersedia
                                                @else
                                                    Tidak tersedia
                                                @endif
                                                <!-- Menampilkan ketersediaan -->
                                                @break
                                            @default
                                                {{ $formattedValue2 }} {{ $spec->unit !== 'Rp' ? $spec->unit : '' }}
                                                <!-- Menampilkan nilai default -->
                                        @endswitch
                                    </span>
                                </div>
                                @if ($isDifferent)
                                    <div class="col-span-2 text-sm text-gray-700 text-left">
                                        {{ $spec->description }}
                                        <!-- Menampilkan deskripsi spesifikasi jika berbeda -->
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

    @endif


    <x-slot:footer>
        <!-- Menyusun footer -->
        <x-menu.footer :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" :recentVehicles="$recentVehicles" :popularVehicles="$popularVehicles" :featuredArticles="$stickies" />
        <!-- Menyertakan komponen footer dengan berbagai informasi -->
    </x-slot>
</x-layouts.main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const combinedList = @json($combinedList);

        function autocomplete(input, suggestionsBox, data) {
            let currentFocus = -1;

            input.addEventListener('input', function () {
                const value = this.value.toLowerCase();
                suggestionsBox.innerHTML = '';
                currentFocus = -1;
                if (!value) return;

                // Filter suggestions to include only those with both brand and vehicle name
                const suggestions = data.filter(item => item.name.toLowerCase().includes(value) && item.name.split(' ').length > 1);
                suggestions.forEach((item, index) => {
                    const div = document.createElement('div');
                    div.textContent = item.name;
                    div.classList.add('p-2', 'cursor-pointer', 'text-left');
                    div.addEventListener('mouseover', function() {
                        this.classList.add('bg-black', 'text-white');
                    });
                    div.addEventListener('mouseout', function() {
                        this.classList.remove('bg-black', 'text-white');
                    });
                    div.addEventListener('click', function () {
                        input.value = item.name;
                        suggestionsBox.innerHTML = '';
                        checkForDuplicate();
                        checkForNotFound();
                    });
                    suggestionsBox.appendChild(div);
                });
            });

            input.addEventListener('keydown', function (e) {
                const items = suggestionsBox.getElementsByTagName('div');
                if (e.key === 'ArrowDown') {
                    currentFocus++;
                    addActive(items);
                } else if (e.key === 'ArrowUp') {
                    currentFocus--;
                    addActive(items);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (currentFocus > -1) {
                        if (items[currentFocus]) {
                            items[currentFocus].click();
                        }
                    }
                }
            });

            function addActive(items) {
                if (!items) return false;
                removeActive(items);
                if (currentFocus >= items.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = items.length - 1;
                items[currentFocus].classList.add('bg-black', 'text-white');
            }

            function removeActive(items) {
                for (let i = 0; i < items.length; i++) {
                    items[i].classList.remove('bg-black', 'text-white');
                }
            }
        }

        function checkForDuplicate() {
            const vehicle1Value = vehicle1Input.value.trim();
            const vehicle2Value = vehicle2Input.value.trim();
            const notification = document.getElementById('duplicateNotification');

            if (vehicle1Value && vehicle2Value && vehicle1Value === vehicle2Value) {
                notification.textContent = 'Kendaraan 1 dan Kendaraan 2 tidak boleh sama.';
                notification.style.display = 'block';
                alert('Kendaraan 1 dan Kendaraan 2 tidak boleh sama.');
            } else {
                notification.style.display = 'none';
            }
        }



        const vehicle1Input = document.getElementById('vehicle1');
        const vehicle1Suggestions = document.getElementById('vehicle1Suggestions');
        const vehicle2Input = document.getElementById('vehicle2');
        const vehicle2Suggestions = document.getElementById('vehicle2Suggestions');

        autocomplete(vehicle1Input, vehicle1Suggestions, combinedList);
        autocomplete(vehicle2Input, vehicle2Suggestions, combinedList);

        vehicle1Input.addEventListener('input', checkForDuplicate);
        vehicle1Input.addEventListener('input', checkForNotFound);
        vehicle2Input.addEventListener('input', checkForDuplicate);
        vehicle2Input.addEventListener('input', checkForNotFound);
    });
</script>
<!-- Script untuk autocomplete pada input kendaraan 1 dan kendaraan 2 -->

<div id="duplicateNotification" class="text-red-500 text-sm mt-2" style="display: none;"></div>
<!-- Notifikasi untuk kendaraan yang sama -->
<div id="notFoundNotification" class="text-red-500 text-sm mt-2" style="display: none;"></div>
<!-- Notifikasi untuk data tidak ditemukan -->
