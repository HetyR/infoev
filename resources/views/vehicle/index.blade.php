<x-layouts.main>
    <x-slot:title>{{ $title }} - InfoEV</x-slot>

    <x-slot:meta>
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    </x-slot:meta>

    {{-- Header --}}
    <x-slot:header>
        <x-menu.navbar :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
        <x-menu.menu />
    </x-slot>
    {{-- End Header --}}

    {{-- Sidebar --}}
    <x-slot:sidebar>
        <x-sidebar.brand-menu :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
        <x-sidebar.latest :recentVehicles="$recentVehicles" />
        <x-sidebar.top :popularVehicles="$popularVehicles" />
        <x-sidebar.featured :featuredArticles="$stickies" />
    </x-slot>
    {{-- End Sidebar --}}

    {{-- Content Section --}}
    @if (isset($banner) && !is_null($banner))
        <x-menu.title-header :img="$banner" title="{{ $title }}" />
    @else
        <x-menu.title-header title="{{ $title }}" />
    @endif

    <div class="flex-1 [&>:last-child]:mb-3 md:mt-0">
        {{-- Mobile Title --}}
        <div class="flex justify-between items-center md:hidden">
            <h2 class="pl-3 py-1 border-l-8 border-purple-900 uppercase font-bold text-slate-800">
                {{ $title }}
            </h2>
        </div>

        {{-- Filter by Brand --}}
        @if (Route::is("type.show"))
            <div class="mt-4 px-3 md:px-6">
                <form method="GET" class="w-full max-w-xs">
                    <label for="brand" class="block mb-1 text-sm font-semibold text-slate-700">
                        Filter berdasarkan Merek
                    </label>
                    <select name="brand" id="brand-select"
                        class="w-full border rounded px-3 py-2 shadow-sm focus:outline-none focus:ring focus:ring-purple-200">
                        <option value="">Semua Merek</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}"
                                {{ (string) request('brand') === (string) $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        @endif

        {{-- Product List --}}
        <div class="grid mt-2 md:mt-0 md:px-6 md:py-3 md:grid-cols-3">
            @unless ($vehicles->isEmpty())
                @foreach ($vehicles as $vehicle)
                    <x-vehicle.list :vehicle="$vehicle" />
                @endforeach
            @else
                <div class="px-2 py-4 text-center text-2xl md:px-0">
                    Kendaraan tidak ditemukan
                </div>
            @endunless
        </div>

        {{ $vehicles->onEachSide(2)->links() }}
    </div>
    {{-- End Content Section --}}

    {{-- Footer --}}
    <x-slot:footer>
        <x-menu.footer :logo="$logo" />
    </x-slot>

    {{-- Tom Select Script --}}
    <x-slot:js>
        <script>
            new TomSelect('#brand-select', {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                onChange: function(value) {
                    this.input.closest('form').submit();
                }
            });
        </script>
    </x-slot:js>
</x-layouts.main>
