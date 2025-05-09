<x-layouts.backend title="Types">
    <section class="section">
        <div class="row">
            <div class="col-md-12 mb-4 text-end">
                <a href="{{ route('backend.type.create') }}" class="btn btn-outline-success hover-shadow">
                    <i class="fas fa-plus me-1"></i> Add New Type
                </a>
            </div>  
            <div class="col-md-12">
                <div class="card shadow-sm rounded">
                    <div class="card-body">       
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Tipe</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($types as $type)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $type->name }}</td>
                                            <td>
                                                <a href="{{ route('backend.type.edit', ['type' => $type->slug]) }}" 
                                                    class="btn btn-sm btn-outline-warning me-1 hover-shadow">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('backend.type.destroy', ['type' => $type->slug]) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Delete this type?')">
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
        </div>
    </section>
</x-layouts.backend>
