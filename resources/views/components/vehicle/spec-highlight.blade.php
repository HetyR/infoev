@props(['highlightSpecs'])

@foreach ($highlightSpecs as $highlight)
<div class="px-4 py-4 text-slate-800 border-zinc-300 even:border-l md:px-8 md:py-0">
@switch($highlight['type'])
            @case('capacity')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 10.5h.375c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125H21M3.75 18h15A2.25 2.25 0 0021 15.75v-6a2.25 2.25 0 00-2.25-2.25h-15A2.25 2.25 0 001.5 9.75v6A2.25 2.25 0 003.75 18z" />
                </svg>
                <p class="mt-2 text-purple-900">
                    <span class="text-3xl">{{ Str::replace('.', ',', $highlight['value']) }}</span>
                    <span class="ml-1">{{ $highlight['unit'] }}</span>
                </p>
                <p class="text-sm text-gray-600 font-light">Kapasitas Baterai</p>
                @break
            @case('charge')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
                <p class="mt-2 text-purple-900">
                    <span class="text-3xl">{{ Str::replace('.', ',', $highlight['value']) }}</span>
                    <span class="ml-1">{{ $highlight['unit'] }}</span>
                </p>
                <p class="text-sm text-gray-600 font-light">{{ isset($highlight['desc']) ? $highlight['desc'] : 'Waktu Charge' }}</p>
                @break
            @case('maxSpeed')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-2 text-purple-900">
                    <span class="text-3xl">{{ Str::replace('.', ',', $highlight['value']) }}</span>
                    <span class="ml-1">{{ $highlight['unit'] }}</span>
                </p>
                <p class="text-sm text-gray-600 font-light">Kecepatan Maksimal</p>
                @break
            @case('range')
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                </svg>
                <p class="mt-2 text-purple-900">
                    <span class="text-3xl">{{ Str::replace('.', ',', $highlight['value']) }}</span>
                    <span class="ml-1">{{ $highlight['unit'] }}</span>
                </p>
                <p class="text-sm text-gray-600 font-light">Jarak Tempuh</p>
                @break
            @default
                @break
        @endswitch

    </div>

@endforeach

{{-- <!-- Tombol Love -->
<div class="flex items-center mt-4">
    <button class="flex items-center text-purple-600 hover:text-purple-900 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 mr-1">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 4.5c-1.127 0-2.157.528-2.682 1.347-.524-.82-1.554-1.347-2.682-1.347-1.76 0-3.25 1.49-3.25 3.25 0 .692.222 1.32.587 1.853.382.548.92 1.104 1.518 1.63 1.185 1.007 2.42 2.054 3.105 2.72.685-.666 1.92-1.713 3.105-2.72.598-.506 1.136-1.062 1.518-1.63.365-.533.587-1.161.587-1.853 0-1.76-1.49-3.25-3.25-3.25z" />
        </svg>
        Suka
    </button>
</div> --}}
