<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\TipsAndTrick;
use Illuminate\Http\Request;

class TipsAndTrickController extends Controller
{
    public function index()
    {
        return view('backend.tips_and_trick.index', [
            'tips' => TipsAndTrick::with('blog.thumbnail')
                        ->latest()
                        ->get()
        ]);
    }

    public function store(Blog $blog)
    {
        $tips = new TipsAndTrick;
        $tips->blog()->associate($blog);
        $tips->save();

        return redirect()->route('backend.blog.index');
    }

    public function destroy(TipsAndTrick $tipsAndTrick)
    {
        $tipsAndTrick->delete();

        return redirect()->route('backend.tipsAndTrick.index');
    }
}
