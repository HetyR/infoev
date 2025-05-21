<x-layouts.main>
    <x-slot:title>Compare Vehicles - InfoEV</x-slot:title>
    <x-slot:meta></x-slot:meta>

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

    <x-menu.title-header-compare :img="$banner" title="Compare" />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-4 mt-4">
        <div class="relative">
            <label for="vehicle1" class="block font-semibold mb-2">Kendaraan 1</label>
            <input type="text" id="vehicle1" class="w-full p-2 border border-gray-300 rounded"
                placeholder="Brand + Model" value="{{ $prefillVehicle1 }}">
            <div id="vehicle1Suggestions" class="absolute bg-white border border-gray-300 rounded w-full z-10 mt-1">
            </div>
        </div>
        <div class="relative">
            <label for="vehicle2" class="block font-semibold mb-2">Kendaraan 2</label>
            <input type="text" id="vehicle2" class="w-full p-2 border border-gray-300 rounded"
                placeholder="Brand + Model">
            <div id="vehicle2Suggestions" class="absolute bg-white border border-gray-300 rounded w-full z-10 mt-1">
            </div>
        </div>
    </div>

    <div id="compare-result" class="px-4 mt-6"></div>

    <x-slot:footer>
        <x-menu.footer :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" :recentVehicles="$recentVehicles" :popularVehicles="$popularVehicles"
            :featuredArticles="$stickies" />
    </x-slot:footer>
