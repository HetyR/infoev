<x-layouts.backend>
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <form method="POST" action="{{ route('backend.marketplace.update', ['marketplace' => $marketplace->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Marketplace</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $marketplace->name }}">
                </div>
                @if ($logo)
                    <div class="mb-3" style="background-size: cover;">
                        <label class="form-label d-block">Current Logo</label>
                        <img src="{{ asset('storage/' . $logo->path) }}" alt="" class="img-fluid" style="max-width: 300px">
                    </div>
                @endif
                <div class="mb-3">
                    <label for="logo" class="form-label">Logo</label>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('backend.marketplace.index') }}" class="btn btn-outline-secondary">Back</a>
            </form>
        </div>
    </div>
</x-layouts.backend>