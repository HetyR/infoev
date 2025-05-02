<x-layouts.backend>
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <form method="POST" action="{{ route('backend.banner.update', ['banner' => $banner->getTable()]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $banner->id }}">
                @if ($banner->thumbnail)
                    <div class="mb-3" style="background-size: cover;">
                        <label class="form-label d-block">Current Banner</label>
                        <img src="{{ asset('storage/' . $banner->thumbnail->path) }}" alt="" class="img-fluid" style="max-width: 300px">
                    </div>
                @endif
                <div class="mb-3">
                    <label for="banner" class="form-label">Banner</label>
                    <input type="file" class="form-control" id="banner" name="banner" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('backend.banner.index') }}" class="btn btn-outline-secondary">Back</a>
            </form>
        </div>
    </div>
</x-layouts.backend>