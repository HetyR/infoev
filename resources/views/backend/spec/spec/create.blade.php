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
                            <select class="form-select" name="catId" id="catId" required>
                                <option value="" disabled selected hidden>Select specification category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Specification Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Specification Type</label>
                            <select class="form-select" name="type" id="type" onchange="handleTypeChange()" required>
                                <option value="" disabled selected hidden>Select type</option>
                                <option value="price">Price</option>
                                <option value="unit">Unit</option>
                                <option value="description">Description</option>
                                <option value="list">List</option>
                                <option value="availability">Availability</option>
                            </select>
                        </div>

                        <!-- Dynamic fields area -->
                        <div id="dynamic-fields"></div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="hidden" name="hidden">
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
                const placeholder = type === 'price' ? 'e.g. Rp, USD, ¥' : 'e.g. Liter, kWh, cc';
                dynamicFields.innerHTML = `
                    <div class="mb-3">
                        <label for="unit" class="form-label">Specification Unit</label>
                        <input type="text" class="form-control" name="unit" id="unit" required placeholder="${placeholder}">
                    </div>`;
            } else if (type === 'description') {
                dynamicFields.innerHTML = `
                    <div class="mb-3">
                        <label for="description" class="form-label">Specification Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
                    </div>`;
            } else if (type === 'list') {
                dynamicFields.innerHTML = `
                    <div id="spec-list-wrapper">
                        <div class="row mb-2" data-parent>
                            <div class="col-sm-10">
                                <input class="form-control" name="specLists[]" placeholder="Enter list item" required>
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
    </script>
</x-layouts.backend>
