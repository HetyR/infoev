<x-layouts.backend title="Tambah Affiliate">
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <div class="card">
                <div class="card-header bg-primary text-white fw-bold">
                    Add New Affiliate
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('backend.affiliate.store', ['vehicle' => $vehicle->slug]) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="marketplace" class="form-label d-block">Marketplace</label>
                            <select class="form-select" name="marketplace" id="marketplace">
                                <option value="" disabled selected hidden>Select marketplace</option>
                                @foreach ($marketplaces as $marketplace)
                                    <option value="{{ $marketplace->id }}">{{ $marketplace->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="desc" class="form-label">Description</label>
                            <input type="text" class="form-control" id="desc" name="desc">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" class="form-control" id="price" name="price">
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Link</label>
                            <input type="text" class="form-control" id="link" name="link">
                        </div>
                        <div class="d-flex justify-content-start gap-2 mt-4">
                            <button type="submit" class="btn btn-sm btn-outline-primary hover-shadow">
                                <i class="fas fa-save me-1"></i> Submit
                            </button>
                            <a href="{{ route('backend.affiliate.show', ['vehicle' => $vehicle->slug]) }}" class="btn btn-sm btn-outline-secondary hover-shadow">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.backend>
