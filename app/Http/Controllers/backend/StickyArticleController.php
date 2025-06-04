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
        // Eager load blog dan thumbnail agar relasi langsung tersedia
        $stickies = StickyArticle::with('blog.thumbnail')->get(); 

        // // Debug log (optional, supaya kamu yakin data sudah benar)
        // foreach ($stickies as $sticky) {
        //     \Log::debug('sticky id: ' . $sticky->id);
        //     if ($sticky->blog) {
        //         \Log::debug('blog id: ' . $sticky->blog->id);
        //         \Log::debug('blog title: ' . $sticky->blog->title);
        //         if ($sticky->blog->thumbnail) {
        //             \Log::debug('thumbnail path: ' . $sticky->blog->thumbnail->path);
        //         } else {
        //             \Log::debug('no thumbnail');
        //         }
        //     } else {
        //         \Log::debug('no blog');
        //     }
        // }

        return view('backend.sticky_article.index', compact('stickies'));
    }




    public function store(Blog $blog) {
        $stickyArticle = new StickyArticle;
        $stickyArticle->blog()->associate($blog);
        $stickyArticle->save();

        return redirect()->route('backend.blog.index');
    }


public function destroy(StickyArticle $stickyArticle)
{
    $stickyArticle->delete();
    return redirect()->route('backend.stickyArticle.index')->with('success', 'Sticky article removed.');
}

}
