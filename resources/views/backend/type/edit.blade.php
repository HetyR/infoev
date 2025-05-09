<x-layouts.backend title="Edit Type">
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    Edit Type
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('backend.type.update', ['type' => $type->slug]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Type</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $type->name }}" required>
                        </div>

                        @if ($banner)
                            <div class="mb-3">
                                <label class="form-label d-block">Current Banner</label>
                                <img src="{{ asset('storage/' . $banner->path) }}" alt="Current Banner" class="img-fluid rounded shadow-sm" style="max-width: 300px;">
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="banner" class="form-label">Upload New Banner</label>
                            <input type="file" class="form-control" id="banner" name="banner" accept="image/*">
                        </div>

                        <div class="d-flex justify-content-start gap-2 mt-4">
                            <button type="submit" class="btn btn-sm btn-outline-primary hover-shadow">
                                <i class="fas fa-save me-1"></i> Update
                            </button>
                            <a href="{{ route('backend.type.index') }}" class="btn btn-sm btn-outline-secondary hover-shadow">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                        </div>
                        
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.backend>
