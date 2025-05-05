<x-layouts.backend>
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <form method="POST" action="{{ route('backend.spec.spec.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="catId" class="form-label d-block">Specification Category</label>
                    <select class="form-select" name="catId" id="catId">
                        <option value="" disabled selected hidden>Select specification category</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Specification Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="mb-3">
                    <input type="checkbox" class="form-check-input" id="hidden" name="hidden">
                    <label for="hidden" class="form-check-label">Is Hidden?</label>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('backend.spec.index') }}" class="btn btn-outline-secondary">Back</a>
            </form>
        </div>
    </div>
</x-layouts.backend>