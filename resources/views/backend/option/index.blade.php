<x-layouts.backend>
    <form action="{{ route('backend.option.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row mb-4">
            <div class="col-12 mb-2">
                <h2>Banner</h2>
            </div>
            @foreach ($banners as $banner)
                <div class="row mb-2">
                    <div class="col-12">
                        <h4>{{ Str::title($banner->name) }}</h4>
                    </div>
                    <div class="col-6">
                        <h6>Current Banner</h6>
                        @if (!is_null($banner->thumbnail))
                            <img src="{{ asset('storage/' . $banner->thumbnail->path) }}" class="w-100 img-fluid" alt="">
                        @endif
                    </div>
                    <div class="col-6">
                        <input type="file" class="form-control" name="banner[]" data-type="banner" data-id="{{ $banner->id }}" data-multiple accept="image/*">
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <h2>Logo</h2>
            </div>
            @if (!is_null($logo))
                <div class="col-6">
                    <h6>Current Logo</h6>
                    @if (!is_null($logo->thumbnail))
                        <img src="{{ asset('storage/' . $logo->thumbnail->path) }}" class="w-100 img-fluid" alt="">
                    @endif
                </div>
                <div class="col-6">
                    <input type="file" class="form-control" name="logo" @if (!is_null($logo)) data-type="logo" data-id="{{ $logo->id }}" @endif accept="image/*">
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </form>

    <x-slot:js>
        <script>
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => input.addEventListener('change', inputChange));

            function inputChange(e) {
                const target = e.target;

                if (target.nextElementSibling == null) {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('name', `${target.dataset.type}_id${target.dataset.multiple != undefined ? '[]' : ''}`);
                    input.value = target.dataset.id;
                    target.after(input);
                }
            }
        </script>
    </x-slot>
</x-layouts.backend>