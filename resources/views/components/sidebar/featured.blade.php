@props(['featuredArticles'])

<section class="hidden md:block">
    <div class="pl-3 py-1 border-l-8 border-purple-900 uppercase font-bold text-slate-800">Berita Pilihan</div>

    <div class="pl-2">
        <div class="p-3 bg-gray-100 space-y-3 text-sm">
            @foreach ($featuredArticles as $featured)
                <a href="{{ route('blog.show', ['blog' => $featured->slug]) }}" class="group flex space-x-3">
                    <div class="w-1/3">
                        @if (isset($featured->thumbnail) && !is_null($featured->thumbnail))
                            <img src="{{ asset('storage/' . $featured->thumbnail->path) }}" class="w-full group-hover:brightness-105" alt="{{ $featured->title }}">
                        @else
                            <img src="{{ asset('img/placeholder-md.png') }}" class="w-full group-hover:brightness-105" alt="{{ $featured->title }}">
                        @endif
                    </div>
                    <div class="flex items-center w-2/3">
                        <p class="font-bold text-slate-800 group-hover:text-purple-900 group-hover:underline">{{ $featured->title }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>