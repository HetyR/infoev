<x-layouts.backend>
    <div class="row mb-4">
        <div class="col-md-6">
            <a href="{{ route('backend.vehicle.create') }}" class="btn btn-success">Add New Vehicle</a>
        </div>
</div>
<!-- ini tambahan filter -->
    <table id="datatable" class="table">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th>
                    <select id="typeFilter" class="form-select" aria-label="Filter by Type">
                        <option value="" {{ request('type_id') === null ? 'selected' : '' }}>Semua Tipe</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </th>
                <th>
                    <select id="brandFilter" class="form-select" aria-label="Filter by Brand">
                        <option value="" {{ request('brand_id') === null ? 'selected' : '' }}>Semua Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </th>
                <th></th>
                <th style="width: 18%;"></th>
                <th style="width: 188%;">
                    <select id="marketplaceFilter" class="form-select" aria-label="Filter by Marketplace">
                        <option value="" {{ request('marketplace_id') === null ? 'selected' : '' }}>Semua Marketplace</option>
                        <option value="none" {{ request('marketplace_id') === 'none' ? 'selected' : '' }}>Tidak Ada Marketplace</option>
                        @foreach ($marketplaces as $marketplace)
                            <option value="{{ $marketplace->id }}" {{ request('marketplace_id') == $marketplace->id ? 'selected' : '' }}>
                                {{ $marketplace->name }}
                            </option>
                        @endforeach
                    </select>
                </th>
            </tr>
        </thead>
        <!-- sampai ini -->
         <!-- ada tambahan javascript dibawah -->
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Thumbnail</th>
                <th scope="col">Type</th>
                <th scope="col">Brand</th>
                <th scope="col">Name</th>
                <th scope="col" style="width: 18%;">Menu</th>
                <th scope="col" style="width: 18%;">Marketplace</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vehicles as $vehicle)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>
                        @if ($vehicle->pictures->isNotEmpty())
                            @if ($vehicle->pictures->where('thumbnail', 1)->isNotEmpty())
                            <img src="{{ asset('storage/' . $vehicle->pictures->first()->path) }}" alt="thumbnail" class="img-fluid" style="max-width: 150px">
                            @else
                            <img src="{{ asset('storage/' . $vehicle->pictures->first()->path) }}" alt="thumbnail" class="img-fluid" style="max-width: 150px">
                            @endif
                        @endif  
                    </td>
                    <td>{{ $vehicle->type->name }}</td>
                    <td>{{ $vehicle->brand->name }}</td>
                    <td>{{ $vehicle->name }}</td>
                    <td>
                        <a href="{{ route('backend.vehicle.edit', ['vehicle' => $vehicle->slug]) }}" class="btn btn-warning mt-1">Edit</a>
                        <a href="{{ route('backend.affiliate.show', ['vehicle' => $vehicle->slug]) }}" class="btn btn-primary mx-1 mt-1">Affiliate Link</a>
                        <form class="d-inline-block mt-1" action="{{ route('backend.vehicle.destroy', ['vehicle' => $vehicle->slug]) }}" onsubmit="return confirm('Delete this vehicle?')" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        <a href="{{ route('vehicle.show', ['vehicle' => $vehicle->slug]) }}" class="btn btn-secondary mt-1" target="_blank" rel="noopener">View</a>
                    </td>
                    <td>
                        @foreach ($vehicle->affiliate as $affiliate)
                            <span style="display: inline-block; margin-right: 5px;">
                                {{ $affiliate->marketplace->name ?? 'Marketplace tidak tersedia' }}
                            </span>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <x-slot:css>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css"
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    </x-slot>

    <x-slot:js>
        <!-- ini tambahan javasript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const typeFilter = document.getElementById('typeFilter');
                const brandFilter = document.getElementById('brandFilter');
                const marketplaceFilter = document.getElementById('marketplaceFilter');

                function updateUrlFilter() {
                    const selectedType = typeFilter.value;
                    const selectedBrand = brandFilter.value;
                    const selectedMarketplace = marketplaceFilter.value;
                    const url = new URL(window.location.href);

                    if (selectedType) {
                        url.searchParams.set('type_id', selectedType);
                    } else {
                        url.searchParams.delete('type_id');
                    }

                    if (selectedBrand) {
                        url.searchParams.set('brand_id', selectedBrand);
                    } else {
                        url.searchParams.delete('brand_id');
                    }

                    if (selectedMarketplace) {
                        url.searchParams.set('marketplace_id', selectedMarketplace);
                    } else {
                        url.searchParams.delete('marketplace_id');
                    }

                    window.location.href = url.toString();
                }

                typeFilter.addEventListener('change', updateUrlFilter);
                brandFilter.addEventListener('change', updateUrlFilter);
                marketplaceFilter.addEventListener('change', updateUrlFilter);
            });
        </script>
        <!-- sampai ini -->
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
