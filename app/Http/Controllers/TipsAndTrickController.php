<?php

namespace App\Http\Controllers;

use App\Models\TipsAndTrick;
use Illuminate\Http\Request;

class TipsAndTrickController extends Controller
{
    // Menampilkan daftar Tips and Trick
    public function index(Request $request)
    {
        // Mengambil artikel Tips and Trick yang dipublikasikan dan mengurutkannya
        $tipsAndTricks = TipsAndTrick::where('published', true)
                                     ->latest()
                                     ->paginate(10);

        // Menampilkan tampilan dengan data artikel
        return view('tips.index', [
            'tipsAndTricks' => $tipsAndTricks
        ]);
    }

    // Menampilkan detail Tips and Trick
    public function show($id)
    {
        // Menampilkan artikel berdasarkan ID
        $tipsAndTrick = TipsAndTrick::findOrFail($id);

        return view('tips.show', [
            'tipsAndTrick' => $tipsAndTrick
        ]);
    }
}
