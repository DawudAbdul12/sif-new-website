<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchasePrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PurchasePriceController extends Controller
{
    public function index(Request $request): View
    {
        $purchasePrices = PurchasePrice::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->orderByDesc('show_on_home')
            ->latest('display_at')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.purchase-prices.index', compact('purchasePrices'));
    }

    public function create(): View
    {
        return view('admin.purchase-prices.create', [
            'purchasePrice' => new PurchasePrice([
                'title' => 'SIF Approved Purchase Price per Pound',
                'subtitle' => 'Based on LBMA PM Price | Valid: 2:00 PM - 8:30 PM',
                'status' => 'draft',
                'lbma_price_session' => 'PM',
                'lbma_price_visibility' => 'visible',
                'rate_label' => 'Exchange Rate',
                'rate_visibility' => 'visible',
                'secondary_rate_label' => 'BRR for financing window',
                'secondary_rate_visibility' => 'hidden',
                'discount_rate_visibility' => 'visible',
                'total_price_visibility' => 'visible',
                'bonus_label' => "NB: SIF programme note for beneficiaries",
                'bonus_visibility' => 'hidden',
                'alternate_total_label' => 'Total price per pound',
                'alternate_total_visibility' => 'hidden',
                'price_currency' => 'USD',
                'currency' => 'GHS',
                'discount_rate' => 0,
                'display_at' => now(),
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['show_on_home'] = $request->boolean('show_on_home');
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $purchasePrice = PurchasePrice::create($data);

        $this->ensureSingleHomepagePrice($purchasePrice);

        return redirect()->route('admin.purchase-prices.edit', $purchasePrice)->with('status', 'Purchase price created.');
    }

    public function edit(PurchasePrice $purchasePrice): View
    {
        return view('admin.purchase-prices.edit', compact('purchasePrice'));
    }

    public function update(Request $request, PurchasePrice $purchasePrice): RedirectResponse
    {
        $data = $this->validated($request);
        $data['show_on_home'] = $request->boolean('show_on_home');
        $data['updated_by'] = $request->user()->id;

        $purchasePrice->update($data);

        $this->ensureSingleHomepagePrice($purchasePrice);

        return redirect()->route('admin.purchase-prices.edit', $purchasePrice)->with('status', 'Purchase price updated.');
    }

    public function destroy(PurchasePrice $purchasePrice): RedirectResponse
    {
        $purchasePrice->delete();

        return redirect()->route('admin.purchase-prices.index')->with('status', 'Purchase price deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'review', 'published', 'archived'])],
            'lbma_price_session' => ['required', Rule::in(['AM', 'PM'])],
            'lbma_pm_price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'lbma_price_visibility' => ['required', Rule::in(['visible', 'faded', 'hidden'])],
            'rate_label' => ['required', 'string', 'max:255'],
            'exchange_rate' => ['required', 'numeric', 'min:0', 'max:99999999.9999'],
            'rate_visibility' => ['required', Rule::in(['visible', 'faded', 'hidden'])],
            'secondary_rate_label' => ['nullable', 'string', 'max:255'],
            'secondary_rate' => ['nullable', 'numeric', 'min:0', 'max:99999999.9999'],
            'secondary_rate_visibility' => ['required', Rule::in(['visible', 'faded', 'hidden'])],
            'discount_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'discount_rate_visibility' => ['required', Rule::in(['visible', 'faded', 'hidden'])],
            'total_price_per_pound' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'total_price_visibility' => ['required', Rule::in(['visible', 'faded', 'hidden'])],
            'bonus_label' => ['nullable', 'string', 'max:255'],
            'bonus_amount' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'bonus_visibility' => ['required', Rule::in(['visible', 'faded', 'hidden'])],
            'alternate_total_label' => ['nullable', 'string', 'max:255'],
            'alternate_total_amount' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'alternate_total_visibility' => ['required', Rule::in(['visible', 'faded', 'hidden'])],
            'price_currency' => ['required', 'string', 'max:10'],
            'currency' => ['required', 'string', 'max:10'],
            'display_at' => ['nullable', 'date'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'show_on_home' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function ensureSingleHomepagePrice(PurchasePrice $purchasePrice): void
    {
        if (! $purchasePrice->show_on_home) {
            return;
        }

        PurchasePrice::whereKeyNot($purchasePrice->id)->update(['show_on_home' => false]);
    }
}
