<x-layouts.backend title="Add Specification">
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <div class="card shadow rounded-4">
                <div class="card-header bg-primary text-white fw-bold">
                    Add New Specification
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('backend.spec.spec.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="catId" class="form-label">Specification Category</label>
                            <select class="form-select @error('catId') is-invalid @enderror" name="catId" id="catId" required>
                                <option value="" disabled selected hidden>Select specification category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('catId') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('catId')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Specification Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Specification Type</label>
                            <select class="form-select @error('type') is-invalid @enderror" name="type" id="type" onchange="handleTypeChange()" required>
                                <option value="" disabled selected hidden>Select type</option>
                                <option value="price" {{ old('type') == 'price' ? 'selected' : '' }}>Price</option>
                                <option value="unit" {{ old('type') == 'unit' ? 'selected' : '' }}>Unit</option>
                                <option value="description" {{ old('type') == 'description' ? 'selected' : '' }}>Description</option>
                                <option value="list" {{ old('type') == 'list' ? 'selected' : '' }}>List</option>
                                <option value="availability" {{ old('type') == 'availability' ? 'selected' : '' }}>Availability</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dynamic fields area -->
                        <div id="dynamic-fields">
                            {{-- Tetap kosong saat awal, JavaScript akan isi --}}
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="hidden" name="hidden" {{ old('hidden') ? 'checked' : '' }}>
                            <label for="hidden" class="form-check-label">Is Hidden?</label>
                        </div>

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

    <script>
    function handleTypeChange() {
        const type = document.getElementById('type').value;
        const dynamicFields = document.getElementById('dynamic-fields');
        dynamicFields.innerHTML = '';

        if (type === 'price' || type === 'unit') {
            dynamicFields.innerHTML = `
                <div class="mb-3">
                    <label for="unit" class="form-label">Specification Unit</label>
                    <input type="text" class="form-control @error('unit') is-invalid @enderror" name="unit" id="unit" placeholder="${type === 'price' ? 'e.g. Rp, USD, ¥' : 'e.g. Liter, kWh, cc'}" value="{{ old('unit') }}" required>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>`;
        } else if (type === 'description') {
            dynamicFields.innerHTML = `
                <div class="mb-3">
                    <label for="description" class="form-label">Specification Description</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>`;
        } else if (type === 'list') {
            dynamicFields.innerHTML = `
                <div id="spec-list-wrapper">
                    <div class="row mb-2" data-parent>
                        <div class="col-sm-10">
                            <input class="form-control @error('specLists.*') is-invalid @enderror" name="specLists[]" placeholder="Enter list item" required>
                            @error('specLists.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-outline-success" onclick="addList()">Add</button>
                        </div>
                    </div>
                </div>`;
        } else if (type === 'availability') {
            dynamicFields.innerHTML = `
                <div class="alert alert-info">Tipe ini dikelola di level kendaraan dan tidak memerlukan input di sini.</div>
            `;
        }
    }

    function addList() {
        const wrapper = document.getElementById('spec-list-wrapper');
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'mb-2');
        newRow.innerHTML = `
            <div class="col-sm-10">
                <input class="form-control" name="specLists[]" placeholder="Enter list item" required>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-outline-danger" onclick="this.closest('.row').remove()">Remove</button>
            </div>`;
        wrapper.appendChild(newRow);
    }

    // Run once on page load if there's an old type selected
    window.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('type').value) {
            handleTypeChange();
        }
    });
    </script>
</x-layouts.backend>
