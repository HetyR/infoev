<x-layouts.main>
    <x-slot:title>{{ $title }} Keranjang - InfoEV</x-slot>

    {{-- Meta tags --}}
    <x-slot:meta>
        {{-- Tambahkan meta tags jika diperlukan --}}
    </x-slot>

    {{-- Header --}}
    <x-slot:header>
        <x-menu.navbar :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
        <x-menu.menu />
    </x-slot>

    {{-- Sidebar --}}
    <x-slot:sidebar>
        <x-sidebar.latest :recentVehicles="$recentVehicles" />
        <x-sidebar.top :popularVehicles="$popularVehicles" />
    </x-slot>

    {{-- Konten Utama --}}
    <div class="bg-white min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Header Halaman -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Kendaraan Favorit</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">Temukan dan kelola koleksi kendaraan listrik favorit Anda</p>
            </div>

            <!-- Grid Kartu Kendaraan -->
            @if ($informasiKendaraan && count($informasiKendaraan) > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach ($informasiKendaraan as $kendaraan)
                        @php
                            // Pastikan URL gambar menggunakan HTTPS
                            $imageUrl = $kendaraan['gambar'] ?? '';
                            if (!empty($imageUrl)) {
                                // Force HTTPS jika menggunakan HTTP
                                $imageUrl = str_replace('http://', 'https://', $imageUrl);
                                
                                // Jika bukan full URL, buat jadi full URL
                                if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                    $imageUrl = secure_asset('storage/vehicle/' . basename($imageUrl));
                                }
                            }
                        @endphp

                        <div class="bg-white rounded shadow-sm border border-gray-100 hover:shadow-md hover:border-gray-200 transition-all duration-300 overflow-hidden group">
                            <!-- Gambar Kendaraan -->
                            <div class="relative bg-gray-50 overflow-hidden" style="min-height: 200px;">
                                <a href="{{ route('vehicle.show', ['vehicle' => $kendaraan['slug']]) }}" class="block h-full">
                                    @if(!empty($imageUrl))
                                        <!-- Gambar Utama dengan style inline untuk memastikan tampil -->
                                        <img src="{{ $imageUrl }}"
                                            class="w-full h-48 object-contain group-hover:scale-105 transition-transform duration-500"
                                            alt="{{ ($kendaraan['merek'] ?? '') . ' ' . ($kendaraan['nama'] ?? '') }}"
                                            loading="lazy"
                                            style="display: block !important; visibility: visible !important; opacity: 1 !important; background: #f9fafb; max-height: 192px;"
                                            onload="this.style.opacity='1'; console.log('✓ Image loaded:', this.src);"
                                            onerror="console.error('✗ Image failed:', this.src); this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        
                                        <!-- Placeholder untuk gambar yang gagal load -->
                                        <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200 hidden items-center justify-center">
                                            <div class="text-center">
                                                <svg class="w-8 h-8 text-gray-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <p class="text-gray-500 text-xs">Gambar Tidak Tersedia</p>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Placeholder jika tidak ada gambar -->
                                        <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <div class="text-center">
                                                <svg class="w-8 h-8 text-gray-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <p class="text-gray-500 text-xs">Tidak Ada Gambar</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Overlay Hover -->
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                                        <span class="bg-white text-gray-800 px-2 py-1 rounded text-xs font-medium opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                                            Lihat
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <!-- Informasi Kendaraan -->
                            <div class="p-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-sm text-gray-900 mb-0.5 truncate">
                                            {{ $kendaraan['merek'] ?? 'Merek Tidak Diketahui' }}
                                        </h3>
                                        <p class="text-gray-600 text-xs truncate">
                                            {{ $kendaraan['nama'] ?? 'Model Tidak Diketahui' }}
                                        </p>
                                        
                                        {{-- Debug URL gambar (hapus setelah fix) --}}
                                        @if(config('app.debug'))
                                            <div class="text-xs text-blue-600 mt-1 break-all">
                                                URL: {{ $imageUrl }}
                                            </div>
                                            <div class="text-xs text-green-600">
                                                Original: {{ $kendaraan['gambar'] ?? 'null' }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('keranjang.remove', ['vehicleId' => $kendaraan['id']]) }}" method="POST" class="flex-shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus dari favorit"
                                                onclick="return confirm('Hapus kendaraan ini?')"
                                                class="flex items-center justify-center w-7 h-7 bg-red-50 hover:bg-red-100 text-red-500 hover:text-red-600 rounded transition-all duration-200 hover:scale-105">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3.5 h-3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Jumlah Hasil -->
                <div class="mt-8 text-center">
                    <p class="text-gray-500 text-sm">
                        Menampilkan {{ count($informasiKendaraan) }} kendaraan favorit
                    </p>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Belum Ada Kendaraan Favorit</h3>
                        <p class="text-gray-600 mb-6">Anda belum menambahkan kendaraan apapun ke favorit. Mulailah menjelajahi koleksi kami untuk menemukan kendaraan yang Anda sukai!</p>
                        <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Jelajahi Kendaraan
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- CSS Override untuk memastikan gambar tampil -->
    <style>
        /* Force display gambar */
        .relative img {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        /* Pastikan container tidak hidden */
        .relative {
            position: relative !important;
            overflow: visible !important;
        }
        
        /* Debug border untuk development */
        @if(config('app.debug'))
        img[src*="storage/vehicle"] {
            border: 2px solid #10b981 !important;
            box-shadow: 0 0 0 1px #10b981 !important;
        }
        @endif
        
        /* Anti-conflict dengan CSS lain */
        .grid img {
            max-width: 100% !important;
            height: auto !important;
            object-fit: contain !important;
        }
    </style>

    <!-- JavaScript debug sederhana -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Log semua gambar vehicle
            const vehicleImages = document.querySelectorAll('img[src*="storage/vehicle"]');
            console.log('🚗 Found', vehicleImages.length, 'vehicle images');
            
            vehicleImages.forEach((img, index) => {
                console.log(`📸 Image ${index + 1}:`, {
                    src: img.src,
                    display: getComputedStyle(img).display,
                    visibility: getComputedStyle(img).visibility,
                    opacity: getComputedStyle(img).opacity,
                    width: img.clientWidth,
                    height: img.clientHeight
                });
                
                // Force tampilkan gambar
                img.style.display = 'block';
                img.style.visibility = 'visible';
                img.style.opacity = '1';
            });
            
            // Check setelah 2 detik
            setTimeout(() => {
                const hiddenImages = Array.from(vehicleImages).filter(img => 
                    getComputedStyle(img).display === 'none' || 
                    getComputedStyle(img).visibility === 'hidden' ||
                    getComputedStyle(img).opacity === '0'
                );
                
                if (hiddenImages.length > 0) {
                    console.warn('⚠️ Found', hiddenImages.length, 'hidden images:', hiddenImages);
                    
                    // Force show hidden images
                    hiddenImages.forEach(img => {
                        img.style.setProperty('display', 'block', 'important');
                        img.style.setProperty('visibility', 'visible', 'important');
                        img.style.setProperty('opacity', '1', 'important');
                    });
                }
            }, 2000);
        });
        
        // Function untuk test reload gambar
        function testReloadImages() {
            const images = document.querySelectorAll('img[src*="storage/vehicle"]');
            images.forEach(img => {
                const originalSrc = img.src;
                img.src = '';
                setTimeout(() => {
                    img.src = originalSrc + '?t=' + Date.now();
                }, 100);
            });
        }
        
        // Auto-fix gambar yang mungkin ter-hide oleh CSS
        setInterval(() => {
            const vehicleImages = document.querySelectorAll('img[src*="storage/vehicle"]');
            vehicleImages.forEach(img => {
                if (getComputedStyle(img).display === 'none') {
                    console.log('🔧 Auto-fixing hidden image:', img.src);
                    img.style.setProperty('display', 'block', 'important');
                }
            });
        }, 5000);
    </script>

    {{-- Footer --}}
    <x-slot:footer>
        <x-menu.footer :logo="$logo"
            :bikeBrands="$bikeBrands"
            :carBrands="$carBrands"
            :recentVehicles="$recentVehicles"
            :popularVehicles="$popularVehicles"
            :featuredArticles="$stickies" />
    </x-slot>
</x-layouts.main>