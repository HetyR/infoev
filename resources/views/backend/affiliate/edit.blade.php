<x-layouts.backend>
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <form method="POST" action="{{ route('backend.affiliate.update', ['affiliate' => $affiliate->id]) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="marketplace" class="form-label d-block">Marketplace</label>
                    <select class="form-select" name="marketplace" id="marketplace">
                        <option value="" disabled selected hidden>Select marketplace</option>
                        @foreach ($marketplaces as $marketplace)
                            <option value="{{ $marketplace->id }}" @if ($marketplace->id == $affiliate->marketplace->id) selected @endif>{{ $marketplace->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="desc" class="form-label">Description</label>
                    <input type="text" class="form-control" id="desc" name="desc" value="{{ $affiliate->desc }}">
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="text" class="form-control" id="price" name="price" value="{{ $affiliate->price }}">
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Link</label>
                    <input type="text" class="form-control" id="link" name="link" value="{{ $affiliate->link }}">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('backend.affiliate.show', ['vehicle' => $vehicle->slug]) }}" class="btn btn-outline-secondary">Back</a>
            </form>
        </div>
    </div>
</x-layouts.backend>