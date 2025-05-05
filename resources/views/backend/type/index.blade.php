<x-layouts.backend title="Types">
    <div class="row">
        <div class="col-md-12 mb-4">
            <a href="{{ route('backend.type.create') }}" class="btn btn-success">Add New Type</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Type</th>
                    <th scope="col">Menu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($types as $type)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $type->name }}</td>
                        <td>
                            <a href="{{ route('backend.type.edit', ['type' => $type->slug]) }}" class="btn btn-warning">Edit</a>
                            <form class="d-inline-block" action="{{ route('backend.type.destroy', ['type' => $type->slug]) }}" onsubmit="return confirm('Delete this type?')" method="POST">
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
