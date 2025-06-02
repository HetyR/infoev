<x-layouts.backend title="Edit Brand">
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white fw-bold">
                    Edit Brand
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('backend.brand.update', ['brand' => $brand->slug]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $brand->name }}">
                        </div>

                        @if ($banner)
                            <div class="mb-3" style="background-size: cover;">
                                <label class="form-label d-block">Current Banner</label>
                                <img src="{{ asset('storage/' . $banner->path) }}" alt="Current Banner" class="img-fluid" style="max-width: 300px;">
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="banner" class="form-label">Banner</label>
                            <input type="file" class="form-control" id="banner" name="banner" accept="image/*">
                        </div>

                        <div class="d-flex justify-content-start gap-2 mt-4">
                            <button type="submit" class="btn btn-sm btn-outline-primary hover-shadow">
                                <i class="fas fa-save me-1"></i> Update
                            </button>
                            <a href="{{ route('backend.brand.index') }}" class="btn btn-sm btn-outline-secondary hover-shadow">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.backend>
