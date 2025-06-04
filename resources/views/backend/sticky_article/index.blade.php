<x-layouts.backend title="Sticky Articles">
    <div class="row">
        <div class="col-md-12 my-4">
            <div class="card shadow-sm rounded">
                <div class="card-body">
                    <table id="datatable" class="table align-middle text-center">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">Thumbnail</th>
                                <th scope="col" class="text-center">Title</th>
                                <th scope="col" class="text-center">Added At</th>
                                <th scope="col" class="text-center" style="width: 15%">Menu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stickies as $sticky)
                                <tr>
                                    <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                                    <td class="text-center">
                                        @if ($sticky && $sticky->thumbnail)
                                            <img src="{{ asset('storage/' . $sticky->thumbnail->path) }}"
                                                alt="Thumbnail" class="img-fluid" style="max-width: 150px;">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $sticky->title ?? 'No Blog Found' }}
                                    </td>
                                    <td class="text-center">{{ $sticky->created_at->format('d M Y H:i') }}</td>
                                    <td class="text-center">
                                        <form class="d-inline-block"
                                            action="{{ route('backend.stickyArticle.destroy', ['stickyArticle' => $sticky->blog_id]) }}"
                                            method="POST" onsubmit="return confirm('Remove sticky article?')">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE">
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

    <x-slot:css>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css"
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
                color: #fff !important;
                background-color: #0d6efd !important;
                border-color: #0d6efd !important;
            }
        </style>
    </x-slot:css>

    <x-slot:js>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"
            referrerpolicy="no-referrer"></script>
        <script>
            $(document).ready(function() {
                $('#datatable').DataTable();
            });
        </script>
    </x-slot:js>
</x-layouts.backend>
