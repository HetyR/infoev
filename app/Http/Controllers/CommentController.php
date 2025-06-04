<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
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
            'comment' => 'required|max:255',
        ]);

        $fields = [
            'name' => $request->name ?: null,
            'comment' => $request->comment,
            'parent_id' => $request->parent ?: null,
        ];

        $commentable->comments()->create($fields);

        return redirect()->back()->withFragment('#comment');
    }

    public function storeApi(Request $request)
    {
        // Validasi input terlebih dahulu
        $validated = $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer',
            'name' => 'nullable|string|max:25',
            'comment' => 'required|string|max:255',
            'parent' => 'nullable|integer',
        ]);

        // Cari model commentable berdasarkan type dan id
        switch ($validated['type']) {
            case 'vehicle':
                $commentable = Vehicle::find($validated['id']);
                if (!$commentable) {
                    return response()->json(
                        [
                            'message' => 'Vehicle not found',
                        ],
                        404,
                    );
                }
                break;
            default:
                return response()->json(
                    [
                        'message' => 'Unsupported comment type',
                    ],
                    400,
                );
        }

        // Siapkan data komentar
        $fields = [
            'name' => $validated['name'] ?? null,
            'comment' => $validated['comment'],
            'parent_id' => $validated['parent'] ?? null,
        ];

        // Buat komentar
        $comment = $commentable->comments()->create($fields);

        // Response sukses dengan data komentar baru
        return response()->json(
            [
                'message' => 'Comment created successfully',
                'data' => $comment,
            ],
            201,
        );
    }
}