</x-layouts.main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const combinedList = @json($combinedList);

        const vehicle1Input = document.getElementById('vehicle1');
        const vehicle2Input = document.getElementById('vehicle2');
        const vehicle1Suggestions = document.getElementById('vehicle1Suggestions');
        const vehicle2Suggestions = document.getElementById('vehicle2Suggestions');

        function autocomplete(input, suggestionsBox) {
            input.addEventListener('input', () => {
                const value = input.value.toLowerCase();
                suggestionsBox.innerHTML = '';
                if (!value) return;

                const results = combinedList.filter(item => item.name.toLowerCase().includes(value));
                results.forEach(item => {
                    const div = document.createElement('div');
                    div.textContent = item.name;
                    div.classList.add('p-2', 'hover:bg-gray-200', 'cursor-pointer');
                    div.onclick = () => {
                        input.value = item.name;
                        suggestionsBox.innerHTML = '';
                        fetchCompareResult();
                    };
                    suggestionsBox.appendChild(div);
                });
            });

            input.addEventListener('blur', () => {
                setTimeout(() => suggestionsBox.innerHTML = '', 200);
            });
        }

        function fetchCompareResult() {
            const vehicle1 = vehicle1Input.value.trim();
            const vehicle2 = vehicle2Input.value.trim();

            if (!vehicle1 && !vehicle2) return;
            if (vehicle1 && vehicle2 && vehicle1 === vehicle2) return;

            fetch('https://infoev.mazkama.web.id/compare/fetch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        vehicle1,
                        vehicle2
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log('Response data:', data);
                    document.getElementById('compare-result').innerHTML = renderCompare(data);
                })
                .catch(err => {
                    document.getElementById('compare-result').innerHTML =
                        '<p class="text-red-500">Gagal memuat data.</p>';
                });
        }

        function renderCompare(data) {
            console.log('renderCompare data:', data);

            const v1 = data.vehicle1;
            const v2 = data.vehicle2;
            const specs = data.specCategories;

            if (!v1 && !v2) return '<p class="text-red-500">Data kendaraan tidak ditemukan.</p>';

            let html = '<div class="mb-4">';
            html += v1 && v2 ?
                `<h2 class="text-lg font-semibold mb-2">Perbandingan: <strong>${v1.brand.name} ${v1.name}</strong> vs <strong>${v2.brand.name} ${v2.name}</strong></h2>` :
                `<h2 class="text-lg font-semibold mb-2">Spesifikasi: <strong>${(v1 || v2).brand.name} ${(v1 || v2).name}</strong></h2>`;
            html += '</div>';

            // Gambar kendaraan
            html += `<div class="grid grid-cols-2 md:grid-cols-3 gap-4 items-start mb-6">
        <div></div>`; // Kolom kosong untuk "judul spek"

            [v1, v2].forEach(vehicle => {
                if (!vehicle) {
                    html += '<div>-</div>';
                    return;
                }
                const thumb = vehicle.pictures?.find(p => p.thumbnail)?.path || vehicle.pictures?.[0]
                    ?.path;
                html += `<div class="text-center">
            <h3 class="font-bold mb-1">${vehicle.brand.name} ${vehicle.name}</h3>`;
                if (thumb) {
                    html +=
                        `<img src="/storage/${thumb}" class="mx-auto max-w-[150px] rounded shadow">`;
                }
                html += '</div>';
            });
            html += '</div>';

            // Render tiap kategori spesifikasi
            specs.forEach(cat => {
                console.log('Category:', cat.name);

                // Deteksi jika kategori memiliki spec yang terkait dengan kendaraan
                const hasVehicleSpecs = cat.specs.some(spec => {
                    return spec.vehicles && spec.vehicles.some(v =>
                        String(v.id) === String(v1?.id) || String(v.id) === String(v2?.id)
                    );
                });

                if (!hasVehicleSpecs) return;

                // Render judul kategori
                html +=
                    `<h4 class="mt-6 font-semibold text-purple-700 border-b-2 border-purple-700 pb-1 mb-2 inline-block">${cat.name}</h4>`;

                // Render tabel spesifikasi
                html +=
                    `<table class="w-full text-sm text-left mb-6 border border-gray-300 border-collapse">`;

                cat.specs.forEach(spec => {
                    console.log('Spec:', spec.name, spec.type, spec.vehicles);

                    // Hanya tampilkan spec yang terkait dengan kendaraan
                    const veh1 = spec.vehicles?.find(v => String(v.id) === String(v1?.id));
                    const veh2 = spec.vehicles?.find(v => String(v.id) === String(v2?.id));

                    if (!veh1 && !veh2) return;

                    let val1 = '-';
                    let val2 = '-';
                    let isDiff = false;

                    // Berdasarkan tipe spec, ambil nilai yang sesuai
                    switch (spec.type) {
                        case 'price':
                            val1 = veh1?.pivot?.value ? formatCurrency(veh1.pivot.value, spec
                                .unit) : '-';
                            val2 = veh2?.pivot?.value ? formatCurrency(veh2.pivot.value, spec
                                .unit) : '-';

                            // Tambahkan deskripsi jika ada
                            if (veh1?.pivot?.value_desc) val1 += ` (${veh1.pivot.value_desc})`;
                            if (veh2?.pivot?.value_desc) val2 += ` (${veh2.pivot.value_desc})`;
                            break;

                        case 'unit':
                            val1 = veh1?.pivot?.value ?
                                `${formatNumber(veh1.pivot.value)} ${spec.unit || ''}` : '-';
                            val2 = veh2?.pivot?.value ?
                                `${formatNumber(veh2.pivot.value)} ${spec.unit || ''}` : '-';

                            // Tambahkan deskripsi jika ada
                            if (veh1?.pivot?.value_desc) val1 += ` (${veh1.pivot.value_desc})`;
                            if (veh2?.pivot?.value_desc) val2 += ` (${veh2.pivot.value_desc})`;
                            break;

                        case 'list':
                            val1 = veh1?.pivot?.lists?.map(item => item.list).join(', ') || '-';
                            val2 = veh2?.pivot?.lists?.map(item => item.list).join(', ') || '-';

                            // Tambahkan deskripsi jika ada
                            if (veh1?.pivot?.value_desc) val1 += ` (${veh1.pivot.value_desc})`;
                            if (veh2?.pivot?.value_desc) val2 += ` (${veh2.pivot.value_desc})`;
                            break;

                        case 'description':
                            val1 = veh1?.pivot?.value_desc || '-';
                            val2 = veh2?.pivot?.value_desc || '-';
                            break;

                        case 'availability':
                            val1 = veh1?.pivot?.value_bool === 1 ? 'Tersedia' :
                                'Tidak tersedia';
                            val2 = veh2?.pivot?.value_bool === 1 ? 'Tersedia' :
                                'Tidak tersedia';
                            break;

                        default:
                            val1 = veh1?.pivot?.value ? `${veh1.pivot.value} ${spec.unit || ''}`
                                .trim() : '-';
                            val2 = veh2?.pivot?.value ? `${veh2.pivot.value} ${spec.unit || ''}`
                                .trim() : '-';
                            break;
                    }

                    isDiff = val1 !== val2 && v1 && v2;
                    const diffClass = isDiff ? 'text-red-600 font-medium' : '';

                    html += `<tr class="border border-gray-200">
                <td class="p-2 w-1/3 font-medium bg-gray-50">${spec.name}</td>
                <td class="p-2 w-1/3 bg-blue-50 ${diffClass}">${val1}</td>
                <td class="p-2 w-1/3 bg-green-50 ${diffClass}">${val2}</td>
            </tr>`;

                    // Tambahkan deskripsi spec jika berbeda
                    if (isDiff && spec.description) {
                        html += `<tr class="border border-gray-200">
                    <td colspan="3" class="p-2 text-sm text-gray-700">${spec.description}</td>
                </tr>`;
                    }
                });

                html += `</table>`;
            });

            return html;
        }

        // Fungsi pembantu untuk format angka
        function formatNumber(value) {
            if (!value) return '';
            return String(value).replace('.', ',');
        }

        // Fungsi pembantu untuk format mata uang
        function formatCurrency(value, unit) {
            if (!value) return '';
            return `${unit} ${new Intl.NumberFormat('id-ID').format(value)},-`;
        }

        autocomplete(vehicle1Input, vehicle1Suggestions);
        autocomplete(vehicle2Input, vehicle2Suggestions);

        // Trigger otomatis kalau vehicle1 sudah terisi dari session
        if (vehicle1Input.value.trim()) {
            fetchCompareResult();
        }
    });
</script>
