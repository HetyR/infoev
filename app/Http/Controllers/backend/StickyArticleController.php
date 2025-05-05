<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\StickyArticle;
use Illuminate\Http\Request;

class StickyArticleController extends Controller
{
    public function index() {
        return view('backend.sticky_article.index', [
            'stickies' => StickyArticle::with('blog.thumbnail')
                        ->latest()
                        ->get()
        ]);
    }

    public function store(Blog $blog) {
        $stickyArticle = new StickyArticle;
        $stickyArticle->blog()->associate($blog);
        $stickyArticle->save();

        return redirect()->route('backend.blog.index');
    }

    public function destroy(StickyArticle $stickyArticle) {
        $stickyArticle->delete();
        return redirect()->route('backend.stickyArticle.index');
    }
}
