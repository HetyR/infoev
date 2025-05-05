@props(['logo', 'bikeBrands', 'carBrands'])

<nav class="sticky top-0 bg-white border-b border-zinc-300 z-30 md:static">
    <div class="container mx-auto px-3 py-2 bg-white text-slate-800 md:px-4">
        <div class="flex justify-between space-x-6 min-h-10 md:min-h-initial">
            {{-- Logo --}}
            <div class="mobile-menu flex items-center space-x-3 md:max-w-xs">
                <button class="block mt-1 hamburger focus:outline-none md:hidden" data-menu-btn
                    data-menu-target="main-menu">
                    <span class="hamburger-top"></span>
                    <span class="hamburger-middle"></span>
                    <span class="hamburger-bottom"></span>
                </button>

                <a href="{{ route('home') }}">
                    @if ($logo && $logo->thumbnail)
                        <img src="{{ asset('storage/' . $logo->thumbnail->path) }}" class="w-full max-w-[200px]" alt="Logo Info EV">
                    @else
                        <img src="{{ asset('images/default-logo.png') }}" class="w-full max-w-[200px]" alt="Default Logo">
                    @endif
                </a>
            </div>
            {{-- End Logo --}}

            {{-- Search Bar --}}
            <form action="{{ route('search') }}" class="hidden items-stretch md:flex">
                <input type="text"
                    class="px-2 py-1 w-80 max-w-full h-full border border-purple-900 z-10 placeholder:text-gray-300"
                    name="q" placeholder="Cari">
                <button class="px-3 py-2 bg-white border-y border-r border-purple-900 z-0 hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </button>
            </form>
            {{-- End Search Bar --}}

            {{-- Icons --}}
            <div class="flex justify-end items-center space-x-1 md:hidden">
                <button data-menu-btn data-menu-target="search-menu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </button>
            </div>
            {{-- End Icons --}}

            {{-- Wishlist dan Tautan Pengguna --}}
            <div class="flex items-center space-x-3">
                @guest
                    <a href="{{ route('auth.login') }}" class="text-gray-300 hover:text-purple-500">
                        <i class="fas fa-user"></i> LOGIN
                    </a>
                @endguest

                @auth
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('keranjang.index') }}" class="block px-2 hover:text-grey shadow-inner">
                            <i class="fas fa-heart text-2xl"></i>
                        </a>
                        <div class="relative">
                            <button class="flex items-center space-x-2 focus:outline-none" id="user-menu-button">
                                <span>{{ auth()->user()->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg hidden" id="user-menu">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Keluar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
            {{-- End Wishlist dan Tautan Pengguna --}}
        </div>
    </div>

    {{-- Menu Mobile --}}
    @if (isset($bikeBrands) && isset($carBrands))
        <x-menu.mobile-menu :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
    @else
        <x-menu.mobile-menu />
    @endif
    {{-- End Menu Mobile --}}
</nav>

<script>
    document.getElementById('user-menu-button')?.addEventListener('click', function () {
        document.getElementById('user-menu')?.classList.toggle('hidden');
    });
</script>
