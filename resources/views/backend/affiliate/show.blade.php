<x-layouts.backend>
    <div class="row">
        <div class="col-md-12 mb-4">
            <a href="{{ route('backend.affiliate.create', ['vehicle' => $vehicle->slug]) }}" class="btn btn-success">Add New Affiliate Link</a>
        </div>

        <table class="table">
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
                            <a href="{{ route('backend.affiliate.edit', ['affiliate' => $affiliate->id, 'vehicle' => $vehicle->slug]) }}" class="btn btn-warning">Edit</a>
                            <form class="d-inline-block" action="{{ route('backend.affiliate.destroy', ['affiliate' => $affiliate->id]) }}" onsubmit="return confirm('Delete this affiliate link?')" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.backend>