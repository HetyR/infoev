<x-layouts.backend>
    <div class="row">
        <div class="col-md-12 mb-4">
            <a href="{{ route('backend.marketplace.create') }}" class="btn btn-success">Add New Marketplace</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Logo</th>
                    <th scope="col">Name</th>
                    <th scope="col">Menu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($marketplaces as $marketplace)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>
                            @if (!is_null($marketplace->logo))
                                <img src="{{ asset('storage/' . $marketplace->logo->path) }}" alt="thumbnail" class="img-fluid" style="max-width: 150px">
                            @endif
                        </td>
                        <td>{{ $marketplace->name }}</td>
                        <td>
                            <a href="{{ route('backend.marketplace.edit', ['marketplace' => $marketplace->id]) }}" class="btn btn-warning">Edit</a>
                            <form class="d-inline-block" action="{{ route('backend.marketplace.destroy', ['marketplace' => $marketplace->id]) }}" onsubmit="return confirm('Delete this marketplace?')" method="POST">
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