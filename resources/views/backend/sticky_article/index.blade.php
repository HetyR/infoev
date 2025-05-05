<x-layouts.backend>
    <div class="row">

        <table id="datatable" class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Thumbnail</th>
                    <th scope="col">Title</th>
                    <th scope="col">Added At</th>
                    <th scope="col" style="width: 15%">Menu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stickies as $sticky)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>
                            @if ($sticky->blog->thumbnail)
                                <img src="{{ asset('storage/' . $sticky->blog->thumbnail->path) }}" alt="" class="img-fluid" style="max-width: 150px">
                            @endif
                        </td>
                        <td>{{ $sticky->blog->title }}</td>
                        <td>{{ $sticky->created_at }}</td>
                        <td>
                            <form class="d-inline-block mt-1" action="{{ route('backend.stickyArticle.destroy', ['stickyArticle' => $sticky->id]) }}" onsubmit="return confirm('Remove sticky article?')" method="POST">
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