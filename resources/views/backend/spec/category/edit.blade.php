<x-layouts.backend title="Edit Specification Category">
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white fw-bold">
                    Edit Category
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('backend.spec.category.update', ['spec' => $cat->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $cat->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="priority" class="form-label d-block">Priority</label>
                            <select class="form-select" name="priority" id="priority">
                                @for ($i = 0; $i < 9; $i++)
                                    <option value="{{ $i + 1 }}" @if ($i + 1 == $cat->priority) selected @endif>{{ $i + 1 }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="d-flex justify-content-start gap-2 mt-4">
                            <button type="submit" class="btn btn-sm btn-outline-primary hover-shadow">
                                <i class="fas fa-save me-1"></i> Update
                            </button>
                            <a href="{{ route('backend.spec.index') }}" class="btn btn-sm btn-outline-secondary hover-shadow">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.backend>
