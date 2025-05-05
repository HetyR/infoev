<x-layouts.main>
    <x-slot:title>Berita - InfoEV</x-slot>

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
        <x-menu.title-header :img="$banner" title="Berita" />
    @else
        <x-menu.title-header title="Berita" />
    @endif

    {{-- Posts List --}}
    <div class="flex-1 md:mt-0 md:p-3">
        {{-- Mobile Title --}}
        <div class="flex justify-between items-center md:hidden">
            <h2 class="pl-3 py-1 border-l-8 border-purple-900 uppercase font-bold text-slate-800">Berita</h2>
        </div>
        {{-- End Mobile Title --}}

        {{-- News Search --}}
        <form action="{{ route('blog.index') }}">
            <div class="flex items-center mt-3 px-2 pb-4 text-sm md:px-6 md:py-3">
                <h3 class="hidden mr-3 md:block">Cari Berita</h3>
                <input type="text" class="flex-1 mr-3 px-2 py-0.5 border border-slate-800 md:flex-initial" name="q" value="{{ request('q') }}">
                <button type="submit" class="px-3 py-1 bg-slate-800 text-white uppercase hover:bg-purple-900">Cari</button>
            </div>
        </form>
        {{-- End News Search --}}

        <div class="space-y-3">
            {{-- Post List --}}
            @unless ($posts->isEmpty())
                @foreach ($posts as $post)
                    @if ($post->featured == true)
                        {{-- Full Post --}}
                        @if (isset($post->thumbnail) && !is_null($post->thumbnail))
                            <x-blog.full-post img="{{ $post->thumbnail->path }}" :post="$post" />
                        @else
                            <x-blog.full-post :post="$post" />
                        @endif
                    @else
                        {{-- Regular Post --}}
                        @if (isset($post->thumbnail) && !is_null($post->thumbnail))
                            <x-blog.regular-post img="{{ $post->thumbnail->path }}" :post="$post" />
                        @else
                            <x-blog.regular-post :post="$post" />
                        @endif
                    @endif
                @endforeach
            @else
                <div class="px-2 py-4 text-center text-2xl md:px-0">
                    Berita tidak ditemukan
                </div>
            @endunless
            {{-- End Post List --}}
        </div>

        {{ $posts->onEachSide(2)->links() }}
    </div>
    {{-- End Posts List --}}
    {{-- End Content Section --}}

    {{-- Footer --}}
    <x-slot:footer>
        <x-menu.footer :logo="$logo" />
    </x-slot>
    {{-- End Footer --}}
</x-layouts.main>