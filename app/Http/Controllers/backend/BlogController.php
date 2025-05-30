<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\TipsAndTrick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Contract\Messaging;

class BlogController extends Controller
{
    protected Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function index()
    {
        return view('backend.blog.index', [
            'posts' => Blog::with(['thumbnail', 'sticky', 'tipsAndTrick'])
                ->latest()
                ->paginate(10),
        ]);
    }
    public function storeTipsAndTrick(Blog $blog, Request $request)
    {
        // Validasi jika diperlukan
        $request->validate([
            'content' => 'required|string',
        ]);

        if (!$blog->tipsAndTrick) {
            $tipsAndTrick = new TipsAndTrick();
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string|max:500',
            'content' => 'required|string',
            'status' => 'required|boolean',
            'featured' => 'nullable',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $formFields = [
            'title' => $validated['title'],
            'summary' => $validated['summary'],
            'content' => $validated['content'],
            'published' => $validated['status'],
            'featured' => $request->has('featured') ? 1 : 0,
        ];

        $blog = Blog::create($formFields);

        $imageUrl = null;

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('blog', 'public');
            $blog->thumbnail()->create(['path' => $path]);
            $imageUrl = Storage::disk('public')->url($path);
        }

        // Tambahkan gambar ke Notification, bukan hanya di data
        $message = CloudMessage::fromArray([
            'topic' => 'infoev_news',
            'notification' => [
                'title' => $blog->title,
                'body' => $blog->summary,
                'image' => $imageUrl ?? '',
            ],
            'data' => [
        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        'id' => (string) $blog->id,
        'title' => $blog->title,
        'summary' => $blog->summary,
        'image' => $imageUrl ?? '',
    ],
        ]);

        $this->messaging->send($message);

        return redirect()->route('backend.blog.index');
    }

    public function edit(Blog $blog)
    {
        return view('backend.blog.edit', [
            'post' => $blog,
            'thumbnail' => $blog->thumbnail,
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $formFields = [
            'title' => $request->title,
            // 'slug' => $request->slug ?? $blog->status,
            'slug' => $request->filled('slug') ? Str::slug($request->slug) : $blog->slug, // gunakan slug request, jika tidak ada pakai slug lama
            'summary' => $request->summary,
            'content' => $request->content,
            'published' => $request->status,
            'featured' => $request->featured == 'on' ?? 0,
        ];

        $blog->update($formFields);

        if ($request->hasFile('thumbnail')) {
            $thumbnail = $blog->thumbnail;
            if (!is_null($thumbnail)) {
                Storage::delete('public/' . $thumbnail->path);
                $thumbnail->delete();
            }

            $blog->thumbnail()->create([
                'path' => $request->file('thumbnail')->store('blog', 'public'),
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
