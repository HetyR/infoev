<x-layouts.backend title="Edit Marketplace">
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white fw-bold">
                    Edit Marketplace
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('backend.marketplace.update', ['marketplace' => $marketplace->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Marketplace</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $marketplace->name }}" required>
                        </div>

                        @if ($logo)
                            <div class="mb-3">
                                <label class="form-label d-block">Current Logo</label>
                                <img src="{{ asset('storage/' . $logo->path) }}" alt="Current Logo" class="img-fluid rounded shadow-sm" style="max-width: 300px">
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                        </div>

                        <div class="d-flex justify-content-start gap-2 mt-4">
                            <button type="submit" class="btn btn-sm btn-outline-primary hover-shadow">
                                <i class="fas fa-save me-1"></i> Update
                            </button>
                            <a href="{{ route('backend.marketplace.index') }}" class="btn btn-sm btn-outline-secondary hover-shadow">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.backend>
