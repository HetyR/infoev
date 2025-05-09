<x-layouts.backend title="Add New Marketplace">
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white fw-bold">
                    Add Marketplace
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('backend.marketplace.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Marketplace</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                        </div>

                        <div class="d-flex justify-content-start gap-2 mt-4">
                            <button type="submit" class="btn btn-sm btn-outline-primary hover-shadow">
                                <i class="fas fa-save me-1"></i> Submit
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
