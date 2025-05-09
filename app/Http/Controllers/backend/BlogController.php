<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\TipsAndTrick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        return view('backend.blog.index', [
            'posts' => Blog::with(['thumbnail', 'sticky', 'tipsAndTrick'])->latest()->get()
        ]);
    }
  public function storeTipsAndTrick(Blog $blog, Request $request)
    {
        // Validasi jika diperlukan
        $request->validate([
            'content' => 'required|string', 
        ]);

        if (!$blog->tipsAndTrick) {
            $tipsAndTrick = new TipsAndTrick;
            $tipsAndTrick->blog()->associate($blog);
            $tipsAndTrick->content = $request->input('content');
            $tipsAndTrick->save();
        }

        return redirect()->route('backend.blog.index')->with('success', 'Tips & Trick berhasil ditambahkan ke blog');
    }

    public function destroyTipsAndTrick(Blog $blog)
    {
        $blog->tipsAndTrick()->delete();

        return redirect()->route('backend.blog.index');
    }

    public function create()
    {
        return view('backend.blog.create');
    }

    public function store(Request $request)
    {
        $formFields = [
            'title' => $request->title,
            'summary' => $request->summary,
            'content' => $request->content,
            'published' => $request->status,
            'featured' => $request->featured == 'on' ?? 0
        ];

        $blog = Blog::create($formFields);

        if ($request->hasFile('thumbnail')) {
            $blog->thumbnail()->create([
                'path' => $request->file('thumbnail')->store('blog', 'public')
            ]);
        }

        return redirect()->route('backend.blog.index');
    }

    public function edit(Blog $blog)
    {
        return view('backend.blog.edit', [
            'post' => $blog,
            'thumbnail' => $blog->thumbnail
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $formFields = [
            'title' => $request->title,
            'slug' => $request->slug ?? $blog->status,
            'summary' => $request->summary,
            'content' => $request->content,
            'published' => $request->status,
            'featured' => $request->featured == 'on' ?? 0
        ];

        $blog->update($formFields);

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $blog->thumbnail;
            if (!is_null($thumbnail)) {
                Storage::delete('public/' . $thumbnail->path);
                $thumbnail->delete();
            }

            $blog->thumbnail()->create([
                'path' => $request->file('thumbnail')->store('blog', 'public')
            ]);
        }

        return redirect()->route('backend.blog.index');
    }

    public function destroy(Blog $blog)
    {
        $thumbnail = $blog->thumbnail;
        if (!is_null($thumbnail)) {
            Storage::delete('public/' . $thumbnail->path);
            $thumbnail->delete();
        }

        $blog->delete();
        return redirect()->route('backend.blog.index');
    }
}
