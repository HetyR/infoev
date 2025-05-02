<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request) {
        $commentable = '';
        switch ($request->type) {
            case 'vehicle':
                $commentable = Vehicle::findOrFail($request->id);
                break;
            default:
                break;
        }

        $request->validate([
            'name' => 'max:25',
            'comment' => 'required|max:255'
        ]);

        $fields = [
            'name' => $request->name ?: null,
            'comment' => $request->comment,
            'parent_id' => $request->parent ?: null
        ];

        $commentable->comments()->create($fields);

        return redirect()->back()->withFragment('#comment');
    }
}
