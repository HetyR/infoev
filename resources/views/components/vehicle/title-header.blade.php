@props(['title', 'img'])

<div>
    <div class="md:hidden">
        <h2 class="mb-3 pl-2 text-lg font-semibold text-slate-800">{{ $title }}</h2>
    </div>

    <div class="relative overflow-hidden md:flex md:min-h-40">
        <div class="z-0 md:absolute md:w-full md:h-full">
            @if (isset($img) && $img->count() !== 0)
                @if ($img->count() > 1)
                    <div class="glide md:h-full">
                        <div class="text-white" data-glide-el="controls">
                            <button class="absolute left-2 top-1/2 -translate-y-1/2 z-50" data-glide-dir="<">
                                <svg viewBox="0 0 20 20" fill="currentColor" class="chevron-left w-6 h-6 md:w-10 md:h-10"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </button>
                            <button class="absolute right-2 top-1/2 -translate-y-1/2 z-50" data-glide-dir=">">
                                <svg viewBox="0 0 20 20" fill="currentColor" class="chevron-right w-6 h-6 md:w-10 md:h-10"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            </button>
                        </div>
                        <div class="glide__track h-full" data-glide-el="track">
                            <ul class="glide__slides h-full">
                                @foreach ($img as $pic)
                                    @if (Storage::exists('public/' . $pic->path))
                                        <li class="glide__slide h-full">
                                            <img src="{{ asset('storage/' . $pic->path) }}" class="w-full h-full object-cover" alt="{{ $title }}">
                                        </li>
                                    @else
                                        <li class="glide__slide h-full">
                                            <img src="{{ asset('img/placeholder-lg.png') }}" class="w-full h-full object-cover" alt="{{ $title }}">
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @else
                    @if (Storage::exists('public/' . $img[0]->path))
                        <img src="{{ asset('storage/' . $img[0]->path) }}" class="w-full h-full object-cover" alt="{{ $title }}">
                    @else
                        <img src="{{ asset('img/placeholder-lg.png') }}" class="w-full h-full object-cover" alt="{{ $title }}">
                    @endif
                @endif
            @else
                <img src="{{ asset('img/placeholder-lg.png') }}" class="w-full h-full object-cover" alt="{{ $title }}">
            @endif
        </div>

        <div class="hidden w-full mt-auto px-10 py-6 backdrop-contrast-50 bg-black/40 md:block">
            <h2 class="text-white text-3xl font-semibold">{{ $title }}</h2>
        </div>
    </div>
</div>
