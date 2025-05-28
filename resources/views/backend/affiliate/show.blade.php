<x-layouts.backend title="Affiliate List">
    <div class="row">
        <div class="row">
            <div class="col-md-12 mb-4 text-end">
                <a href="{{ route('backend.affiliate.create', ['vehicle' => $vehicle->slug]) }}" class="btn btn-sm btn-success custom-hover">
                    <i class="fas fa-plus me-1"></i> Add New Affiliate Link
                </a>
            </div>
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">Affiliate Links</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Marketplace</th>
                                <th scope="col">Description</th>
                                <th scope="col">Price</th>
                                <th scope="col">Link</th>
                                <th scope="col">Menu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vehicle->affiliateLinks as $affiliate)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $affiliate->marketplace->name }}</td>
                                    <td>{{ $affiliate->desc }}</td>
                                    <td>{{ $affiliate->price }}</td>
                                    <td>
                                        <a href="{{ $affiliate->link }}" target="_blank">{{ $affiliate->link }}</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('backend.affiliate.edit', ['affiliate' => $affiliate->id, 'vehicle' => $vehicle->slug]) }}" 
                                            class="btn btn-sm btn-outline-warning hover-shadow">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form class="d-inline-block" action="{{ route('backend.affiliate.destroy', ['affiliate' => $affiliate->id]) }}" 
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
</x-layouts.backend>
