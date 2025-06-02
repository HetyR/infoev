@props(['title', 'img'])

<div class="hidden relative min-h-40 overflow-hidden md:flex">
    <div class="absolute w-full h-full z-0">
        @if (isset($img))
            <img src="{{ asset('storage/' . $img->path) }}" class="w-full h-full object-cover" alt="{{ $title }}">
        @else
            <img src="{{ asset('storage/banner/6FGYNbh6sjy39q7mhaPOIec6iLnprUPKFqJTU10r.jpg') }}" class="w-full h-full object-cover" alt="{{ $title }}">
        @endif
    </div>

    <div class="w-full mt-auto px-10 py-6 backdrop-contrast-50 bg-black/40">
        <h2 class="text-white text-3xl font-semibold">{{ $title }}</h2>
    </div>
</div>
