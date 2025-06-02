<x-layouts.backend title="Brands">
    <div class="row">
        <div class="col-md-12 mb-4 text-end">
            <a href="{{ route('backend.brand.create') }}" class="btn btn-outline-success hover-shadow">
                <i class="fas fa-plus me-1"></i> Add New Brand
            </a>
        </div>
        <div class="col-md-12">
            <div class="card shadow-sm rounded">
                <div class="card-body">
                    <table id="datatable" class="table table-bordered align-middle text-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">Brand</th>
                                <th scope="col" class="text-center">Menu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($brands as $brand)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $brand->name }}</td>
                                    <td>
                                        <a href="{{ route('backend.brand.edit', ['brand' => $brand->slug]) }}" 
                                            class="btn btn-sm btn-outline-warning hover-shadow">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form class="d-inline-block" action="{{ route('backend.brand.destroy', ['brand' => $brand->slug]) }}" 
                                              onsubmit="return confirm('Delete this brand?')" method="POST">
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
    </x-slot>
    <x-slot:js>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <script>
            $(document).ready(function () {
                $('#datatable').DataTable();
            });
        </script>
    </x-slot>
</x-layouts.backend>
