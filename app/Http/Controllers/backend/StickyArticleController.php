<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\StickyArticle;
use Illuminate\Http\Request;

class StickyArticleController extends Controller
{
    public function index()
    {
        // Eager load blog dan thumbnail
        $stickies = StickyArticle::with('blog.thumbnail')->get();

        return view('backend.sticky_article.index', compact('stickies'));
    }

    public function store(Blog $blog)
    {
        $stickyArticle = new StickyArticle;
        $stickyArticle->blog()->associate($blog);
        $stickyArticle->save();

        return redirect()->route('backend.blog.index');
    }

    public function destroy(StickyArticle $stickyArticle)
    {
        if (!$stickyArticle) {
            return redirect()->route('backend.stickyArticle.index')
                ->with('error', 'Sticky article not found.');
        }

        $stickyArticle->delete();

        return redirect()->route('backend.stickyArticle.index')
            ->with('success', 'Sticky article removed.');
    }
}
