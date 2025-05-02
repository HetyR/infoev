@props(['popularVehicles'])

<section>
    <div class="pl-3 py-1 border-l-8 border-purple-900 uppercase font-bold text-slate-800">Populer 3 Bulan Terakhir</div>

    <div class="mt-2 md:mt-0 md:pl-2">
        @foreach ($popularVehicles as $vehicle)
            <div class="flex items-center text-sm text-slate-800 font-semibold border-b border-zinc-200 first:border-y md:border-b-0 md:first:border-y-0 md:py-1 md:odd:bg-gray-100 md:even:bg-white">
                <span class="pl-6">{{ $loop->iteration }}.</span>
                <a href="{{ route('vehicle.show', ['vehicle' => $vehicle->slug]) }}" class="flex flex-1 justify-between items-center py-3 pl-2 pr-4 font-semibold hover:underline hover:text-purple-900 md:inline-block md:py-0 md:font-bold">
                    <span>{{ $vehicle->brand->name }} {{ $vehicle->name }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 md:hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>
        @endforeach
    </div>
</section>