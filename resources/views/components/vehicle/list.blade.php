@props(['vehicle'])

<a href="{{ route('vehicle.show', ['vehicle' => $vehicle->slug]) }}" class="group flex items-stretch px-4 py-3 border-b border-zinc-200 first:border-y hover:z-30 md:block md:px-0 md:py-0 md:text-gray-400 md:border-b-0 md:first:border-y-0 md:hover:scale-105 md:transition-transform md:ease-out">
    <div class="flex justify-center items-center max-w-10 md:max-w-full">
        @if ($vehicle->pictures->isEmpty())
            <img src="{{ asset('img/placeholder-md.png') }}" class="w-full object-cover group-hover:brightness-105" alt="">
        @else
            @php
                $imagePath = 'storage/' . $vehicle->pictures->first()->path;
            @endphp
            @if (file_exists(public_path($imagePath)))
                <img src="{{ asset($imagePath) }}" class="w-full object-cover group-hover:brightness-105" alt="">
            @else
                <img src="{{ asset('img/placeholder-md.png') }}" class="w-full object-cover group-hover:brightness-105" alt="">
            @endif
        @endif
    </div>
    <div class="flex flex-1 flex-col justify-center items-start px-3 py-1 text-slate-700 group-hover:text-white group-hover:bg-purple-900 md:justify-start md:items-center md:px-2 md:bg-stone-50">
        <span class="font-semibold text-lg">{{ $vehicle->brand->name }} {{ $vehicle->name }}</span>
        <span class="font-light text-sm tracking-tight">{{ (float) $vehicle->pivot->value }}</span>
    </div>
</a>
