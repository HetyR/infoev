@props(['bikeBrands', 'carBrands'])

<section>
    <h2 class="pl-3 py-1 border-l-8 border-purple-900 uppercase font-bold text-slate-800">Daftar Merek</h2>

    <div class="md:pt-3 md:bg-neutral-200">
        <ul class="mt-2 text-slate-800 md:columns-3 md:gap-0 md:mt-4">
            @foreach ($bikeBrands as $brand)
                <li class="w-full border-b border-zinc-200 first:border-y md:block md:border-b-0 md:first:border-y-0 md:break-inside-avoid">
                    <a href="{{ route('brand.show', ['brand' => $brand->slug]) }}" class="flex justify-between items-center px-4 py-3 text-sm font-semibold md:pl-3 md:py-1 hover:underline hover:text-purple-900 md:uppercase md:tracking-tight md:font-normal md:hover:bg-purple-900 md:hover:text-white md:hover:no-underline">
                        <span>{{ $brand->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 md:hidden">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                        </svg>
                    </a>
                </li>
            @endforeach

            <li class="border-b border-zinc-200 first:border-y md:border-b-0 md:first:border-y-0">
                <a href="{{ route('type.show', ['type' => 'sepeda-motor']) }}" class="flex justify-between items-center px-4 py-3 text-sm font-semibold md:pl-3 md:py-1 hover:underline hover:text-purple-900 md:uppercase md:tracking-tight md:font-normal md:hover:bg-purple-900 md:hover:text-white md:hover:no-underline">
                    <span>[Semua Motor]</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 md:hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                    </svg>
                </a>
            </li>
        </ul>

        <hr class="my-3 border border-purple-900 md:border-gray-300">

        <ul class="text-slate-800 md:columns-3 md:gap-0 md:mt-4">
            @foreach ($carBrands as $brand)
                <li class="w-full border-b border-zinc-200 first:border-y md:block md:border-b-0 md:first:border-y-0 md:break-inside-avoid">
                    <a href="{{ route('brand.show', ['brand' => $brand->slug]) }}" class="flex justify-between items-center px-4 py-3 text-sm font-semibold md:pl-3 md:py-1 hover:underline hover:text-purple-900 md:uppercase md:tracking-tight md:font-normal md:hover:bg-purple-900 md:hover:text-white md:hover:no-underline">
                        <span>{{ $brand->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 md:hidden">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                        </svg>
                    </a>
                </li>
            @endforeach

            <li class="border-b border-zinc-200 first:border-y md:border-b-0 md:first:border-y-0">
                <a href="{{ route('type.show', ['type' => 'mobil']) }}" class="flex justify-between items-center px-4 py-3 text-sm font-semibold md:pl-3 md:py-1 hover:underline hover:text-purple-900 md:uppercase md:tracking-tight md:font-normal md:hover:bg-purple-900 md:hover:text-white md:hover:no-underline">
                    <span>[Semua Mobil]</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 md:hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                    </svg>
                </a>
            </li>
        </ul>

        <a href="{{ route('finder.index') }}" class="block w-full mt-3 px-4 py-2 bg-gray-800 text-center text-white hover:bg-purple-900 hover:text-white md:mt-5 md:py-3">Cari EV</a>
    </div>
</section>
