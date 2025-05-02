<x-layouts.main>
    <x-slot:title>{{ $vehicle->brand->name }} {{ $vehicle->name }} - Spesifikasi Lengkap - InfoEV</x-slot>

    <x-slot:meta>

    </x-slot>

    {{-- Header --}}
    <x-slot:header>
        <x-menu.navbar :logo="$logo" :bikeBrands="$bikeBrands" :carBrands="$carBrands" />
        <x-menu.menu />
    </x-slot>
    {{-- End Header --}}

    {{-- Sidebar --}}
    <x-slot:sidebar>
        {{-- Brand Menu --}}
        <x-sidebar.brand-menu :bikeBrands="$bikeBrands" :carBrands="$carBrands" />

        {{-- Latest Models --}}
        <x-sidebar.latest :recentVehicles="$recentVehicles" />

        {{-- Top 10 --}}
        <x-sidebar.top :popularVehicles="$popularVehicles" />

        {{-- Featured --}}
        <x-sidebar.featured :featuredArticles="$stickies" />
    </x-slot>
    {{-- End Sidebar --}}

    {{-- Content Section --}}
    {{-- Title Header --}}
{{-- Title Header --}}
@if (isset($pictures) && !is_null($pictures) )
    <x-vehicle.title-header :img="$pictures" title="{{ $vehicle->brand->name }} {{ $vehicle->name }}" />
@else
    <x-vehicle.title-header title="{{ $vehicle->brand->name }} {{ $vehicle->name }}" />
