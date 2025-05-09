<x-layouts.backend title="Blogs">
    <div class="row">
        <div class="col-md-12 mb-4 text-end">
            <a href="{{ route('backend.blog.create') }}" class="btn btn-outline-success custom-hover">
                <i class="fas fa-plus me-1"></i> Add New Post
            </a>
        </div>

        <div class="col-md-12">
            <div class="card shadow-sm rounded">
                <div class="card-body">
                    <table id="datatable" class="table table-bordered align-middle text-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="text-uppercase small text-center">#</th>
                                <th scope="col" class="text-uppercase small text-center">Thumbnail</th>
                                <th scope="col" class="text-uppercase small text-center">Title</th>
                                <th scope="col" class="text-uppercase small text-center">Status</th>
                                <th scope="col" class="text-uppercase small text-center">Featured</th>
                                <th scope="col" class="text-uppercase small text-center">Created At</th>
                                <th scope="col" class="text-uppercase small text-center">Updated At</th>
                                <th scope="col" class="text-uppercase small text-center" style="width: 20%">Menu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
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
                                    <td class="d-flex justify-content-center align-items-center gap-2">
                                        <a href="{{ route('backend.blog.edit', ['blog' => $post->slug]) }}" class="btn btn-sm btn-outline-warning custom-hover">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form class="d-inline-block" action="{{ route('backend.blog.destroy', ['blog' => $post->slug]) }}" onsubmit="return confirm('Delete this post?')" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger custom-hover">
                                                <i class="fas fa-trash-alt me-1"></i> Delete
                                            </button>
                                        </form>
                                        @if (is_null($post->sticky))
                                            <form class="d-inline-block" action="{{ route('backend.stickyArticle.store', ['blog' => $post->slug]) }}" onsubmit="return confirm('Add to sticky news?')" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary custom-hover">
                                                    <i class="fas fa-thumbtack me-1"></i> Sticky
                                                </button>
                                            </form>
                                        @endif
                                        @if (is_null($post->tipsAndTrick))
                                            <form class="d-inline-block" action="{{ route('backend.tipsAndTrick.store', ['blog' => $post->slug]) }}" onsubmit="return confirm('Add to Tips & Trick?')" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success custom-hover">
                                                    <i class="fas fa-lightbulb me-1"></i> Tips & Trick
                                                </button>
                                            </form>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-slot:css>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </x-slot>
    
    <x-slot:js>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            $(document).ready(function () {
                $('#datatable').DataTable();
            });
        </script>
    </x-slot>
</x-layouts.backend>
