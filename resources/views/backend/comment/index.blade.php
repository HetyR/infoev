<x-layouts.backend title="Comments Management">
    <div class="row justify-content-center">
        <div class="col-12 my-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Comments List</h5>
                </div>
                <div class="card-body table-responsive">
                    <table id="datatable" class="table table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center">Reply</th>
                                <th scope="col" class="text-center">Name</th>
                                <th scope="col" class="text-center">Comment</th>
                                <th scope="col" class="text-center">Place</th>
                                <th scope="col" class="text-center">Menu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comments as $comment)
                                <tr>
                                    <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                                    <td class="text-center">
                                        @if ($comment->user_id !== null)
                                            <span class="badge text-bg-primary">Login</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($comment->parent_id !== null)
                                            <span class="badge text-bg-success">Reply</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $comment->name }}</td>
                                    <td class="text-center">{{ $comment->comment }}</td>
                                    <td class="text-center">{{ $comment->commentable->name }}</td>
                                    <td class="d-flex flex-wrap gap-1 justify-content-center">
                                        <a href="{{ route('vehicle.show', ['vehicle' => $comment->commentable->slug]) }}#comment"
                                            class="btn btn-sm btn-outline-secondary" target="_blank">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                        <form action="{{ route('backend.comment.moderate.name', $comment->id) }}"
                                            method="POST" onsubmit="return confirm('Moderate this comment name?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-user-edit me-1"></i>
                                                @if ($comment->hide_name)
                                                    Unmoderate Name
                                                @else
                                                    Moderate Name
                                                @endif
                                            </button>
                                        </form>
                                        <form action="{{ route('backend.comment.moderate.comment', $comment->id) }}"
                                            method="POST" onsubmit="return confirm('Moderate this comment?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-comment-slash me-1"></i>
                                                @if ($comment->hide_comment)
                                                    Unmoderate Comment
                                                @else
                                                    Moderate Comment
                                                @endif
                                            </button>
                                        </form>
                                        <form action="{{ route('backend.comment.destroy', $comment->id) }}"
                                            method="POST" onsubmit="return confirm('Delete this comment?')">
                                            @csrf
                                            @method('DELETE')
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
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function() {
                $('#datatable').DataTable({
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50, 100],
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        paginate: {
                            previous: "Previous",
                            next: "Next"
                        }
                    }
                });
            });
        </script>
    </x-slot:js>
</x-layouts.backend>
