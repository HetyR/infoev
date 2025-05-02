@props(['recentVehicles'])

<section class="hidden md:block">
    <div class="pl-3 py-1 border-l-8 border-purple-900 uppercase font-bold text-slate-800">Model Terbaru</div>

    <div class="pl-2">
        <div class="p-3 bg-gray-100 space-y-3 text-sm">
            @foreach ($recentVehicles as $recent)
                <a href="{{ route('vehicle.show', ['vehicle' => $recent->slug]) }}" class="group flex space-x-3">
                    <div class="w-1/2">
                        @if ($recent->pictures->isEmpty())
                            <img src="{{ asset('img/placeholder-md.png') }}" class="w-full group-hover:brightness-105" alt="{{ $recent->name }}">
                        @else
                            <img src="{{ asset('storage/' . $recent->pictures->first()->path) }}" class="w-full group-hover:brightness-105" alt="{{ $recent->name }}">
                        @endif
                    </div>
                    <div class="flex items-center w-1/2">
                        <p class="font-bold text-slate-700 group-hover:text-purple-900 group-hover:underline">{{ $recent->brand->name }} {{ $recent->name }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>