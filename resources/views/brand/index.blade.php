<x-layouts.main>
    <x-slot:title>Daftar Merek - InfoEV</x-slot>

    <x-slot:meta>
        {{-- Meta tags tambahan jika perlu --}}
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
        <x-menu.title-header :img="$banner" title="Semua Merek" />
    @else
        <x-menu.title-header title="Semua Merek" />
    @endif

    {{-- Filter by Vehicle Type --}}
    <div class="mt-4 px-3 md:px-6">
        <form method="GET" class="w-full max-w-xs">
            <label for="vehicle_type" class="block mb-1 text-sm font-semibold text-slate-700">Filter berdasarkan Jenis Kendaraan</label>
            <select name="type" id="vehicle_type" onchange="this.form.submit()" class="w-full border rounded px-3 py-2 shadow-sm focus:outline-none focus:ring focus:ring-purple-200">
                <option value="">Semua Jenis</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
    {{-- End Filter by Vehicle Type --}}


    {{-- Brand Container --}}
    <div class="flex-1 md:mt-0 md:p-3">
        {{-- Mobile Title --}}
        <div class="flex justify-between items-center md:hidden">
            <h2 class="pl-3 py-1 border-l-8 border-purple-900 uppercase font-bold text-slate-800">Semua Merek</h2>
        </div>
        {{-- End Mobile Title --}}

        {{-- Brand List --}}
        <div class="mt-2 md:mt-0 md:px-7 md:py-8 md:columns-2">
            @unless ($items->isEmpty())
                @foreach ($items as $item)
                    <x-menu.list :item="$item" :link="route('brand.show', ['brand' => $item->slug])" />
                @endforeach
            @else
                <div class="px-2 py-4 text-center text-2xl md:px-0">
                    Merek tidak ditemukan
                </div>
            @endunless
        </div>
        {{-- End Brand List --}}
    </div>
    {{-- End Brand Container --}}
    {{-- End Content Section --}}

    {{-- Footer --}}
    <x-slot:footer>
        <x-menu.footer :logo="$logo" />
    </x-slot>
    {{-- End Footer --}}
</x-layouts.main>
