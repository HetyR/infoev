<section class="hidden container mx-auto bg-neutral-200 border-b border-zinc-300 md:block">
    <ul class="flex font-semibold text-slate-800 justify-between">
        <div class="flex">
            <li>
                <a href="{{ route('home') }}" class="block px-8 py-4 hover:bg-purple-900 hover:text-white">HOME</a>
            </li>
            <li>
                <a href="{{ route('brand.index') }}" class="block px-8 py-4 hover:bg-purple-900 hover:text-white">MEREK</a>
            </li>
            <li>
                <a href="{{ route('type.index') }}" class="block px-8 py-4 hover:bg-purple-900 hover:text-white">TIPE</a>
            </li>
            <li>
                <a href="{{ route('blog.index') }}" class="block px-8 py-4 hover:bg-purple-900 hover:text-white">BERITA</a>
            </li>
            <li>
                <a href="{{ route('compare.index') }}" class="block px-8 py-4 hover:bg-purple-900 hover:text-white">COMPARE</a>
            </li>
            @auth
                @if (auth()->user()->role == 1)
                    <li>
                        <a href="{{ route('backend.dashboard') }}" class="block px-8 py-4 hover:bg-purple-900 hover:text-white">DASHBOARD</a>
                    </li>
                @endif
            @endauth
            {{-- <li>
                <a href="{{ route('finder.index') }}" class="block px-8 py-4 hover:bg-purple-900 hover:text-white">FINDER</a>
            </li> --}}
        </div>
        {{-- <li>
            <a href="{{ route('wishlist.index') }}" class="block px-8 py-4 hover:bg-purple-900 hover:text-white">My Wishlist</a>
        </li> --}}
    </ul>
</section>
