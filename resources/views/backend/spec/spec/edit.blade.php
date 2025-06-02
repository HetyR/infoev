<x-layouts.backend>
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <div class="card shadow rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0">Edit Specification</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('backend.spec.spec.update', ['spec' => $spec->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="catId" class="form-label">Specification Category</label>
                            <select class="form-select" name="catId" id="catId" required>
                                <option value="" disabled hidden>Select specification category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected($spec->spec_category_id == $cat->id)>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Specification Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $spec->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Specification Type</label>
<select id="type" name="type" class="form-select" onchange="handleTypeChange()"
    data-old-unit="{{ $spec->unit ?? '' }}"
    data-old-description="{{ $spec->description ?? '' }}"
    data-old-lists='@json($spec->lists->pluck("list") ?? [])'>
    <option value="">-- Pilih Tipe --</option>
    <option value="unit" {{ $spec->type === 'unit' ? 'selected' : '' }}>Unit</option>
    <option value="price" {{ $spec->type === 'price' ? 'selected' : '' }}>Price</option>
    <option value="description" {{ $spec->type === 'description' ? 'selected' : '' }}>Description</option>
    <option value="list" {{ $spec->type === 'list' ? 'selected' : '' }}>List</option>
    <option value="availability" {{ $spec->type === 'availability' ? 'selected' : '' }}>Availability</option>
</select>




                        </div>

                        <div id="dynamic-fields"></div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="hidden" name="hidden" @checked($spec->hidden)>
                            <label for="hidden" class="form-check-label">Is Hidden?</label>
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

    {{-- Template --}}
    <template id="list-template">
        <div class="row mb-2" data-parent>
            <div class="col-sm-10">
                <input class="form-control" name="specLists[]" placeholder="Enter list item" required>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-danger" onclick="this.closest('.row').remove()">Remove</button>
            </div>
        </div>
    </template>

    {{-- Script --}}
<script>
    function handleTypeChange() {
        const typeElement = document.getElementById('type');
        const type = typeElement.value;
        const dynamicFields = document.getElementById('dynamic-fields');
        dynamicFields.innerHTML = '';

        const oldUnit = typeElement.dataset.oldUnit || '';
        const oldDescription = typeElement.dataset.oldDescription || '';
        const oldLists = JSON.parse(typeElement.dataset.oldLists || '[]');

        if (type === 'price' || type === 'unit') {
            dynamicFields.innerHTML = `
                <div class="mb-3">
                    <label for="unit" class="form-label">Specification Unit</label>
                    <input type="text" class="form-control" name="unit" id="unit" required
                        placeholder="${type === 'price' ? 'e.g. Rp, USD, ¥' : 'e.g. Liter, kWh, cc'}"
                        value="${oldUnit}">
                </div>`;
        } else if (type === 'description') {
            dynamicFields.innerHTML = `
                <div class="mb-3">
                    <label for="description" class="form-label">Specification Description</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description', $spec->description ?? '') }}</textarea>
                </div>`;
        } else if (type === 'list') {
            let listFields = '';
            if (Array.isArray(oldLists) && oldLists.length > 0) {
                oldLists.forEach((val, index) => {
                    listFields += `
                        <div class="row mb-2" data-parent>
                            <div class="col-sm-10">
                                <input class="form-control" name="specLists[]" placeholder="Enter list item" required value="${val}">
                            </div>
                            <div class="col-sm-2">
                                ${index === 0
                                    ? '<button type="button" class="btn btn-outline-success" onclick="addList()">Add</button>'
                                    : '<button type="button" class="btn btn-outline-danger" onclick="this.closest(\'.row\').remove()">Remove</button>'}
                            </div>
                        </div>`;
                });
            } else {
                listFields = `
                    <div class="row mb-2" data-parent>
                        <div class="col-sm-10">
                            <input class="form-control" name="specLists[]" placeholder="Enter list item" required>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-outline-success" onclick="addList()">Add</button>
                        </div>
                    </div>`;
            }

            dynamicFields.innerHTML = `<div id="spec-list-wrapper">${listFields}</div>`;
        } else if (type === 'availability') {
            dynamicFields.innerHTML = `
                <div class="alert alert-info">Tipe ini dikelola di level kendaraan dan tidak memerlukan input di sini.</div>`;
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

    document.addEventListener('DOMContentLoaded', () => {
        handleTypeChange();
    });
</script>


</x-layouts.backend>
