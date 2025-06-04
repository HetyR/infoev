<x-layouts.backend title="Specification Management">
    <div class="row">
        <div class="mb-3 text-end">
            <a href="{{ route('backend.spec.category.create') }}"
                class="btn btn-outline-success me-2 d-inline-block hover-shadow">
                <i class="fas fa-plus me-1"></i> Add Specification Category
            </a>
            <a href="{{ route('backend.spec.spec.create') }}" class="btn btn-outline-success d-inline-block hover-shadow">
                <i class="fas fa-plus me-1"></i> Add Specification
            </a>
        </div>
        <div class="col-md-12 my-4">
            <div class="card shadow-sm rounded">
                <div class="card-body">
                    <table id="datatable" class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Specification</th>
                                <th class="text-center">Priority</th>
                                <th class="text-center">Menu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($specs as $cat)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $cat->name }}</td>
                                    <td class="text-start">
                                        <table class="w-100 table table-borderless mb-0">
                                            @foreach ($cat->specs as $spec)
                                                <tr>
                                                    <td class="w-75">
                                                        {{ $spec->name }}@if ($spec->hidden)
                                                            <span class="text-muted">(hidden)</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="d-flex justify-content-end gap-2">
                                                            <a href="{{ route('backend.spec.spec.edit', ['spec' => $spec->id]) }}"
                                                                class="btn btn-sm btn-outline-warning d-inline-flex align-items-center hover-shadow">
                                                                <i class="fas fa-edit me-1"></i> Edit
                                                            </a>
                                                            <form
                                                                action="{{ route('backend.spec.spec.destroy', ['spec' => $spec->id]) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Delete this specification?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger d-inline-flex align-items-center hover-shadow">
                                                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                    <td>{{ $cat->priority }}</td>
                                    <td>
                                        <a href="{{ route('backend.spec.category.edit', ['spec' => $cat->id]) }}"
                                            class="btn btn-sm btn-outline-warning me-1 hover-shadow">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form
                                            action="{{ route('backend.spec.category.destroy', ['spec' => $cat->id]) }}"
                                            method="POST" class="d-inline-block"
                                            onsubmit="return confirm('Delete this specification category?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger hover-shadow">
                                                <i class="fas fa-trash-alt me-1"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-slot:css>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css"
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            /* Pertegas border tabel */
            .table-bordered th,
            .table-bordered td {
                border: 1.5px solid #dee2e6 !important;
            }

            /* DataTables pagination active color fix */
            .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
                color: #fff !important;
                background-color: #0d6efd !important;
                border-color: #0d6efd !important;
            }
        </style>
    </x-slot:css>

    <x-slot:js>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <script>
            $(document).ready(function() {
                $('#datatable').DataTable({
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50, 100],
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        paginate: {
                            previous: "Previous",
                            next: "Next"
                        }
                    }
                });
            });
        </script>
    </x-slot:js>
</x-layouts.backend>
