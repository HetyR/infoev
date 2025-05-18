<x-layouts.main>
    <x-slot:title>{{ $title }} - InfoEV</x-slot>

    <x-slot:meta>
        {{-- Meta tags bisa ditambahkan di sini --}}
    </x-slot>

    {{-- Header --}}
    <x-slot:header>
        <x-menu.navbar :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
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

    {{-- Content Section --}}
    {{-- Title Header --}}
    @if (isset($banner) && !is_null($banner))
        <x-menu.title-header :img="$banner" title="{{ $title }}" />
    @else
        <x-menu.title-header title="{{ $title }}" />
    @endif

    {{-- Product List Container --}}
    <div class="flex-1 [&>:last-child]:mb-3 md:mt-0">
        {{-- Mobile Title --}}
        <div class="flex justify-between items-center md:hidden">
            <h2 class="pl-3 py-1 border-l-8 border-purple-900 uppercase font-bold text-slate-800">{{ $title }}</h2>
        </div>
        {{-- End Mobile Title --}}

        {{-- Filter by Brand --}}
        <div class="mt-4 px-3 md:px-6">
            <form method="GET" class="w-full max-w-xs">
                <label for="brand" class="block mb-1 text-sm font-semibold text-slate-700">Filter berdasarkan Merek</label>
                <select name="brand" id="brand" onchange="this.form.submit()" class="w-full border rounded px-3 py-2 shadow-sm focus:outline-none focus:ring focus:ring-purple-200">
                    <option value="">Semua Merek</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ (string) request('brand') === (string) $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

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
        {{-- End Product List --}}

        {{ $vehicles->onEachSide(2)->links() }}
    </div>
    {{-- End Product List Container --}}
    {{-- End Content Section --}}

    {{-- Footer --}}
    <x-slot:footer>
        <x-menu.footer :logo="$logo" />
    </x-slot>
    {{-- End Footer --}}
</x-layouts.main>
