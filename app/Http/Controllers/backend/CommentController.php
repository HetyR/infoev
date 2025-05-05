<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function index() {
        return view('backend.comment.index', [
            'comments' => Comment::latest()->get()
        ]);
    }

    public function moderateName(Comment $comment) {
        $comment->hide_name = (bool) !$comment->hide_name;
        $comment->save();

        return redirect()->route('backend.comment.index');
    }

    public function moderateComment(Comment $comment) {
        $comment->hide_comment = (bool) !$comment->hide_comment;
        $comment->save();

        return redirect()->route('backend.comment.index');
    }

    public function destroy(Comment $comment) {
        $comment->delete();
        return redirect()->route('backend.comment.index');
    }
}
