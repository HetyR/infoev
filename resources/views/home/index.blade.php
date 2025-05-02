<x-layouts.main>
    <x-slot:title>InfoEV - Berita Kendaraan Listrik, Spesifikasi, Komparasi dan Lainnya</x-slot>

    <x-slot:meta></x-slot>

    {{-- Header --}}
    <x-slot:header>
        <x-menu.navbar :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
        <x-menu.menu />
    </x-slot>

    {{-- Sidebar --}}
    <x-slot:sidebar>
        <x-sidebar.brand-menu :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
        <x-sidebar.latest :recentVehicles="$recentVehicles" />
        <x-sidebar.top :popularVehicles="$popularVehicles" />
        <x-sidebar.featured :featuredArticles="$stickies" />
    </x-slot>

    {{-- Content Section --}}
    <div class="hidden md:grid md:grid-cols-5 md:gap-0.5">
        {{-- Featured Post 1 --}}
        @php $sticky0 = $stickies->get(0); @endphp
        @if ($sticky0)
            <article class="relative min-h-40 md:col-span-3 md:row-span-2">
                <a href="{{ route('blog.show', ['blog' => $sticky0->slug]) }}" class="group flex flex-col h-full">
                    <div class="absolute w-full h-full z-0">
                        @if ($sticky0->thumbnail)
                            <img src="{{ asset('storage/' . $sticky0->thumbnail->path) }}" class="w-full h-full object-cover group-hover:brightness-105" alt="{{ $sticky0->title }}">
                        @else
                            <img src="{{ asset('img/placeholder-md.png') }}" class="w-full h-full object-cover group-hover:brightness-105" alt="{{ $sticky0->title }}">
                        @endif
                    </div>
                    <h2 class="relative mt-auto p-4 z-10 bg-black bg-opacity-30 text-white text-2xl font-semibold leading-relaxed group-hover:underline">
                        {{ $sticky0->title }}
                    </h2>
                </a>
            </article>
        @endif

        {{-- Featured Post 2 --}}
        @php $sticky1 = $stickies->get(1); @endphp
        @if ($sticky1)
            <article class="relative md:col-span-2">
                <a href="{{ route('blog.show', ['blog' => $sticky1->slug]) }}" class="group flex flex-col h-full">
                    <div class="absolute w-full h-full z-0">
                        @if ($sticky1->thumbnail)
                            <img src="{{ asset('storage/' . $sticky1->thumbnail->path) }}" class="w-full h-full object-cover group-hover:brightness-105" alt="{{ $sticky1->title }}">
                        @else
                            <img src="{{ asset('img/placeholder-md.png') }}" class="w-full h-full object-cover group-hover:brightness-105" alt="{{ $sticky1->title }}">
                        @endif
                    </div>
                    <h2 class="relative mt-auto p-2 z-10 bg-black bg-opacity-30 text-white text-lg font-semibold leading-relaxed group-hover:underline">
                        {{ $sticky1->title }}
                    </h2>
                </a>
            </article>
        @endif

        {{-- Featured Post 3 --}}
        @php $sticky2 = $stickies->get(2); @endphp
        @if ($sticky2)
            <article class="relative md:col-span-2">
                <a href="{{ route('blog.show', ['blog' => $sticky2->slug]) }}" class="group flex flex-col h-full transition-all">
                    <div class="absolute w-full h-full z-0">
                        @if ($sticky2->thumbnail)
                            <img src="{{ asset('storage/' . $sticky2->thumbnail->path) }}" class="w-full h-full object-cover group-hover:brightness-105" alt="{{ $sticky2->title }}">
                        @else
                            <img src="{{ asset('img/placeholder-md.png') }}" class="w-full h-full object-cover group-hover:brightness-105" alt="{{ $sticky2->title }}">
                        @endif
                    </div>
                    <h2 class="relative mt-auto p-2 z-10 bg-black bg-opacity-30 text-white text-lg font-semibold leading-relaxed group-hover:underline">
                        {{ $sticky2->title }}
                    </h2>
                </a>
            </article>
        @endif
    </div>

    {{-- Featured Posts Mobile --}}
    <div class="md:hidden">
        <h2 class="pl-3 py-1 border-l-8 border-purple-900 uppercase font-bold text-slate-800">Berita Pilihan</h2>
        <div class="flex mt-2 px-3 space-x-2 text-slate-800 overflow-x-auto snap-x scroll-hide">
            @foreach ($stickies as $sticky)
                @if ($sticky)
                    <article class="flex-shrink-0 border border-zinc-300 max-w-10 snap-center">
                        <a href="{{ route('blog.show', ['blog' => $sticky->slug]) }}" class="flex-col">
                            <div class="w-full">
                                @if ($sticky->thumbnail)
                                    <img src="{{ asset('storage/' . $sticky->thumbnail->path) }}" class="w-full object-cover" alt="{{ $sticky->title }}">
                                @else
                                    <img src="{{ asset('img/placeholder-md.png') }}" class="w-full object-cover" alt="{{ $sticky->title }}">
                                @endif
                            </div>
                            <h2 class="mt-1 px-1 pb-1 text-sm font-semibold">
                                {{ $sticky->title }}
                            </h2>
                        </a>
                    </article>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Posts List --}}
    <div class="mt-5 flex-1 space-y-3 md:mt-0 md:p-3">
        <div class="flex justify-between items-center md:hidden">
            <h2 class="pl-3 py-1 border-l-8 border-purple-900 uppercase font-bold text-slate-800">Berita Terbaru</h2>
            <a href="{{ route('blog.index') }}" class="flex items-center text-sm text-purple-900 font-semibold">
                Lihat Semua
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </a>
        </div>

        {{-- Post List --}}
        @foreach ($posts as $post)
            @if ($post->featured)
                @if ($post->thumbnail)
                    <x-blog.full-post img="{{ $post->thumbnail->path }}" :post="$post" />
                @else
                    <x-blog.full-post :post="$post" />
                @endif
            @else
                @if ($post->thumbnail)
                    <x-blog.regular-post img="{{ $post->thumbnail->path }}" :post="$post" />
                @else
                    <x-blog.regular-post :post="$post" />
                @endif
            @endif
        @endforeach

        <div class="md:flex md:justify-end">
            <a href="{{ route('blog.index') }}" class="block px-4 py-2 w-full bg-slate-800 text-white text-center hover:bg-purple-900 md:inline-block md:w-auto">Lihat Semua</a>
        </div>
    </div>

    {{-- Footer --}}
    <x-slot:footer>
        <x-menu.footer :logo="$logo" />
    </x-slot>
</x-layouts.main>
