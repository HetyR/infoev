@props(['logo'])

<footer class="container flex flex-col mx-auto mt-10 p-4 items-center">
    <div>
        @if ($logo && $logo->thumbnail)
            <img src="{{ asset('storage/' . $logo->thumbnail->path) }}" class="max-w-[150px]" alt="logo">
        @else
            <img src="{{ asset('img/placeholder-md.png') }}" class="max-w-[150px]" alt="default logo">
        @endif
    </div>

    <h1 class="mt-4 text-slate-800 text-center font-semibold md:mb-2">Pusat informasi segala kendaraan listrik di Indonesia</h1>
    <a href="{{ route('privacy.index') }}" class="mt-2 text-slate-800 text-center font-semibold hover:underline">Kebijakan Privasi</a>
    <ul class="flex my-2 divide-x divide-zinc-300 font-bold text-sm md:hidden">
        <li><a href="{{ route('home') }}" class="px-2">Home</a></li>
        <li><a href="{{ route('brand.index') }}" class="px-2">Merek</a></li>
        <li><a href="{{ route('type.index') }}" class="px-2">Tipe</a></li>
        <li><a href="{{ route('blog.index') }}" class="px-2">Berita</a></li>
        <li><a href="{{ route('blog.index') }}" class="px-2">Login</a></li>
        <li><a hef="{{ route('blog.index') }}" class="px-2">Register</a></li>
        
    </ul>

    <div class="text-sm text-gray-500">Copyright &copy; Ginio.id <span id="year">2022</span></div>
</footer>
