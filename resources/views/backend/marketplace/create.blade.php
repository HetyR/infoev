<x-layouts.backend>
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <form method="POST" action="{{ route('backend.marketplace.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Marketplace</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="mb-3">
                    <label for="logo" class="form-label">Logo</label>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('backend.marketplace.index') }}" class="btn btn-outline-secondary">Back</a>
            </form>
        </div>
    </div>
</x-layouts.backend>