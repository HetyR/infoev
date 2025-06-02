<x-layouts.backend title="Add Specification Category">
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white fw-bold">
                    Add New Category
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('backend.spec.category.store') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Priority --}}
                        <div class="mb-3">
                            <label for="priority" class="form-label d-block">Priority</label>
                            <select
                                class="form-select @error('priority') is-invalid @enderror"
                                name="priority"
                                id="priority"
                            >
                                @for ($i = 0; $i < 9; $i++)
                                    <option value="{{ $i + 1 }}" @selected(old('priority', 9) == $i + 1)>
                                        {{ $i + 1 }}
                                    </option>
                                @endfor
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-start gap-2 mt-4">
                            <button type="submit" class="btn btn-sm btn-outline-primary hover-shadow">
                                <i class="fas fa-save me-1"></i> Submit
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

    <x-slot:css>
    </x-slot>
</x-layouts.backend>
