<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminChapterController extends Controller
{
    /**
     * GET /api/admin/chapters
     * Supports: ?search=, ?status=published|draft, ?page=, ?limit=30, ?sort=order|title|created_at, ?dir=asc|desc
     */
    public function index(Request $request): JsonResponse
    {
        $query = Chapter::query();

        // Search
        if ($search = $request->query('search')) {
            if (preg_match('/^#(\d+)$/', trim($search), $m)) {
                $query->where('order', (int) $m[1]);
            } else {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('title_gu', 'like', "%{$search}%")
                      ->orWhere('title_en', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('description_gu', 'like', "%{$search}%")
                      ->orWhere('description_en', 'like', "%{$search}%");
                });
            }
        }

        // Status filter
        if ($status = $request->query('status')) {
            if (in_array($status, ['published', 'draft'])) {
                $query->where('status', $status);
            }
        }

        // Sort
        $sortCol = in_array($request->query('sort'), ['order', 'title', 'created_at'])
            ? $request->query('sort')
            : 'order';
        $sortDir = $request->query('dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortCol, $sortDir);

        // Pagination for infinite scroll
        $limit = min((int) $request->query('limit', 30), 100);
        $page  = max((int) $request->query('page', 1), 1);

        $total   = $query->count();
        $chapters = $query->select([
            'id', 'title', 'title_gu', 'title_en', 
            'description', 'description_gu', 'description_en', 
            'order', 'status', 'published_at', 'created_at'
        ])
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

        return response()->json([
            'data'        => $chapters,
            'total'       => $total,
            'page'        => $page,
            'limit'       => $limit,
            'has_more'    => ($page * $limit) < $total,
        ]);
    }

    /**
     * POST /api/admin/chapters
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'          => 'nullable|string|max:255',
            'title_gu'       => 'nullable|string|max:255',
            'title_en'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_gu' => 'nullable|string',
            'description_en' => 'nullable|string',
            'content'        => 'nullable|string',
            'content_gu'     => 'nullable|string',
            'content_en'     => 'nullable|string',
            'order'          => 'required|integer|min:1',
            'status'         => 'in:published,draft',
        ]);

        // Fallbacks
        $data['title']       = $data['title']       ?: ($data['title_gu']       ?: $data['title_en']       ?: "Chapter {$data['order']}");
        $data['title_gu']    = $data['title_gu']    ?: $data['title'];
        $data['title_en']    = $data['title_en']    ?: $data['title'];
        $data['content']     = $data['content']     ?: ($data['content_gu']     ?: $data['content_en']     ?: '');
        $data['content_gu']  = $data['content_gu']  ?: $data['content'];
        $data['content_en']  = $data['content_en']  ?: $data['content'];

        if (($data['status'] ?? 'draft') === 'published') {
            $data['published_at'] = Carbon::now();
        }

        $chapter = Chapter::create($data);

        return response()->json($chapter, 201);
    }

    /**
     * GET /api/admin/chapters/{id}
     */
    public function show(int $id): JsonResponse
    {
        $chapter = Chapter::findOrFail($id);
        return response()->json($chapter);
    }

    /**
     * PUT /api/admin/chapters/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $chapter = Chapter::findOrFail($id);

        $data = $request->validate([
            'title'          => 'nullable|string|max:255',
            'title_gu'       => 'nullable|string|max:255',
            'title_en'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_gu' => 'nullable|string',
            'description_en' => 'nullable|string',
            'content'        => 'nullable|string',
            'content_gu'     => 'nullable|string',
            'content_en'     => 'nullable|string',
            'order'          => 'sometimes|integer|min:1',
            'status'         => 'sometimes|in:published,draft',
        ]);

        // Synchronize main fields if missing
        if (isset($data['title_gu']) || isset($data['title_en'])) {
            $data['title'] = $data['title'] ?? ($data['title_gu'] ?? $data['title_en'] ?? $chapter->title);
        }
        if (isset($data['content_gu']) || isset($data['content_en'])) {
            $data['content'] = $data['content'] ?? ($data['content_gu'] ?? $data['content_en'] ?? $chapter->content);
        }

        if (isset($data['status']) && $data['status'] === 'published' && !$chapter->published_at) {
            $data['published_at'] = Carbon::now();
        }

        $chapter->update($data);

        return response()->json($chapter);
    }

    /**
     * DELETE /api/admin/chapters/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        Chapter::findOrFail($id)->delete();
        return response()->json(['message' => 'Chapter deleted.']);
    }

    /**
     * POST /api/admin/chapters/bulk
     */
    public function bulk(Request $request): JsonResponse
    {
        $data = $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'integer',
            'action' => 'required|in:publish,draft,delete',
        ]);

        $chapters = Chapter::whereIn('id', $data['ids']);

        switch ($data['action']) {
            case 'publish':
                $chapters->update(['status' => 'published', 'published_at' => Carbon::now()]);
                break;
            case 'draft':
                $chapters->update(['status' => 'draft']);
                break;
            case 'delete':
                $chapters->delete();
                break;
        }

        return response()->json(['message' => "Bulk {$data['action']} applied to " . count($data['ids']) . ' chapters.']);
    }
}
