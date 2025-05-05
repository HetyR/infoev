<x-layouts.backend>
    <div class="row">

        <table class="table">
            <thead>
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
                        <td>
                            <a href="{{ route('vehicle.show', ['vehicle' => $comment->commentable->slug]) }}#comment" class="btn btn-secondary" target="_blank">View</a>
                            <form class="d-inline-block" action="{{ route('backend.comment.moderate.name', $comment->id) }}" onsubmit="return confirm('Moderate this comment name?')" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-warning">@if ($comment->hide_name) Unmoderate Name @else Moderate Name @endif</button>
                            </form>
                            <form class="d-inline-block" action="{{ route('backend.comment.moderate.comment', $comment->id) }}" onsubmit="return confirm('Moderate this comment?')" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-warning">@if ($comment->hide_comment) Unmoderate Comment @else Moderate Comment @endif</button>
                            </form>
                            <form class="d-inline-block" action="{{ route('backend.comment.destroy', $comment->id) }}" onsubmit="return confirm('Delete this comment?')" method="POST">
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