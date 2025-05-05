<x-layouts.backend>
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <form method="POST" action="{{ route('backend.spec.category.update', ['spec' => $cat->id]) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $cat->name }}">
                </div>
                <div class="mb-3">
                    <label for="priority" class="form-label d-block">Priority</label>
                    <select class="form-select" name="priority" id="priority">
                        @for ($i = 0; $i < 9; $i++)
                            <option value="{{ $i + 1 }}" @if ($i + 1 == $cat->priority) selected @endif>{{ $i + 1 }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('backend.spec.index') }}" class="btn btn-outline-secondary">Back</a>
            </form>
        </div>
    </div>
</x-layouts.backend>