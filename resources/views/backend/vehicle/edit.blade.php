<x-layouts.backend title="Edit Vehicle">
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <div class="card">
                <div class="card-header bg-primary text-white fw-bold">
                    Edit Vehicle
                </div>
                <div class="card-body">
            <form method="POST" action="{{ route('backend.vehicle.update', ['vehicle' => $vehicle->slug]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="type" class="form-label d-block">Type</label>
                    <select class="form-select" name="type" id="type">
                        <option value="" disabled selected hidden>Select type</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" @selected($type->id == $vehicle->type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="brand" class="form-label d-block">Brand</label>
                    <select class="form-select" name="brand" id="brand">
                        <option value="" disabled selected hidden>Select brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" @selected($brand->id == $vehicle->brand->id)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $vehicle->name }}">
                </div>
                @if ($vehicle->pictures->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label">Current Vehicle Pictures</label>

                        <div class="row">
                            @foreach ($vehicle->pictures as $img)
                                <div class="col-3">
                                    <img src="{{ asset('storage/' . $img->path) }}" alt="" class="img-fluid">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="mb-3">
                    <label for="pictures" class="form-label">Pictures</label>
                    <input type="file" class="form-control" id="pictures" name="pictures[]" accept="image/png, image/jpeg" multiple>
                </div>
                <div class="mb-3" data-specification>
                    <label class="form-label">Specification</label>
                    @forelse ($vehicle->specs as $spec)
                        <div @class(['row', 'mt-4' => $loop->index > 0]) @if ($loop->index > 0) data-parent @endif>
                            <div class="col-sm-4">
                                <select class="form-select" name="spec_ids[]" onchange="changeSpec(this)" data-s2-spec>
                                    <option value="" disabled selected hidden>Select specification</option>
                                    @foreach ($specs as $index => $cat)
                                        @foreach ($cat->specs as $specification)
                                            <option value="{{ $specification->id }}" data-cat="{{ $index }}" data-index="{{ $loop->index }}" data-type="{{ $specification->type }}" @selected($spec->id == $specification->id)>{{ $cat->name }} - {{ $specification->name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-sm-5">
                                <select class="form-select" onchange="changeCategory(this)">
                                    <option value="" disabled selected hidden>Select category</option>
                                    @foreach ($specs as $cat)
                                        <option value="{{ $cat->id }}" data-index="{{ $loop->index }}" @selected($spec->specCategory->id == $cat->id)>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <select class="form-select" name="spec_ids[]" onchange="changeSpec(this)">
                                    @foreach (App\Models\SpecCategory::with('specs')->find($spec->specCategory->id)->specs as $item)
                                        <option value="{{ $item->id }}" data-index="{{ $loop->index }}" @selected($spec->id == $item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-sm-6 row m-0 p-0" data-spec-container>
                                @switch($spec->type)
                                    @case('price')
                                        <div class="col">
                                            <input type="hidden" name="value_types[]" value="{{ $spec->type }}">
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" name="values[]" class="form-control" min="0" value="{{ (float)$spec->pivot->value }}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" name="value_descriptions[]" placeholder="Description" value="{{ $spec->pivot->value_desc }}">
                                        </div>
                                        @break
                                    @case('unit')
                                        <div class="col">
                                            <input type="hidden" name="value_types[]" value="{{ $spec->type }}">
                                            @if (is_null($spec->unit))
                                                <input type="number" name="values[]" class="form-control" min="0" value="{{ (float)$spec->pivot->value }}">
                                            @else
                                                <div class="input-group">
                                                    <input type="number" name="values[]" class="form-control" min="0" step=".01" value="{{ (float)$spec->pivot->value }}">
                                                    <span class="input-group-text">{{ $spec->unit }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" name="value_descriptions[]" placeholder="Description"
                                                value="{{ $spec->pivot->value_desc }}">
                                        </div>
                                        @break
                                    @case('list')
                                        <div class="col">
                                            <input type="hidden" name="value_types[]" value="{{ $spec->type }}">
                                            @php
                                                $uuid = 'list-' . Str::orderedUuid();
                                                $pivotLists = App\Models\SpecVehicle::with('lists')->find($spec->pivot->id)->lists;
                                            @endphp
                                            <input type="hidden" name="values[]" value="{{ $uuid }}">
                                            <select class="form-select" multiple name="{{ $uuid }}[]" data-s2>
                                                @foreach ($spec->lists as $list)
                                                    <option value="{{ $list->id }}" @selected($pivotLists->filter(fn ($value) => $value->id == $list->id)->isNotEmpty())>{{ $list->list }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" name="value_descriptions[]" placeholder="Description"
                                                value="{{ $spec->pivot->value_desc }}">
                                        </div>
                                        @break
                                    @case('description')
                                        <div class="col">
                                            <input type="hidden" name="value_types[]" value="{{ $spec->type }}">
                                            <input type="hidden" name="values[]">
                                            <input type="text" class="form-control" name="value_descriptions[]" placeholder="Description" value="{{ $spec->pivot->value_desc }}">
                                        </div>
                                        @break
                                    @case('availability')
                                        <div class="col d-flex align-items-center">
                                            <input type="hidden" name="value_types[]" value="{{ $spec->type }}">
                                            <div class="form-check form-switch">
                                                <label class="form-check-label">
                                                    <input type="hidden" name="values[]" value="{{ $spec->pivot->value_bool != null && $spec->pivot->value_bool ? 'true' : 'false' }}">
                                                    <input class="form-check-input" type="checkbox" role="switch" onchange="checkboxChange(this)" @checked($spec->pivot->value_bool)>
                                                    {{ $spec->pivot->value_bool != null && $spec->pivot->value_bool ? 'Tersedia' : 'Tidak tersedia' }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" name="value_descriptions[]" placeholder="Description"
                                                value="{{ $spec->pivot->value_desc }}">
                                        </div>
                                        {{-- <div class="col">
                                            <input type="hidden" name="value_types[]" value="{{ $spec->type }}">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="values[]" value="false" @checked(!$spec->pivot->value_bool)>
                                                    Tidak Tersedia
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="values[]" value="true" @checked($spec->pivot->value_bool)>
                                                    Tersedia
                                                </label>
                                            </div>
                                        </div> --}}
                                        @break
                                    @default
                                        @break
                                @endswitch
                            </div>
                            <div class="col-sm-2">
                                @if ($loop->index == 0)
                                    <button type="button" class="btn btn-success" onclick="addSpec()">Add</button>
                                @else
                                    <button type="button" class="btn btn-danger" onclick="removeSpec(this)">Remove</button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="row">
                            <div class="col-sm-10" data-parent>
                                <select class="form-select" name="spec_ids[]" onchange="changeSpec(this)" data-s2-spec>
                                    <option value="" disabled selected hidden>Select specification</option>
                                    @foreach ($specs as $index => $cat)
                                        @foreach ($cat->specs as $spec)
                                            <option value="{{ $spec->id }}" data-cat="{{ $index }}" data-index="{{ $loop->index }}" data-type="{{ $spec->type }}">{{ $cat->name }} - {{ $spec->name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-outline-success btn-sm mb-3" onclick="addSpec()">Add</button>
                            </div>
                            
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-start gap-2 mt-4">
                    <button type="submit" class="btn btn-sm btn-outline-primary hover-shadow">
                        <i class="fas fa-save me-1"></i> Update
                    </button>
                    <a href="{{ route('backend.vehicle.index') }}" class="btn btn-sm btn-outline-secondary hover-shadow">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>

    <template data-template>
        <div class="row mt-4" data-parent>
            <div class="col-sm-10">
                <select class="form-select" name="spec_ids[]" onchange="changeSpec(this)" data-s2-spec>
                    <option value="" disabled selected hidden>Select specification</option>
                    @foreach ($specs as $index => $cat)
                        @foreach ($cat->specs as $spec)
                            <option value="{{ $spec->id }}" data-cat="{{ $index }}" data-index="{{ $loop->index }}" data-type="{{ $spec->type }}">{{ $cat->name }} - {{ $spec->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-danger" onclick="removeSpec(this)">Remove</button>
            </div>
            <div class="mt-3">
                <div class="row">
                </div>
            </div>
        </div>
    </template>

    <x-slot:css>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    </x-slot>
    <x-slot:js>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            const specs = {!! $specs->toJson() !!};

            function changeCategory(e) {
                const index = e.selectedOptions[0].dataset.index;
                const spec = specs[index];
                const specElem = e.parentElement.nextElementSibling.firstElementChild;
                const inputContainer = e.parentElement.parentElement.lastElementChild.lastElementChild;
                let option = '<option value="" disabled selected hidden>Select specification</option>';

                for (const [idx, sp] of spec.specs.entries()) {
                    option = `${option}<option value="${sp.id}" data-cat="${index}" data-index="${idx}" data-type="${sp.type}">${sp.name}</option>`;
                }
                specElem.innerHTML = option;
                specElem.disabled = false;
                inputContainer.innerHTML = '';
            }

            function changeSpec(e) {
                const parentContainer = e.parentElement.parentElement;
                const selectContainer = parentContainer.firstElementChild;
                let specContainer = parentContainer.querySelector('[data-spec-container]');
                if (specContainer === null) {
                    selectContainer.insertAdjacentHTML('afterend', '<div class="col-sm-6 row m-0 p-0" data-spec-container></div>');
                    specContainer = parentContainer.querySelector('[data-spec-container]');
                }
                selectContainer.className = 'col-sm-4';

                const type = e.selectedOptions[0].dataset.type;
                const catIndex = e.selectedOptions[0].dataset.cat;
                const specIndex = e.selectedOptions[0].dataset.index;

                let content = '<div class="col-5"><input type="text" class="form-control" name="value_descriptions[]" placeholder="Description"></div>';
                switch (type) {
                    case 'price':
                        content = `<div class="col-5">
                            <input type="hidden" name="value_types[]" value="${type}">
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="values[]" class="form-control" min="0">
                            </div>
                        </div>${content}`;
                        break;
                    case 'unit':
                        const unit = specs[catIndex].specs[specIndex].unit;
                        if (unit == null) {
                            content = `<div class="col-5">
                                <input type="hidden" name="value_types[]" value="${type}">
                                <input type="number" name="values[]" class="form-control" min="0">
                            </div>${content}`;
                        } else {
                            content = `<div class="col-5">
                                <input type="hidden" name="value_types[]" value="${type}">
                                <div class="input-group">
                                    <input type="number" name="values[]" class="form-control" min="0" step=".01">
                                    <span class="input-group-text">${unit}</span>
                                </div>
                            </div>${content}`;
                        }
                        break;
                    case 'list':
                        const lists = specs[catIndex].specs[specIndex].lists;
                        const uuid = crypto.randomUUID();
                        content = `<div class="col-5">
                            <input type="hidden" name="value_types[]" value="${type}">
                            <input type="hidden" name="values[]" value="${'list-' + uuid}">
                            <select class="form-select" multiple name="${'list-' + uuid + '[]'}" data-s2>
                                ${lists.map(list => '<option value="' + list.id + '">' + list.list + '</option>').join('')}
                            </select>
                        </div>${content}`;
                        break;
                    case 'description':
                        content = `<div class="col-10"><input type="hidden" name="value_types[]" value="${type}"><input type="hidden" name="values[]"><input type="text" class="form-control" name="value_descriptions[]" placeholder="Description"></div>`;
                        break;
                    case 'availability':
                        content = `<div class="col d-flex align-items-center">
                            <input type="hidden" name="value_types[]" value="${type}">
                            <div class="form-check form-switch">
                                <label class="form-check-label">
                                    <input type="hidden" name="values[]" value="false">
                                    <input class="form-check-input" type="checkbox" role="switch" onchange="checkboxChange(this)">
                                    Tidak tersedia
                                </label>
                            </div>
                        </div>${content}`;
                        break;
                    default:
                        break;
                }

                specContainer.innerHTML = content;
                if (type == 'list') select2ListInit();
            }

            function addSpec() {
                const container = document.querySelector('[data-specification]');
                const template = document.querySelector('[data-template]');
                const clone = template.content.cloneNode(true);
                container.append(clone);
                select2SpecInit();
            }

            function removeSpec(e) {
                const parent = e.closest('[data-parent]');
                parent.remove();
            }

            function checkboxChange(e) {
                if (e.checked) {
                    e.parentElement.firstElementChild.value = 'true';
                    e.nextSibling.textContent = 'Tersedia';
                } else {
                    e.parentElement.firstElementChild.value = 'false';
                    e.nextSibling.textContent =  'Tidak tersedia';
                }
            }

            function select2ListInit() {
                $('[data-s2]').last().select2({
                    theme: 'bootstrap-5',
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                    placeholder: 'Select list',
                    closeOnSelect: false,
                });
            }

            function select2SpecInit() {
                $('[data-s2-spec]').last().select2({
                    theme: 'bootstrap-5',
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style'
                });
            }

            $('[data-s2]').each(function (index) {
                $(this).select2({
                    theme: 'bootstrap-5',
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                    placeholder: 'Select list',
                    closeOnSelect: false,
                });
            });

            $('[data-s2-spec]').each(function (index) {
                $(this).select2({
                    theme: 'bootstrap-5',
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style'
                })
            });
        </script>
    </x-slot>
</x-layouts.backend>