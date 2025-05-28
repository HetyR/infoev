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
            <div id="vehicle1Suggestions" class="absolute bg-white border border-gray-300 rounded w-full z-10 mt-1 max-h-60 overflow-y-auto shadow-lg"></div>
        </div>
        <div class="relative">
            <label for="vehicle2" class="block font-semibold mb-2">Kendaraan 2</label>
            <input type="text" id="vehicle2" class="w-full p-2 border border-gray-300 rounded"
                placeholder="Brand + Model">
            <div id="vehicle2Suggestions" class="absolute bg-white border border-gray-300 rounded w-full z-10 mt-1 max-h-60 overflow-y-auto shadow-lg"></div>
        </div>
    </div>

    <div id="compare-result" class="px-4 mt-6"></div>

    <x-slot:footer>
        <x-menu.footer :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" :recentVehicles="$recentVehicles" :popularVehicles="$popularVehicles"
            :featuredArticles="$stickies" />
    </x-slot:footer>
</x-layouts.main>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<script>
    document.addEventListener('DOMContentLoaded', function () {
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
                    div.classList.add('p-2', 'hover:bg-purple-50', 'cursor-pointer', 'text-gray-700', 'transition', 'duration-150');
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

            fetch("{{ request()->secure() ? secure_url('/compare/fetch') : url('/compare/fetch') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ vehicle1, vehicle2 })
            })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('compare-result').innerHTML = renderCompare(data);
                })
                .catch(err => {
                    document.getElementById('compare-result').innerHTML = '<p class="text-red-500 text-sm">Gagal memuat data.</p>';
                });
        }

        function renderCompare(data) {
            const v1 = data.vehicle1;
            const v2 = data.vehicle2;
            const specs = data.specCategories;

            if (!v1 && !v2) return '<p class="text-red-500 text-sm">Data kendaraan tidak ditemukan.</p>';

            let html = '<div class="mb-6">';
            html += v1 && v2 ?
                `<h2 class="text-lg md:text-xl font-semibold text-gray-800">Perbandingan: <span class="text-purple-600">${v1.brand.name} ${v1.name}</span> vs <span class="text-purple-600">${v2.brand.name} ${v2.name}</span></h2>` :
                `<h2 class="text-lg md:text-xl font-semibold text-gray-800">Spesifikasi: <span class="text-purple-600">${(v1 || v2).brand.name} ${(v1 || v2).name}</span></h2>`;
            html += '</div>';

            // Vehicle images (side-by-side)
            html += `<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">`;
            [v1, v2].forEach(vehicle => {
                if (!vehicle) {
                    html += '<div class="text-center text-gray-500">-</div>';
                    return;
                }
                const thumb = vehicle.pictures?.find(p => p.thumbnail)?.path || vehicle.pictures?.[0]?.path;
                html += `<div class="text-center">
                    <h3 class="font-semibold text-gray-800 text-lg mb-2">${vehicle.brand.name} ${vehicle.name}</h3>`;
                if (thumb) {
                    html += `<img src="/storage/${thumb}" class="mx-auto max-w-[250px] h-auto rounded-lg object-cover" alt="${vehicle.name}">`;
                }
                html += '</div>';
            });
            html += '</div>';

            // Specification categories
            specs.forEach(cat => {
                const hasVehicleSpecs = cat.specs.some(spec => {
                    return spec.vehicles && spec.vehicles.some(v =>
                        String(v.id) === String(v1?.id) || String(v.id) === String(v2?.id)
                    );
                });

                if (!hasVehicleSpecs) return;

                html += `<div class="mt-8">`;
                html += `<h2 class="text-lg font-semibold text-gray-800 uppercase border-l-8 border-purple-900 pl-4 py-2 mb-1">${cat.name}</h2>`;
                html += `<table class="w-full text-sm text-left border border-gray-200 rounded-lg">`;

                cat.specs.forEach(spec => {
                    const veh1 = spec.vehicles?.find(v => String(v.id) === String(v1?.id));
                    const veh2 = spec.vehicles?.find(v => String(v.id) === String(v2?.id));

                    if (!veh1 && !veh2) return;

                    let val1 = '-';
                    let val2 = '-';
                    let isDiff = false;

                    switch (spec.type) {
                        case 'price':
                            val1 = veh1?.pivot?.value ? formatCurrency(veh1.pivot.value, spec.unit) : '-';
                            val2 = veh2?.pivot?.value ? formatCurrency(veh2.pivot.value, spec.unit) : '-';
                            if (veh1?.pivot?.value_desc) val1 += ` (${veh1.pivot.value_desc})`;
                            if (veh2?.pivot?.value_desc) val2 += ` (${veh2.pivot.value_desc})`;
                            break;
                        case 'unit':
                            if (spec.name.toLowerCase().includes('tahun')) {
                                const val1Num = veh1?.pivot?.value ? Math.round(veh1.pivot.value) : null;
                                const val2Num = veh2?.pivot?.value ? Math.round(veh2.pivot.value) : null;
                                val1 = val1Num !== null ? `${val1Num} ${spec.unit || ''}` : '-';
                                val2 = val2Num !== null ? `${val2Num} ${spec.unit || ''}` : '-';
                            } else {
                                val1 = veh1?.pivot?.value ? `${formatNumber(veh1.pivot.value)} ${spec.unit || ''}` : '-';
                                val2 = veh2?.pivot?.value ? `${formatNumber(veh2.pivot.value)} ${spec.unit || ''}` : '-';
                            }
                            if (veh1?.pivot?.value_desc) val1 += ` (${veh1.pivot.value_desc})`;
                            if (veh2?.pivot?.value_desc) val2 += ` (${veh2.pivot.value_desc})`;
                            break;
                        case 'list':
                            val1 = veh1?.pivot?.lists?.map(item => item.list).join(', ') || '-';
                            val2 = veh2?.pivot?.lists?.map(item => item.list).join(', ') || '-';
                            if (veh1?.pivot?.value_desc) val1 += ` (${veh1.pivot.value_desc})`;
                            if (veh2?.pivot?.value_desc) val2 += ` (${veh2.pivot.value_desc})`;
                            break;
                        case 'description':
                            val1 = veh1?.pivot?.value_desc || '-';
                            val2 = veh2?.pivot?.value_desc || '-';
                            break;
                        case 'availability':
                            val1 = veh1?.pivot?.value_bool === 1 ? 'Tersedia' : 'Tidak tersedia';
                            val2 = veh2?.pivot?.value_bool === 1 ? 'Tersedia' : 'Tidak tersedia';
                            break;
                        default:
                            val1 = veh1?.pivot?.value ? `${veh1.pivot.value} ${spec.unit || ''}`.trim() : '-';
                            val2 = veh2?.pivot?.value ? `${veh2.pivot.value} ${spec.unit || ''}`.trim() : '-';
                            break;
                    }

                    isDiff = val1 !== val2 && v1 && v2;
                    const diffClass = isDiff ? 'text-purple-600 font-medium' : '';

                    html += `<tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="p-3 w-1/3 font-medium bg-gray-100">${spec.name}</td>
                        <td class="p-3 w-1/3 text-red-600 ${diffClass}">${val1}</td>
                        <td class="p-3 w-1/3 text-blue-600 ${diffClass}">${val2}</td>
                    </tr>`;

                    if (isDiff && spec.description) {
                        html += `<tr class="border-b border-gray-200">
                            <td colspan="3" class="p-3 text-sm text-gray-600 bg-gray-50">${spec.description}</td>
                        </tr>`;
                    }
                });

                html += `</table></div>`;
            });

            return html;
        }

        function formatNumber(value) {
            if (!value) return '';
            return String(value).replace('.', ',');
        }

        function formatCurrency(value, unit) {
            if (!value) return '';
            return `${unit} ${new Intl.NumberFormat('id-ID').format(value)},-`;
        }

        autocomplete(vehicle1Input, vehicle1Suggestions);
        autocomplete(vehicle2Input, vehicle2Suggestions);

        if (vehicle1Input.value.trim()) {
            fetchCompareResult();
        }
    });
</script>