@endif
{{-- End Title Header --}}

    {{-- Product Detail Container --}}
    <div class="flex-1 md:mt-0">

        {{-- Product Detail --}}
        <div class="mt-2 md:mt-0">
            {{-- Specification Detail --}}
            @if (!empty($highlightSpecs))
                <div class="grid grid-cols-2 px-4 py-6 md:flex md:justify-center md:divide-x md:divide-zinc-300">
                    <x-vehicle.spec-highlight :highlightSpecs="$highlightSpecs" />

                    {{-- Love Button --}}
                    <div class="flex items-center justify-center px-3">
                        @if (auth()->check())
                            <form action="{{ route('vehicle.toggleLove', ['id' => $vehicle->id]) }}" method="POST">
                                @csrf
                                @php
                                    $isInCart = \App\Models\LovedVehicle::where('vehicle_id', $vehicle->id)
                                        ->where('user_id', auth()->id())
                                        ->exists();
                                @endphp
                                @if ($isInCart)
                                    <button type="submit" class="flex items-center text-red-600 focus:outline-1">
                                        <i class="fas fa-heart w-16 h-16 mr-1" style="color: red;"></i>
                                        Unlike
                                    </button>
                                @else
                                    <button type="submit" class="flex items-center text-black focus:outline-1">
                                        <i class="far fa-heart w-16 h-16 mr-1"></i>
                                        Like
                                    </button>
                                @endif
                            </form>
                        @else
                            <button type="button" class="flex items-center text-black focus:outline-1"
                                onclick="alert('Please log in to like this vehicle.');">
                                <i class="far fa-heart w-16 h-16 mr-1"></i>
                                Like
                            </button>
                        @endif
                    </div>
                    {{-- End Love Button --}}






                </div>
            @endif
            {{-- End Specification Detail --}}


            {{-- Specification Detail --}}
            <div class="grid gap-2 pb-4">
                @foreach ($specCategories as $cat)
                    @if ($cat->specs->filter(fn($value) => $value->vehicles->isNotEmpty())->isNotEmpty())
                        <div class="grid grid-cols-5 auto-rows-max">
                            <div
                                class="px-2 py-1 col-span-5 row-span-full bg-slate-200 uppercase text-purple-900 font-bold md:col-span-1 md:px-0 md:pl-4 md:pr-3 md:bg-slate-100">
                                <p>{{ $cat->name }}</p>
                            </div>

                            <div class="px-2 col-span-5 bg-slate-100 divide-y divide-zinc-300 md:col-span-4 md:px-0">
                                @foreach ($cat->specs as $spec)
                                    @if ($spec->vehicles->isNotEmpty())
                                        <div class="grid grid-cols-5 md:grid-cols-4 md:pr-4">
                                            <div class="py-1 col-span-2 font-bold md:col-span-1">
                                                {{ $spec->name }}
                                            </div>

                                            <div
                                                class="py-1 col-span-3 divide-y divide-zinc-300 border-b border-zinc-300 last:border-b-0 [&>*:first-child:not(:only-child)]:pb-1 [&>*:not(:first-child):not(:last-child)]:py-1  [&>*:last-child:not(:only-child)]:pt-1">
                                                @php
                                                    $pivot = $spec->vehicles->find($vehicle->id)->pivot;
                                                @endphp
                                                @switch($spec->type)
                                                    @case('price')
                                                        {{ $spec->unit }}
                                                        {{ number_format($pivot->value, 0, ',', '.') . ',-' }}{{ $pivot->value_desc != null ? ' (' . $pivot->value_desc . ')' : '' }}
                                                    @break

                                                    @case('unit')
                                                        {{ Str::replace('.', ',', (float) $pivot->value) }}
                                                        {{ $spec->unit }}
                                                        {{ $pivot->value_desc != null ? ' (' . $pivot->value_desc . ')' : '' }}
                                                    @break

                                                    @case('list')
                                                        @php
                                                            $pivotLists = App\Models\SpecVehicle::with('lists')->find(
                                                                $pivot->id,
                                                            )->lists;
                                                        @endphp
                                                        {{ $pivotLists->implode('list', ', ') }}{{ $pivot->value_desc != null ? ' (' . $pivot->value_desc . ')' : '' }}
                                                    @break

                                                    @case('description')
                                                        {{ $pivot->value_desc }}
                                                    @break

                                                    @case('availability')
                                                        @if ($pivot->value_bool == 1)
                                                            Tersedia
                                                        @else
                                                            Tidak tersedia
                                                        @endif
                                                    @break

                                                    @default
                                                    @break
                                                @endswitch
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            {{-- End Specification Detail --}}
        </div>
        {{-- End Product Detail --}}

        {{-- Disclaimer --}}
        <div class="mt-2 mb-3 px-6 text-sm md:mt-0">
            <strong>Disclaimer.</strong> InfoEV tidak menjamin informasi yang ada di halaman ini akurat 100%.
        </div>
        {{-- End Disclaimer --}}

        {{-- Affiliate Link --}}
        @if ($vehicle->affiliateLinks->isNotEmpty())
            <div class="mt-8 mb-3">
                <h2 class="pl-3 py-1 border-l-8 border-purple-900 uppercase font-bold text-slate-800">Cek Harga Terbaru
                    di Sini</h2>

                <div class="px-2 bg-slate-100 md:mt-3 md:py-2">
                    @foreach ($vehicle->affiliateLinks as $affiliate)
                        <div class="py-3 border-zinc-200 [&:not(:last-child)]:border-b md:grid md:grid-cols-2 md:py-0">
                            <div
                                class="px-4 py-2 flex justify-center border-zinc-200 md:justify-start md:items-center md:border-r">
                                <a href="{{ $affiliate->link }}" class="block" target="_blank"
                                    rel="noopener nofollow">
                                    <img src="{{ 'storage/' . $affiliate->marketplace->logo->path }}" class="max-w-10"
                                        alt="">
                                </a>
                            </div>
                            <div class="px-4 py-2 flex items-center">
                                <a href="{{ $affiliate->link }}"
                                    class="block px-4 py-2 w-full bg-slate-800 text-white text-center hover:bg-purple-900 md:inline-block md:w-auto"
                                    target="_blank" rel="noopener nofollow">
                                    Lihat Produk
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        {{-- End Affiliate Link --}}

        {{-- Comment Section --}}
        <div class="mt-14 mb-3" id="comment">
            <h2 class="pl-3 py-1 border-l-8 border-purple-900 uppercase font-bold text-slate-800">Komentar</h2>

            <div class="px-4 mt-5 md:px-6">
                <form action="{{ route('comment.post') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="vehicle">
                    <input type="hidden" name="id" value="{{ $vehicle->id }}">
                    <div class="px-4 py-2 mb-4 border border-gray-300">
                        <input type="text"
                            class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none"
                            name="name" placeholder="Nama (Opsional)" maxlength="25">
                    </div>

                    <div class="px-4 py-2 mb-4 border border-gray-300">
                        <textarea class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none" name="comment"
                            rows="6" placeholder="Tulis komentar...." maxlength="255" required></textarea>
                    </div>

                    <button type="submit"
                        class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-orange-500 rounded focus:ring-4 focus:ring-orange-100 hover:bg-primary-800">
                        Kirim
                    </button>
                </form>
            </div>

            <div class="mt-10 px-6 text-base">
                @foreach ($comments as $comment)
                    <x-comment-card :comment="$comment" />
                @endforeach
            </div>
        </div>
        {{-- End Comment Section --}}
    </div>
    {{-- End Product Detail Container --}}
    {{-- End Content Section --}}

    {{-- Footer --}}
    <x-slot:footer>
        <template data-reply-template>
            <div class="mt-5" data-reply>
                <div class="mb-4 flex justify-end">
                    <button onclick="closeForm(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('comment.post') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="vehicle">
                    <input type="hidden" name="id" value="{{ $vehicle->id }}">
                    <input type="hidden" name="parent" data-parent-id>
                    <div class="px-4 py-2 mb-4 border border-gray-300">
                        <input type="text"
                            class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none"
                            name="name" placeholder="Nama (Opsional)" maxlength="25">
                    </div>

                    <div class="px-4 py-2 mb-4 border border-gray-300">
                        <textarea class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none" name="comment"
                            rows="6" placeholder="Tulis komentar...." maxlength="255" required></textarea>
                    </div>

                    <button type="submit"
                        class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-orange-500 rounded focus:ring-4 focus:ring-orange-100 hover:bg-primary-800">
                        Kirim
                    </button>
                </form>
            </div>
        </template>
        <x-menu.footer :logo="$logo" />
    </x-slot>
    {{-- End Footer --}}

    {{-- Slider --}}
    <x-slot:css>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.6.0/css/glide.core.min.css"
            integrity="sha512-tYKqO78H3mRRCHa75fms1gBvGlANz0JxjN6fVrMBvWL+vOOy200npwJ69OBl9XEsTu3yVUvZNrdWFIIrIf8FLg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    </x-slot>
    <x-slot:js>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.6.0/glide.min.js"
            integrity="sha512-2sI5N95oT62ughlApCe/8zL9bQAXKsPPtZZI2KE3dznuZ8HpE2gTMHYzyVN7OoSPJCM1k9ZkhcCo3FvOirIr2A=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            new Glide('.glide').mount()
        </script>

        <script>
            let displayForm = false;

            function reply(btn) {
                if (displayForm) {
                    const form = document.querySelector('[data-reply]');
                    form.remove();
                    displayForm = false;
                }

                const parentComment = btn.closest('[data-comment-container]').firstElementChild;
                const replyTemplate = document.querySelector('[data-reply-template]');
                let replyForm = replyTemplate.content.cloneNode(true);
                replyForm.querySelector('[data-parent-id]').value = parentComment.dataset.commentId;

                parentComment.after(replyForm);
                parentComment.scrollIntoView({
                    behavior: 'smooth'
                });
                displayForm = true;
            }

            function closeForm(btn) {
                const container = btn.closest('[data-reply]');
                container.remove();
                displayForm = false;
            }
        </script>

        {{-- <script>
            document.addEventListener('DOMContentLoaded', function() {
                const loveForm = document.querySelector(
                    'form[action="{{ route('vehicle.love', ['id' => $vehicle->id]) }}"]');
                loveForm.addEventListener('submit', function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    const formData = new FormData(loveForm);

                    fetch(loveForm.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Eror');
                            } else {
                                alert('Terjadi kesalahan, silakan coba lagi.');
                            }
                        })
                        .catch(error => {
                            console.error('Data masuk ke tabel kendaraan', error);
                            alert('Data masuk ke tabel kendaraan.');
                        });
                });
            });
        </script> --}}
    </x-slot>
    {{-- End Slider --}}
</x-layouts.main>
