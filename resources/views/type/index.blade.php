<x-layouts.main>
    <x-slot:title>Daftar Tipe - InfoEV</x-slot>

    <x-slot:meta>

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
        <x-menu.title-header :img="$banner" title="Semua Tipe" />
    @else
        <x-menu.title-header title="Semua Tipe" />
    @endif

    {{-- Type Container --}}
    <div class="flex-1 md:mt-0 md:p-3">
        {{-- Mobile Title --}}
        <div class="flex justify-between items-center md:hidden">
            <h2 class="pl-3 py-1 border-l-8 border-purple-900 uppercase font-bold text-slate-800">Semua Merek</h2>
        </div>
        {{-- End Mobile Title --}}

        {{-- Type List --}}
        <div class="mt-2 md:mt-0 md:px-7 md:py-8 md:columns-2">

            @unless ($items->isEmpty())
                @foreach ($items as $item)
                    <x-menu.list :item="$item" :link="route('type.show', ['type' => $item->slug])" />
                @endforeach
            @else
                <div class="px-2 py-4 text-center text-2xl md:px-0">
                    Tipe tidak ditemukan
                </div>
            @endunless

        </div>
        {{-- End Type List --}}
    </div>
    {{-- End Type Container --}}
    {{-- End Content Section --}}

    {{-- Footer --}}
    <x-slot:footer>
        <x-menu.footer :logo="$logo" />
    </x-slot>
    {{-- End Footer --}}
</x-layouts.main>