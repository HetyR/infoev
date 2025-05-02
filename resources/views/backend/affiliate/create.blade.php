<x-layouts.backend>
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
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

                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('backend.affiliate.show', ['vehicle' => $vehicle->slug]) }}" class="btn btn-outline-secondary">Back</a>
            </form>
        </div>
    </div>
</x-layouts.backend>