<x-layouts.backend>
    <div class="row">
        <div class="col-md-12 mb-4">
            <a href="{{ route('backend.blog.create') }}" class="btn btn-success">Add New Post</a>
        </div>

        <table id="datatable" class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Thumbnail</th>
                    <th scope="col">Title</th>
                    <th scope="col">Status</th>
                    <th scope="col">Featured</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Updated At</th>
                    <th scope="col" style="width: 15%">Menu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>
                            @if ($post->thumbnail)
                                <img src="{{ asset('storage/' . $post->thumbnail->path) }}" alt="" class="img-fluid" style="max-width: 150px">
                            @endif
                        </td>
                        <td>{{ $post->title }}</td>
                        <td>{!! $post->published ? '<span class="badge bg-primary">Published</span>' : '<span class="badge bg-info">Documentation</span>' !!}</td>
                        <td>{!! $post->featured ? '<span class="badge bg-success">Featured</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
                        <td>{{ $post->created_at }}</td>
                        <td>{{ $post->updated_at }}</td>
                        <td>
                            <a href="{{ route('backend.blog.edit', ['blog' => $post->slug]) }}" class="btn btn-warning mt-1">Edit</a>
                            <form class="d-inline-block mt-1" action="{{ route('backend.blog.destroy', ['blog' => $post->slug]) }}" onsubmit="return confirm('Delete this post?')" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            @if (is_null($post->sticky))
                                <form class="d-inline-block mt-1" action="{{ route('backend.stickyArticle.store', ['blog' => $post->slug]) }}" onsubmit="return confirm('Add to sticky news?')" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Sticky</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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