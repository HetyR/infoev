<x-layouts.backend title="Marketplaces">
    <section class="section">
        <div class="row">
            <div class="col-md-12 mb-4 text-end">
                <a href="{{ route('backend.marketplace.create') }}" class="btn btn-sm btn-success hover-shadow">
                    <i class="fas fa-plus me-1"></i> Add New Marketplace
                </a>
            </div>  
            <div class="col-md-12">
                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col" class="text-center">#</th>
                                        <th scope="col" class="text-center">Logo</th>
                                        <th scope="col" class="text-center">Name</th>
                                        <th scope="col" class="text-center">Menu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($marketplaces as $marketplace)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if (!is_null($marketplace->logo))
                                                    <img src="{{ asset('storage/' . $marketplace->logo->path) }}" alt="thumbnail" class="img-fluid" style="max-width: 150px">
                                                @endif
                                            </td>
                                            <td>{{ $marketplace->name }}</td>
                                            <td>
                                                <a href="{{ route('backend.marketplace.edit', ['marketplace' => $marketplace->id]) }}" 
                                                   class="btn btn-sm btn-outline-warning me-1 hover-shadow">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <form class="d-inline-block" 
                                                      action="{{ route('backend.marketplace.destroy', ['marketplace' => $marketplace->id]) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Delete this marketplace?')">
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
