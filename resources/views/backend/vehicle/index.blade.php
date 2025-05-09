<x-layouts.backend title="Vehicle List">
    <div class="row mb-4">
        <div class="col-md-12 text-end">
            <a href="{{ route('backend.vehicle.create') }}" class="btn btn-success hover-shadow">
                <i class="fas fa-plus me-1"></i> Add New Vehicle
            </a>
        </div>
    </div>
    <!-- Filter -->
    <div class="card shadow-sm rounded mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <select id="typeFilter" class="form-select" aria-label="Filter by Type">
                        <option value="" {{ request('type_id') === null ? 'selected' : '' }}>Semua Tipe</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="brandFilter" class="form-select" aria-label="Filter by Brand">
                        <option value="" {{ request('brand_id') === null ? 'selected' : '' }}>Semua Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="marketplaceFilter" class="form-select" aria-label="Filter by Marketplace">
                        <option value="" {{ request('marketplace_id') === null ? 'selected' : '' }}>Semua Marketplace</option>
                        <option value="none" {{ request('marketplace_id') === 'none' ? 'selected' : '' }}>Tidak Ada Marketplace</option>
                        @foreach ($marketplaces as $marketplace)
                            <option value="{{ $marketplace->id }}" {{ request('marketplace_id') == $marketplace->id ? 'selected' : '' }}>
                                {{ $marketplace->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm rounded">
        <div class="card-body">
                <table id="datatable" class="table align-middle text-center">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col" class="text-center">Thumbnail</th>
                            <th scope="col" class="text-center">Type</th>
                            <th scope="col" class="text-center">Brand</th>
                            <th scope="col" class="text-center">Name</th>
                            <th scope="col" class="text-center" style="width: 18%;">Menu</th>
                            <th scope="col" class="text-center" style="width: 18%;">Marketplace</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($vehicles as $vehicle)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                @if ($vehicle->pictures->isNotEmpty())
                                    <img src="{{ asset('storage/' . $vehicle->pictures->first()->path) }}" alt="thumbnail" class="img-fluid" style="max-width: 150px">
                                @endif
                            </td>
                            <td>{{ $vehicle->type->name }}</td>
                            <td>{{ $vehicle->brand->name }}</td>
                            <td>{{ $vehicle->name }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1 justify-content-center">
                                    <a href="{{ route('backend.vehicle.edit', ['vehicle' => $vehicle->slug]) }}" 
                                       class="btn btn-sm btn-outline-warning hover-shadow">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                            
                                    <a href="{{ route('backend.affiliate.show', ['vehicle' => $vehicle->slug]) }}" 
                                       class="btn btn-sm btn-outline-primary hover-shadow">
                                        <i class="fas fa-user-friends me-1"></i> Affiliate
                                    </a>
                            
                                    <form class="d-inline" action="{{ route('backend.vehicle.destroy', ['vehicle' => $vehicle->slug]) }}"
                                          onsubmit="return confirm('Delete this vehicle?')" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger hover-shadow">
                                            <i class="fas fa-trash-alt me-1"></i> Delete
                                        </button>
                                    </form>
                            
                                    <a href="{{ route('vehicle.show', ['vehicle' => $vehicle->slug]) }}" 
                                       class="btn btn-sm btn-outline-secondary hover-shadow" target="_blank" rel="noopener">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                </div>
                            </td>
                            
                            
                            <td>
                                @foreach ($vehicle->affiliate as $affiliate)
                                    <span class="badge bg-light text-dark">{{ $affiliate->marketplace->name ?? 'N/A' }}</span>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <x-slot:css>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css"
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    </x-slot>

    <x-slot:js>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const typeFilter = document.getElementById('typeFilter');
                const brandFilter = document.getElementById('brandFilter');
                const marketplaceFilter = document.getElementById('marketplaceFilter');

                function updateUrlFilter() {
                    const url = new URL(window.location.href);
                    typeFilter.value ? url.searchParams.set('type_id', typeFilter.value) : url.searchParams.delete('type_id');
                    brandFilter.value ? url.searchParams.set('brand_id', brandFilter.value) : url.searchParams.delete('brand_id');
                    marketplaceFilter.value ? url.searchParams.set('marketplace_id', marketplaceFilter.value) : url.searchParams.delete('marketplace_id');
                    window.location.href = url.toString();
                }

                typeFilter.addEventListener('change', updateUrlFilter);
                brandFilter.addEventListener('change', updateUrlFilter);
                marketplaceFilter.addEventListener('change', updateUrlFilter);
            });
        </script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
                $('#datatable').DataTable();
            });
        </script>
    </x-slot>
</x-layouts.backend>
