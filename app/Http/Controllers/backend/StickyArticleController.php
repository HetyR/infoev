<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\StickyArticle;
use Illuminate\Http\Request;

class StickyArticleController extends Controller
{
public function index() {
    $stickies = StickyArticle::with('blog.thumbnail')->latest()->get();

    // Debug
    foreach ($stickies as $sticky) {
        logger('sticky id: ' . $sticky->id);
        logger('blog id: ' . ($sticky->blog?->id ?? 'NULL'));
        logger('blog title: ' . ($sticky->blog?->title ?? 'NULL'));
        logger('thumbnail path: ' . ($sticky->blog?->thumbnail?->path ?? 'NULL'));
    }

    return view('backend.sticky_article.index', [
        'stickies' => $stickies
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
