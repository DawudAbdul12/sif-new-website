<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Support\UniqueSlug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request, string $type): View
    {
        $this->ensureValidType($type);

        $documents = tap(Document::query()
            ->forType($type)
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%')), function ($query) use ($type): void {
                if ($type === 'contracts') {
                    $query->latest('created_at');

                    return;
                }

                $query->orderBy('sort_order')->latest('document_date')->latest();
            })
            ->paginate(12)
            ->withQueryString();

        return view('admin.documents.index', [
            'documents' => $documents,
            'type' => $type,
            'typeLabel' => Document::TYPES[$type],
        ]);
    }

    public function create(string $type): View
    {
        $this->ensureValidType($type);

        return view('admin.documents.create', [
            'document' => new Document(['type' => $type]),
            'type' => $type,
            'typeLabel' => Document::TYPES[$type],
        ]);
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        $this->ensureValidType($type);

        $data = $this->validated($request);
        $data['type'] = $type;
        $data['slug'] = UniqueSlug::make('documents', $data['slug'] ?: $data['title']);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $this->attachUpload($request, $data, 'file', 'repository-documents', 'file');
        $this->attachUpload($request, $data, 'cover_image', 'repository-covers', 'cover_image');

        $document = Document::create($data);

        return redirect()
            ->route('admin.documents.edit', [$type, $document])
            ->with('status', 'Document created.');
    }

    public function edit(string $type, Document $document): View
    {
        $this->ensureValidType($type);
        abort_unless($document->type === $type, 404);

        return view('admin.documents.edit', [
            'document' => $document,
            'type' => $type,
            'typeLabel' => Document::TYPES[$type],
        ]);
    }

    public function update(Request $request, string $type, Document $document): RedirectResponse
    {
        $this->ensureValidType($type);
        abort_unless($document->type === $type, 404);

        $data = $this->validated($request, $document);
        $data['slug'] = UniqueSlug::make('documents', $data['slug'] ?: $data['title'], $document);
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $document->published_at ?? now()) : null;
        $data['updated_by'] = $request->user()->id;

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($document->file_path);
            $this->attachUpload($request, $data, 'file', 'repository-documents', 'file');
        }

        if ($request->hasFile('cover_image')) {
            Storage::disk('public')->delete($document->cover_image_path);
            $this->attachUpload($request, $data, 'cover_image', 'repository-covers', 'cover_image');
        }

        $document->update($data);

        return redirect()
            ->route('admin.documents.edit', [$type, $document])
            ->with('status', 'Document updated.');
    }

    public function destroy(string $type, Document $document): RedirectResponse
    {
        $this->ensureValidType($type);
        abort_unless($document->type === $type, 404);

        $document->delete();

        return redirect()
            ->route('admin.documents.index', $type)
            ->with('status', 'Document deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Document $document = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'review', 'published', 'archived'])],
            'brief_description' => ['nullable', 'string', 'max:1200'],
            'fiscal_year' => ['nullable', 'string', 'max:20'],
            'document_date' => ['nullable', 'date'],
            'counterparty' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
            'file' => [$document ? 'nullable' : 'required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg', 'max:20480'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function attachUpload(Request $request, array &$data, string $input, string $directory, string $prefix): void
    {
        if (! $request->hasFile($input)) {
            return;
        }

        $file = $request->file($input);
        $path = $file->store($directory, 'public');

        if ($prefix === 'file') {
            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_mime_type'] = $file->getMimeType();
            $data['file_size'] = $file->getSize() ?: 0;

            return;
        }

        $data['cover_image_path'] = $path;
    }

    private function ensureValidType(string $type): void
    {
        abort_unless(array_key_exists($type, Document::TYPES), 404);
    }
}
