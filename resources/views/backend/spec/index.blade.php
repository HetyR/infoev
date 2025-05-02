<x-layouts.backend>
    <div class="row">
        <div class="col-md-12 mb-4">
            <a href="{{ route('backend.spec.category.create') }}" class="btn btn-success">Add New Specification Category</a>
            <a href="{{ route('backend.spec.spec.create') }}" class="btn btn-success">Add New Specification</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Category</th>
                    <th scope="col">Specification</th>
                    <th scope="col">Priority</th>
                    <th scope="col">Menu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($specs as $cat)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $cat->name }}</td>
                        <td>
                            <table class="w-100">
                                @foreach ($cat->specs as $spec)
                                    <tr class="d-flex justify-content-between">
                                        <td>{{ $spec->name }}@if ($spec->hidden) {{ ' (hidden)' }} @endif</td>
                                        <td>
                                            <a href="{{ route('backend.spec.spec.edit', ['spec' => $spec->id]) }}" class="btn btn-warning">Edit</a>
                                            <form class="d-inline-block" action="{{ route('backend.spec.spec.destroy', ['spec' => $spec->id]) }}" onsubmit="return confirm('Delete this specification?')" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                        <td>{{ $cat->priority }}</td>
                        <td>
                            <a href="{{ route('backend.spec.category.edit', ['spec' => $cat->id]) }}" class="btn btn-warning">Edit</a>
                            <form class="d-inline-block" action="{{ route('backend.spec.category.destroy', ['spec' => $cat->id]) }}" onsubmit="return confirm('Delete this specification category?')" method="POST">
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