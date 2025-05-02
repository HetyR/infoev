<x-layouts.main>
    <x-slot:title>{{ $post->title }} - InfoEV</x-slot>

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
    {{-- Posts Detail --}}
    <article class="px-4 space-y-4 text-slate-800">
        <div>
            <h2 class="mb-2 font-semibold md:text-2xl">{{ $post->title }}</h2>
            @if ($post->published)
                <span class="text-xs font-light md:text-sm">{{ $post->created_at->format('d F Y') }}</span>
            @endif
        </div>

        @if (isset($post->thumbnail) && !is_null($post->thumbnail))
            <img src="{{ asset('storage/' . $post->thumbnail->path) }}" class="w-full" alt="">
        @endif

        <div class="pb-5 space-y-5 article">
            {!! $post->content !!}
        </div>
    </article>
    {{-- End Posts Detail --}}
    {{-- End Content Section --}}

    {{-- Footer --}}
    <x-slot:footer>
        <x-menu.footer :logo="$logo" />
    </x-slot>
    {{-- End Footer --}}
</x-layouts.main>