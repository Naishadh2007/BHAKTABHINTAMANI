<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\JsonResponse;

class ChapterController extends Controller
{
    /**
     * GET /api/chapters
     * Returns all published chapters with id, titles, descriptions, order.
     */
    public function index(): JsonResponse
    {
        $chapters = Chapter::select('id', 'title', 'title_gu', 'title_en', 'description', 'description_gu', 'description_en', 'order')
            ->where('status', 'published')
            ->orderBy('order')
            ->get();

        return response()->json($chapters);
    }

    /**
     * GET /api/chapters/{id}
     * Returns a single chapter with prev/next navigation links.
     */
    public function show(int $id): JsonResponse
    {
        $chapter = Chapter::where('status', 'published')->findOrFail($id);

        $prevChapter = Chapter::where('status', 'published')
            ->where('order', '<', $chapter->order)
            ->orderBy('order', 'desc')
            ->select('id', 'order', 'title', 'title_gu', 'title_en')
            ->first();

        $nextChapter = Chapter::where('status', 'published')
            ->where('order', '>', $chapter->order)
            ->orderBy('order', 'asc')
            ->select('id', 'order', 'title', 'title_gu', 'title_en')
            ->first();

        return response()->json([
            'chapter'      => $chapter,
            'prev_chapter' => $prevChapter,
            'next_chapter' => $nextChapter,
        ]);
    }
}
