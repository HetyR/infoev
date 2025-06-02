@props(['bikeBrands', 'carBrands'])

<div>
    {{-- Menu --}}
    <div class="md:hidden">
        <div class="absolute top-menu left-0 right-0 -translate-y-[40rem] px-6 py-5 bg-white border-y border-zinc-300 drop-shadow-md transition-transform ease-in-out duration-300 -z-20" data-main-menu>
            <section>
                <ul class="columns-2 text-sm">
                    <li><a href="{{ route('home') }}" class="block px-3 py-1 font-semibold">HOME</a></li>
                    <li><a href="{{ route('brand.index') }}" class="block px-3 py-1 font-semibold">MEREK</a></li>
                    <li><a href="{{ route('type.index') }}" class="block px-3 py-1 font-semibold">TIPE</a></li>
                    <li><a href="{{ route('blog.index') }}" class="block px-3 py-1 font-semibold">BERITA</a></li>
                    <li><a href="{{ route('compare.index') }}" class="block px-3 py-1 font-semibold">COMPARE</a></li>
                    <li><a href="{{ route('finder.index') }}" class="block px-3 py-1 font-semibold">FINDER</a></li>
                    @auth
                        <li><a href="{{ route('backend.type.index') }}" class="block px-3 py-1 font-semibold">BACKEND</a></li>
                    @endauth
                </ul>
            </section>

            {{-- Brands 1 --}}
            <section class="mt-5">
                <ul class="columns-3 text-sm">
                    @if (isset($bikeBrands))
                        @foreach ($bikeBrands as $brand)
                            <li class="block break-inside-avoid"><a href="{{ route('brand.show', ['brand' => $brand->slug]) }}" class="block px-3 py-1">{{ $brand->name }}</a></li>
                        @endforeach
                    @endif

                    <li class="inline-block"><a href="{{ route('type.show', ['type' => 'sepeda-motor']) }}" class="block px-3 py-1">[Semua Motor]</a></li>
                </ul>
            </section>
            {{-- End Brands 1 --}}

            <hr class="my-3 border border-purple-900 md:border-gray-300">

            {{-- Brands 2 --}}
            <section class="mt-5">
                <ul class="columns-3 text-sm">
                    @if (isset($carBrands))
                        @foreach ($carBrands as $brand)
                            <li class="block break-inside-avoid"><a href="{{ route('brand.show', ['brand' => $brand->slug]) }}" class="block px-3 py-1">{{ $brand->name }}</a></li>
                        @endforeach
                    @endif

                    <li class="inline-block"><a href="{{ route('type.show', ['type' => 'mobil']) }}" class="block px-3 py-1">[Semua Mobil]</a></li>
                </ul>
            </section>
            {{-- End Brands 2 --}}

            <section class="mt-5 text-center">
                <a href="{{ route('brand.index') }}" class="block px-5 py-1 bg-gray-800 text-white hover:bg-purple-900 hover:text-white">Cari EV</a>
            </section>
        </div>
    </div>
    {{-- End Menu --}}

    {{-- Search --}}
    <nav class="md:hidden">
        <div class="absolute top-menu left-0 right-0 -translate-y-[40rem] px-6 py-5 bg-white border-y border-zinc-300 drop-shadow-md transition-transform ease-in-out duration-300 -z-20" data-search-menu>
            <section>
                <form action="{{ route('search') }}" class="flex">
                    <input type="text" class="px-2 py-0.5 w-full border border-purple-900 z-10 placeholder:text-gray-300" name="q" placeholder="Cari">
                    <button class="px-2 py-1 bg-white border-y border-r border-purple-900 z-0 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                        </svg>
                    </button>
                </form>
            </section>
        </div>
    </nav>
    {{-- End Search --}}
</div>
