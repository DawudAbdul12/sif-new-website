<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\CmsPost;
use App\Models\Document;
use App\Models\Faq;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Models\Graphic;
use App\Models\ImpactMetric;
use App\Models\LicenseRegistryEntry;
use App\Models\MediaAsset;
use App\Models\Notice;
use App\Models\Person;
use App\Models\PressRelease;
use App\Models\Project;
use App\Models\PurchasePrice;
use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TrashController extends Controller
{
    private const TYPES = [
        'pages' => ['label' => 'Pages', 'model' => CmsPage::class, 'search' => ['title', 'slug']],
        'posts' => ['label' => 'Posts', 'model' => CmsPost::class, 'search' => ['title', 'slug', 'type']],
        'projects' => ['label' => 'Projects', 'model' => Project::class, 'search' => ['name', 'slug', 'full_name', 'funder']],
        'faqs' => ['label' => 'FAQs', 'model' => Faq::class, 'search' => ['question', 'answer', 'category']],
        'impact-metrics' => ['label' => 'Impact Metrics', 'model' => ImpactMetric::class, 'search' => ['label', 'note', 'value']],
        'categories' => ['label' => 'Categories', 'model' => Category::class, 'search' => ['name', 'slug', 'type']],
        'press-releases' => ['label' => 'Press Releases', 'model' => PressRelease::class, 'search' => ['title', 'slug']],
        'notices' => ['label' => 'Notices', 'model' => Notice::class, 'search' => ['title', 'slug']],
        'gallery' => ['label' => 'Gallery Albums', 'model' => GalleryAlbum::class, 'search' => ['title', 'slug']],
        'gallery-images' => ['label' => 'Gallery Images', 'model' => GalleryImage::class, 'search' => ['alt_text', 'caption', 'image_path']],
        'graphics' => ['label' => 'Graphics', 'model' => Graphic::class, 'search' => ['title', 'slug']],
        'videos' => ['label' => 'Videos', 'model' => Video::class, 'search' => ['title', 'slug']],
        'purchase-prices' => ['label' => 'Purchase Prices', 'model' => PurchasePrice::class, 'search' => ['title', 'subtitle']],
        'license-registry' => ['label' => 'License Registry', 'model' => LicenseRegistryEntry::class, 'search' => ['business_name', 'certificate_number']],
        'documents' => ['label' => 'Documents', 'model' => Document::class, 'search' => ['title', 'slug', 'type']],
        'people' => ['label' => 'People', 'model' => Person::class, 'search' => ['name', 'position', 'group']],
        'media' => ['label' => 'Media', 'model' => MediaAsset::class, 'search' => ['name', 'file_path']],
        'users' => ['label' => 'Users', 'model' => User::class, 'search' => ['name', 'email']],
        'roles' => ['label' => 'Roles', 'model' => Role::class, 'search' => ['name', 'slug']],
        'settings' => ['label' => 'Settings', 'model' => SiteSetting::class, 'search' => ['key', 'label', 'group']],
    ];

    public function index(Request $request): View
    {
        $type = $request->string('type')->toString();
        $type = array_key_exists($type, self::TYPES) ? $type : 'posts';
        $config = self::TYPES[$type];
        $modelClass = $config['model'];

        $items = $modelClass::onlyTrashed()
            ->when($request->filled('search'), function ($query) use ($request, $config): void {
                $search = '%'.$request->string('search').'%';

                $query->where(function ($query) use ($search, $config): void {
                    foreach ($config['search'] as $column) {
                        $query->orWhere($column, 'like', $search);
                    }
                });
            })
            ->latest('deleted_at')
            ->paginate(20)
            ->withQueryString();

        $counts = collect(self::TYPES)
            ->map(fn (array $typeConfig): int => $typeConfig['model']::onlyTrashed()->count())
            ->all();

        return view('admin.trash.index', [
            'items' => $items,
            'types' => self::TYPES,
            'counts' => $counts,
            'activeType' => $type,
        ]);
    }

    public function restore(string $type, int $id): RedirectResponse
    {
        $record = $this->trashedRecord($type, $id);
        $record->restore();

        return redirect()
            ->route('admin.trash.index', ['type' => $type])
            ->with('status', $this->recordLabel($record).' restored.');
    }

    public function destroy(string $type, int $id): RedirectResponse
    {
        $record = $this->trashedRecord($type, $id);
        $label = $this->recordLabel($record);

        $this->purgeFiles($record);
        $record->forceDelete();

        return redirect()
            ->route('admin.trash.index', ['type' => $type])
            ->with('status', $label.' permanently deleted.');
    }

    private function trashedRecord(string $type, int $id): Model
    {
        abort_unless(array_key_exists($type, self::TYPES), 404);

        $modelClass = self::TYPES[$type]['model'];

        return $modelClass::onlyTrashed()->findOrFail($id);
    }

    private function recordLabel(Model $record): string
    {
        foreach (['title', 'name', 'business_name', 'label', 'key', 'email', 'file_name', 'slug'] as $attribute) {
            if (filled($record->getAttribute($attribute))) {
                return (string) $record->getAttribute($attribute);
            }
        }

        return class_basename($record).' #'.$record->getKey();
    }

    private function purgeFiles(Model $record): void
    {
        $paths = match (true) {
            $record instanceof PressRelease, $record instanceof Notice => [$record->file_path],
            $record instanceof Document => [$record->file_path, $record->cover_image_path],
            $record instanceof Person => [$record->photo_path],
            $record instanceof Video => [$record->thumbnail_path],
            $record instanceof Graphic => $record->media_asset_id ? [] : [$record->image_path],
            $record instanceof GalleryImage => $record->media_asset_id ? [] : [$record->image_path],
            $record instanceof GalleryAlbum => collect([$record->cover_image_path])
                ->merge($record->images()->withTrashed()->whereNull('media_asset_id')->pluck('image_path'))
                ->all(),
            $record instanceof MediaAsset => [$record->file_path],
            default => [],
        };

        Storage::disk('public')->delete(array_values(array_filter($paths)));
    }
}
