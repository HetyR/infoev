<x-layouts.main>
    <x-slot:title>EV Finder</x-slot>

    <x-slot:meta>
    </x-slot>

    {{-- Header --}}
    <x-slot:header>
        @if (isset($bikeBrands) && isset($carBrands))
            <x-menu.navbar :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
        @else
            <x-menu.navbar :logo="$logo" />
        @endif
        <x-menu.menu />
    </x-slot>
    {{-- End Header --}}

    <x-slot:sidebar>
        {{-- Latest Models --}}
        <x-sidebar.latest :recentVehicles="$recentVehicles" />

        {{-- Top 10 --}}
        <x-sidebar.top :popularVehicles="$popularVehicles" />

        {{-- Featured --}}
        <x-sidebar.featured :featuredArticles="$stickies" />
    </x-slot>
    {{-- End Sidebar --}}
{{-- Title Header --}}
@if (isset($banner) && !is_null($banner))
<x-menu.title-header-f :img="$banner" title="Kebijakan Privasi" />
@else
<x-menu.title-header-f title="Kebijakan Privasi" />
@endif

 {{-- Main Content --}}
 <div class="container mx-auto px-5 py-5">
    {{-- <h1 class="text-3xl font-bold mb-4">Privacy Policy</h1> --}}


     {{-- Kebijakan Privasi--}}
     <h2 class="text-2xl font-semibold mb-2">Kebijakan Privasi</h2>
     <p class="text-medium gray font-inherit mb-4 pr-3">
         Kami menghargai privasi pengguna kami dan berkomitmen untuk melindungi informasi pribadi Anda. Dalam penggunaan aplikasi InfoEV, kami hanya mengumpulkan data yang diperlukan untuk memberikan layanan yang optimal.
     </p>
     <p class="text-medium gray font-inherit mb-4">
        Jenis data yang kami kumpulkan adalah Informasi Pribadi: Kami dapat mengumpulkan nama, alamat email, dan informasi kontak lainnya saat Anda mendaftar atau berinteraksi dengan aplikasi.
     </p>

     {{-- Kebijakan Konten Section --}}
    <h2 class="text-2xl font-semibold mb-2">Kebijakan Konten</h2>
    <p class="text-medium gray font-inherit mb-4">
        Kami di InfoEV berkomitmen untuk memastikan bahwa semua konten yang tersedia di aplikasi kami mematuhi standar kualitas dan kepatuhan yang tinggi. Kebijakan ini menjelaskan jenis konten yang kami izinkan, bagaimana kami menangani konten yang melanggar, serta hak dan tanggung jawab pengguna terkait konten di aplikasi.
    </p>

    {{-- Konten Aplikasi InfoEV Section --}}
    <h2 class="text-2xl font-semibold mb-2">Konten Aplikasi InfoEV</h2>
    <p class="text-medium gray font-inherit mb-4">
        Konten di Aplikasi ini mencakup informasi tentang kendaraan listrik yang telah dirilis di Indonesia. Aplikasi ini menyediakan informasi mengenai spesifikasi mobil, motor, skuter, dan kendaraan khusus dimana terdapat fitur pembanding dan filter data berdasarkan spesifikasi kendaraan.
    </p>
    <p class="text-medium gray font-inherit mb-4">
        Aplikasi ini didirikan untuk menjadi sumber utama bagi pelanggan dan penggemar teknologi listrik dalam melihat perkembangan terbaru dalam industri kendaraan listrik. Namun, kami tidak dapat menjamin keakuratan data tersebut.
    </p>

    {{-- Penggunaan Data Section --}}
    <h2 class="text-2xl font-semibold mb-2">Penggunaan Data</h2>
    <p class="text-medium gray font-inherit mb-4">
        Kami menggunakan data yang kami kumpulkan untuk:
    </p>
    <p class="text-medium gray font-inherit mb-4">
        Mengoptimalkan Layanan: Data membantu kami memahami bagaimana pengguna berinteraksi dengan aplikasi sehingga kami dapat meningkatkan pengalaman pengguna.Melindungi aplikasi dan pengguna dari aktivitas yang tidak sah atau berbahaya.
    </p>

    {{-- Keamanan Data Section --}}
    <h2 class="text-2xl font-semibold mb-2">Keamanan Data</h2>
    <p class="text-medium gray font-inherit mb-4">
        Kami mengambil langkah-langkah keamanan yang sesuai untuk melindungi data pribadi Anda. Namun, perlu diingat bahwa tidak ada metode transmisi data melalui internet yang sepenuhnya aman.
    </p>

    {{-- Kebijakan Perubahan Section --}}
    <h2 class="text-2xl font-semibold mb-2">Kebijakan Perubahan</h2>
    <p class="text-medium gray font-inherit mb-4">
        Kebijakan privasi ini dapat diperbarui dari waktu ke waktu. Setiap perubahan akan diberitahukan melalui aplikasi atau melalui email.
        Kami mendorong Anda untuk meninjau kebijakan privasi ini secara berkala untuk mengetahui bagaimana kami melindungi informasi Anda.
    </p>

    {{-- Kebijakan Perubahan Section --}}
    <h2 class="text-2xl font-semibold mb-2">Hubungi Kami</h2>
    <p class="text-medium gray font-inherit mb-4">
        Jika Anda memiliki pertanyaan lebih lanjut tentang kebijakan privasi kami, silahkan hubungi tim dukungan kami di <a href="mailto:support@infoev.id" class="text-blue-500 hover:underline">support@infoev.id</a>.

    </p>
    <p class="text-medium gray font-inherit mb-4">
        Semoga kebijakan privasi ini membantu Anda memahami bagaimana kami mengelola data pengguna di aplikasi InfoEV. Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami.
    </p>


</div>

{{-- Footer --}}
<x-slot:footer>
    <x-menu.footer :logo="$logo"
        :bikeBrands="$bikeBrands"
        :carBrands="$carBrands"
        :recentVehicles="$recentVehicles"
        :popularVehicles="$popularVehicles"
        :featuredArticles="$stickies" />
</x-slot>
{{-- End Footer --}}
</x-layouts.main>

