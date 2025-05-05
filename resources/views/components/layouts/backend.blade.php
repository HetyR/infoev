<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Backend - InfoEV</title>
    
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Laravel Asset Styles -->
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">

    <!-- GA Tag -->
    <meta name="robots" content="noindex, nofollow">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'UA-94034622-3');
    </script>

    <!-- Vite & Optional Style Injection -->
    {{ $css ?? '' }}
    @vite('resources/css/app.css')

    <!-- AlpineJS -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">

@auth
<!-- Navbar -->
<nav class="bg-white border-b border-gray-200 shadow-sm" x-data="{ open: false, profileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo & Menu -->
            <div class="flex items-center space-x-4">
                <span class="text-xl font-bold text-indigo-600">Admin Panel</span>

                <div class="hidden md:flex space-x-4">
                    @php
                        $menus = [
                            ['name' => 'Types', 'route' => 'backend.type.index'],
                            ['name' => 'Brands', 'route' => 'backend.brand.index'],
                            ['name' => 'Blogs', 'route' => 'backend.blog.index'],
                            ['name' => 'Sticky Articles', 'route' => 'backend.stickyArticle.index'],
                            ['name' => 'Specifications', 'route' => 'backend.spec.index'],
                            ['name' => 'Vehicles', 'route' => 'backend.vehicle.index'],
                            ['name' => 'Marketplaces', 'route' => 'backend.marketplace.index'],
                            ['name' => 'Comments', 'route' => 'backend.comment.index'],
                            ['name' => 'Settings', 'route' => 'backend.option.index'],
                        ];
                    @endphp

                    @foreach ($menus as $menu)
                        <a href="{{ route($menu['route']) }}"
                        class="relative px-3 py-2 text-sm font-medium transition duration-200
                        @if (Route::is($menu['route']))
                            text-indigo-600 after:absolute after:left-0 after:bottom-0 after:w-full after:h-0.5 after:bg-indigo-500
                        @else
                            text-gray-600 hover:text-indigo-500 hover:after:absolute hover:after:left-0 hover:after:bottom-0 hover:after:w-full hover:after:h-0.5 hover:bg-indigo-300
                        @endif">
                            {{ $menu['name'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- User Profile -->
            <div class="hidden md:flex items-center space-x-4 relative">
                <button @click="profileOpen = !profileOpen" class="flex items-center space-x-2 focus:outline-none">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5.121 17.804A9.004 9.004 0 0112 15a9.004 9.004 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm text-gray-700">Admin</span>
                </button>

                <div x-show="profileOpen" @click.away="profileOpen = false"
                     class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-md overflow-hidden z-50">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-red-50 hover:text-red-600">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile Toggle -->
            <div class="md:hidden">
                <button @click="open = !open" class="text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="md:hidden bg-white px-4 pt-2 pb-4 space-y-1">
        @foreach ($menus as $menu)
            <a href="{{ route($menu['route']) }}"
               class="block text-sm py-2 
               @if (Route::is($menu['route'])) text-indigo-600 font-semibold 
               @else text-gray-600 hover:text-indigo-500 @endif">
                {{ $menu['name'] }}
            </a>
        @endforeach
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="block w-full text-left text-sm text-gray-600 hover:text-red-600 py-2">
                Logout
            </button>
        </form>
    </div>
</nav>

<!-- Judul Halaman -->
<div class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 lg:pl-64">
        <h1 class="text-lg font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
    </div>
</div>
@endauth

<!-- Main Content -->
<main class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 lg:pl-64">
        {{ $slot }}
    </div>
</main>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
</script>

<script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
<script src="{{ asset('assets/modules/popper.js') }}"></script>
<script src="{{ asset('assets/modules/tooltip.js') }}"></script>
<script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
<script src="{{ asset('assets/modules/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/stisla.js') }}"></script>

<script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
<script src="{{ asset('assets/modules/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

{{ $js ?? '' }}

</body>
</html>
