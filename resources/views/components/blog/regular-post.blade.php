@props(['post', 'img'])

<article class="px-2 md:px-0">
    <a href="{{ route('blog.show', ['blog' => $post->slug]) }}" class="group flex bg-white border border-gray-300 text-slate-800">
        <div class="w-1/2 md:w-1/3">
            @if (isset($img))
                <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover group-hover:brightness-105" alt="{{ $post->title }}">
            @else
                <img src="{{ asset('img/placeholder-md.png') }}" class="w-full h-full object-cover group-hover:brightness-105" alt="{{ $post->title }}">
            @endif
        </div>
        <div class="w-1/2 flex flex-col px-2 py-1 md:w-2/3 md:px-4 md:py-2">
            {{-- Title --}}
            <div>
                <h2 class="text-sm font-semibold group-hover:text-purple-900 group-hover:underline md:text-xl md:font-normal">{{ $post->title }}</h2>
                <p class="hidden mt-4 pb-2 md:block">{{ $post->summary }}</p>
            </div>
            {{-- End Title --}}

            <div class="mt-2 text-gray-400 md:flex md:flex-1 md:justify-end md:items-end md:mt-0 md:space-x-8">
                {{-- Date Icon --}}
                <div class="text-xs md:flex md:space-x-2 md:items-end md:text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="hidden w-4 h-4 md:inline-block">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    <span class="uppercase">{{ $post->created_at->format('d M Y') }}</span>
                </div>
                {{-- End Date Icon --}}

                {{-- Comment Icon --}}
                {{-- <div class="hidden space-x-2 text-sm items-end md:flex">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                    </svg>

                    <span class="uppercase">0</span>
                </div> --}}
                {{-- End Comment Icon --}}
            </div>
        </div>
    </a>
</article>