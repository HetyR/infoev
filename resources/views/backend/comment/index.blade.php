<x-layouts.backend title="Comments Management">
    <div class="row justify-content-center">
        <div class="col-12 my-4">
            <div class="card shadow-sm rounded">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Comments List</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Status</th>
                                <th scope="col">Reply</th>
                                <th scope="col">Name</th>
                                <th scope="col">Comment</th>
                                <th scope="col">Place</th>
                                <th scope="col">Menu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comments as $comment)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        @if ($comment->user_id !== null)
                                            <span class="badge text-bg-primary">Login</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($comment->parent_id !== null)
                                            <span class="badge text-bg-success">Reply</span>
                                        @endif
                                    </td>
                                    <td>{{ $comment->name }}</td>
                                    <td>{{ $comment->comment }}</td>
                                    <td>{{ $comment->commentable->name }}</td>
                                    <td class="d-flex flex-wrap gap-1">
                                        <a href="{{ route('vehicle.show', ['vehicle' => $comment->commentable->slug]) }}#comment" class="btn btn-sm btn-outline-secondary" target="_blank">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                        <form action="{{ route('backend.comment.moderate.name', $comment->id) }}" method="POST" onsubmit="return confirm('Moderate this comment name?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-user-edit me-1"></i>
                                                @if ($comment->hide_name) Unmoderate Name @else Moderate Name @endif
                                            </button>
                                        </form>
                                        <form action="{{ route('backend.comment.moderate.comment', $comment->id) }}" method="POST" onsubmit="return confirm('Moderate this comment?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-comment-slash me-1"></i>
                                                @if ($comment->hide_comment) Unmoderate Comment @else Moderate Comment @endif
                                            </button>
                                        </form>
                                        <form action="{{ route('backend.comment.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Delete this comment?')">
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
</x-layouts.backend>
