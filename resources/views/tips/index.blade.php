<x-layouts.main>
    <x-slot:title>Berita - InfoEV</x-slot>

    <x-slot:meta></x-slot>

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

     {{-- Title Header --}}
    @if (isset($banner) && !is_null($banner))
        <x-menu.title-header :img="$banner" title="Tips & Trick" />
    @else
        <x-menu.title-header title="Tips & Trick" />
    @endif

    <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-8 px-6 py-8">
        <main class="flex-1 md:w-3/4">
            <h2
                class="hidden md:block mt-4 pl-4 py-2 border-l-8 border-purple-900 uppercase font-bold text-slate-800 mb-6">
                Tips & Trick
            </h2>


            <div class="space-y-8">
                @forelse ($tipsAndTricks as $tips)
                    @if ($tips->blog?->thumbnail)
                        <x-blog.regular-post img="{{ $tips->blog->thumbnail->path }}" :post="$tips->blog" />
                    @else
                        <x-blog.regular-post :post="$tips->blog" />
                    @endif
                @empty
                    <div class="px-4 py-6 text-center text-2xl md:px-0">
                        Tips & Trick tidak ditemukan
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $tipsAndTricks->onEachSide(2)->links() }}
            </div>
        </main>
    </div>
    {{-- End Content Section --}}

    {{-- Footer --}}
    <x-slot:footer>
        <x-menu.footer :logo="$logo" />
    </x-slot>
    {{-- End Footer --}}
</x-layouts.main>
