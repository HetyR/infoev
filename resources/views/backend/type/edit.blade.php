<x-layouts.backend>
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <form method="POST" action="{{ route('backend.type.update', ['type' => $type->slug]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Type</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $type->name }}">
                </div>
                @if ($banner)
                    <div class="mb-3" style="background-size: cover;">
                        <label class="form-label d-block">Current Banner</label>
                        <img src="{{ asset('storage/' . $banner->path) }}" alt="" class="img-fluid" style="max-width: 300px">
                    </div>
                @endif
                <div class="mb-3">
                    <label for="banner" class="form-label">Banner</label>
                    <input type="file" class="form-control" id="banner" name="banner" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('backend.type.index') }}" class="btn btn-outline-secondary">Back</a>
            </form>
        </div>
    </div>
</x-layouts.backend>