<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Support\UniqueSlug;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PersonController extends Controller
{
    public function index(Request $request, string $group): View
    {
        $this->ensureValidGroup($group);

        $people = Person::query()
            ->forGroup($group)
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.people.index', [
            'people' => $people,
            'group' => $group,
            'groupLabel' => Person::GROUPS[$group],
        ]);
    }

    public function create(string $group): View
    {
        $this->ensureValidGroup($group);

        return view('admin.people.create', [
            'person' => new Person(['group' => $group, 'show_seal' => true]),
            'group' => $group,
            'groupLabel' => Person::GROUPS[$group],
        ]);
    }

    public function store(Request $request, string $group): RedirectResponse
    {
        $this->ensureValidGroup($group);

        $data = $this->validated($request);
        $data['group'] = $group;
        $data['slug'] = UniqueSlug::make('people', $data['slug'] ?: $data['name']);
        $data['show_seal'] = $request->boolean('show_seal');
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? now()) : null;
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $this->attachPhoto($request, $data);

        $person = Person::create($data);

        return redirect()->route('admin.people.edit', [$group, $person])->with('status', 'Profile created.');
    }

    public function edit(string $group, Person $person): View
    {
        $this->ensureValidGroup($group);
        abort_unless($person->group === $group, 404);

        return view('admin.people.edit', [
            'person' => $person,
            'group' => $group,
            'groupLabel' => Person::GROUPS[$group],
        ]);
    }

    public function update(Request $request, string $group, Person $person): RedirectResponse
    {
        $this->ensureValidGroup($group);
        abort_unless($person->group === $group, 404);

        $data = $this->validated($request, $person);
        $data['slug'] = UniqueSlug::make('people', $data['slug'] ?: $data['name'], $person);
        $data['show_seal'] = $request->boolean('show_seal');
        $data['published_at'] = $data['status'] === 'published' ? ($data['published_at'] ?? $person->published_at ?? now()) : null;
        $data['updated_by'] = $request->user()->id;

        if ($request->hasFile('photo')) {
            Storage::disk('public')->delete($person->photo_path);
            $this->attachPhoto($request, $data);
        }

        $person->update($data);

        return redirect()->route('admin.people.edit', [$group, $person])->with('status', 'Profile updated.');
    }

    public function destroy(string $group, Person $person): RedirectResponse
    {
        $this->ensureValidGroup($group);
        abort_unless($person->group === $group, 404);

        $person->delete();

        return redirect()->route('admin.people.index', $group)->with('status', 'Profile deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Person $person = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'appointment_type' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'review', 'published', 'archived'])],
            'brief_profile' => ['nullable', 'string', 'max:1200'],
            'bio' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:80'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'show_seal' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function attachPhoto(Request $request, array &$data): void
    {
        if (! $request->hasFile('photo')) {
            return;
        }

        $data['photo_path'] = $request->file('photo')->store('people', 'public');
    }

    private function ensureValidGroup(string $group): void
    {
        abort_unless(array_key_exists($group, Person::GROUPS), 404);
    }
}
