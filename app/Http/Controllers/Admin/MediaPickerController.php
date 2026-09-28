<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaPickerController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'in:images,files,all'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            'cursor' => ['nullable', 'integer', 'min:1'],
            'cursor_mode' => ['nullable', 'boolean'],
        ]);

        $perPage = $data['per_page'] ?? 20;
        $type = $data['type'] ?? 'images';
        $search = trim((string) ($data['search'] ?? ''));
        $cursorMode = $request->boolean('cursor_mode');

        $assets = MediaAsset::query()
            ->select(['id', 'name', 'file_path', 'mime_type', 'size', 'alt_text', 'caption'])
            ->when($type === 'images', fn ($query) => $query->where('mime_type', 'like', 'image/%'))
            ->when($type === 'files', fn ($query) => $query->where('mime_type', 'not like', 'image/%'))
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('name', 'like', '%'.$search.'%')
                        ->orWhere('alt_text', 'like', '%'.$search.'%')
                        ->orWhere('caption', 'like', '%'.$search.'%');
                });
            });

        if ($cursorMode) {
            $items = $assets
                ->when(isset($data['cursor']), fn ($query) => $query->where('id', '<', $data['cursor']))
                ->orderByDesc('id')
                ->limit($perPage + 1)
                ->get();
            $hasMore = $items->count() > $perPage;
            $items = $items->take($perPage)->values();

            return response()->json([
                'data' => $items->map(fn (MediaAsset $asset): array => $this->serializeAsset($asset))->values(),
                'meta' => [
                    'cursor_mode' => true,
                    'has_more' => $hasMore,
                    'next_cursor' => $hasMore ? $items->last()?->id : null,
                    'per_page' => $perPage,
                    'total' => null,
                ],
            ]);
        }

        $assets = $assets
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json([
            'data' => $assets->getCollection()->map(fn (MediaAsset $asset): array => $this->serializeAsset($asset))->values(),
            'meta' => [
                'cursor_mode' => false,
                'current_page' => $assets->currentPage(),
                'last_page' => $assets->lastPage(),
                'per_page' => $assets->perPage(),
                'total' => $assets->total(),
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeAsset(MediaAsset $asset): array
    {
        return [
            'id' => $asset->id,
            'name' => $asset->name,
            'url' => $asset->url(),
            'mime_type' => $asset->mime_type,
            'alt_text' => $asset->alt_text,
            'caption' => $asset->caption,
            'size' => $asset->size,
        ];
    }
}
