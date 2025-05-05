<x-layouts.backend>
    <div class="row justify-content-center">
        <div class="col-lg-8 my-4">
            <form method="POST" action="{{ route('backend.spec.spec.update', ['spec' => $spec->id]) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="catId" class="form-label d-block">Specification Category</label>
                    <select class="form-select" name="catId" id="catId">
                        <option value="" disabled selected hidden>Select specification category</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @if ($spec->spec_category_id == $cat->id) selected @endif>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Specification Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $spec->name }}">
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label d-block">Specification Type</label>
                    <select class="form-select" name="type" id="type">
                        <option value="" disabled selected hidden>Select specification type</option>
                        <option value="price" @if ($spec->type == 'price') selected @endif>Price</option>
                        <option value="unit" @if ($spec->type == 'unit') selected @endif>Unit</option>
                        <option value="list" @if ($spec->type == 'list') selected @endif>List</option>
                        <option value="description" @if ($spec->type == 'description') selected @endif>Description</option>
                        <option value="availability" @if ($spec->type == 'availability') selected @endif>Availability</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="unit" class="form-label">Specification Unit</label>
                    <input type="text" class="form-control" id="unit" name="unit" value="{{ $spec->unit }}">
                </div>
                <div class="mb-3" data-list>
                    <label class="form-label">Specification List</label>
                    @if ($spec->lists->isNotEmpty())
                        @foreach ($spec->lists as $list)
                            <div class="row mt-2" data-parent>
                                <div class="col-sm-10">
                                    <input class="form-control" name="specLists[]" value="{{ $list->list }}">
                                </div>
                                <div class="col-sm-2">
                                    @if ($loop->index > 0)
                                        <button type="button" class="btn btn-danger" onclick="removeList(this)">Remove</button>
                                    @else
                                        <button type="button" class="btn btn-success" onclick="addList()">Add</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row mt-2" data-parent>
                            <div class="col-sm-10">
                                <input class="form-control" name="specLists[]">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-success" onclick="addList()">Add</button>
                            </div>
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('backend.spec.index') }}" class="btn btn-outline-secondary">Back</a>
            </form>
        </div>
    </div>

    <template data-list-template>
        <div class="row mt-2" data-parent>
            <div class="col-sm-10">
                <input class="form-control" name="specLists[]">
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-danger" onclick="removeList(this)">Remove</button>
            </div>
        </div>
    </template>

    <x-slot:js>
        <script>
            function addList() {
                const container = document.querySelector('[data-list]');
                const template = document.querySelector('[data-list-template]');
                const clone = template.content.cloneNode(true);
                container.append(clone);
            }

            function removeList(e) {
                const parent = e.closest('[data-parent]');
                parent.remove();
            }
        </script>
    </x-slot>
</x-layouts.backend>