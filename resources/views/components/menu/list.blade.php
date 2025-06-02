@props(['item', 'link'])

<div class="md:inline-block md:w-full">
    <a href="{{ $link }}" class="flex justify-between items-center px-4 py-3 border-b border-zinc-200 first:border-y hover:bg-stone-100 hover:text-purple-900 md:block md:px-2 md:py-3 md:text-gray-400 md:border-b-0 md:first:border-y-0">
        <div class="flex items-start">
            <span class="font-semibold md:uppercase md:text-3xl md:tracking-wider">{{ $item->name }}</span>
            <span class="ml-1 text-xs md:font-semibold">{{ $item->vehicles_count }}</span>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 md:hidden">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
        </svg>
    </a>
</div>