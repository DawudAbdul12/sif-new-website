<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LicenseRegistryEntry;
use App\Services\LicenseRegistryImporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LicenseRegistryController extends Controller
{
    public function index(Request $request): View
    {
        $data = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', Rule::in(array_keys(LicenseRegistryEntry::CATEGORIES))],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'per_page' => ['nullable', 'integer', 'in:25,50,100,200'],
        ]);

        $perPage = (int) ($data['per_page'] ?? 50);

        $entries = LicenseRegistryEntry::query()
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search');

                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('business_name', 'like', '%'.$search.'%')
                        ->orWhere('certificate_number', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('category')
            ->orderBy('registry_number')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.license-registry.index', [
            'entries' => $entries,
            'categories' => LicenseRegistryEntry::CATEGORIES,
            'perPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        return view('admin.license-registry.create', [
            'entry' => new LicenseRegistryEntry(['status' => 'active']),
            'categories' => LicenseRegistryEntry::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $entry = LicenseRegistryEntry::create($data);

        return redirect()->route('admin.license-registry.edit', $entry)->with('status', 'License registry entry created.');
    }

    public function edit(LicenseRegistryEntry $licenseRegistry): View
    {
        return view('admin.license-registry.edit', [
            'entry' => $licenseRegistry,
            'categories' => LicenseRegistryEntry::CATEGORIES,
        ]);
    }

    public function update(Request $request, LicenseRegistryEntry $licenseRegistry): RedirectResponse
    {
        $data = $this->validated($request, $licenseRegistry);
        $data['updated_by'] = $request->user()->id;

        $licenseRegistry->update($data);

        return redirect()->route('admin.license-registry.edit', $licenseRegistry)->with('status', 'License registry entry updated.');
    }

    public function destroy(LicenseRegistryEntry $licenseRegistry): RedirectResponse
    {
        $licenseRegistry->delete();

        return redirect()->route('admin.license-registry.index')->with('status', 'License registry entry deleted.');
    }

    public function import(Request $request, LicenseRegistryImporter $importer): RedirectResponse
    {
        $data = $request->validate([
            'category' => ['required', Rule::in(array_keys(LicenseRegistryEntry::CATEGORIES))],
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:51200'],
        ]);

        $summary = $importer->import($data['file'], $data['category'], $request->user()->id);
        $message = "Import complete. Created {$summary['created']}, updated {$summary['updated']}, skipped {$summary['skipped']}.";

        return redirect()
            ->route('admin.license-registry.index', ['category' => $data['category']])
            ->with('status', $message)
            ->with('import_errors', array_slice($summary['errors'], 0, 12));
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?LicenseRegistryEntry $entry = null): array
    {
        return $request->validate([
            'category' => ['required', Rule::in(array_keys(LicenseRegistryEntry::CATEGORIES))],
            'registry_number' => ['nullable', 'integer', 'min:1'],
            'business_name' => ['required', 'string', 'max:255'],
            'certificate_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('license_registry_entries', 'certificate_number')
                    ->where(fn ($query) => $query->where('category', $request->input('category')))
                    ->ignore($entry),
            ],
            'issued_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date', 'after_or_equal:issued_date'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
