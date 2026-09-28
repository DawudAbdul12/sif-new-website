@csrf

@php
  $displayDate = old('display_at', optional($purchasePrice->display_at)->format('Y-m-d\TH:i'));
  $validFrom = old('valid_from', optional($purchasePrice->valid_from)->format('Y-m-d\TH:i'));
  $validUntil = old('valid_until', optional($purchasePrice->valid_until)->format('Y-m-d\TH:i'));
  $visibilityOptions = ['visible' => 'Show', 'faded' => 'Show faded', 'hidden' => 'Hide'];
  $visibility = fn (string $field, string $default = 'visible') => old($field, $purchasePrice->{$field} ?: $default);
  $oldValue = fn (string $field, mixed $default = null) => old($field, $purchasePrice->{$field} ?? $default);
  $formatRate = fn ($value) => rtrim(rtrim(number_format((float) $value, 4), '0'), '.');
  $formatMoney = fn ($value) => number_format((float) $value, 2);
  $formatLocalMoney = fn ($value) => number_format((float) $value);
@endphp

@push('styles')
  <style>
    .price-form-grid {
      align-items: start;
    }

    .price-editor-section + .price-editor-section {
      margin-top: 16px;
    }

    .price-section-title {
      align-items: center;
      color: var(--gb-green);
      display: flex;
      font-size: 1rem;
      font-weight: 800;
      gap: 8px;
      margin: 0 0 18px;
    }

    .price-section-title small {
      color: var(--gb-muted);
      font-size: .78rem;
      font-weight: 700;
      margin-left: auto;
    }

    .price-field-help {
      color: var(--gb-muted);
      font-size: .8rem;
      font-weight: 600;
      margin: 7px 0 0;
    }

    .price-side-panel {
      position: sticky;
      top: 24px;
    }

    .price-input-prefix {
      position: relative;
    }

    .price-input-prefix span {
      color: var(--gb-muted);
      font-size: .78rem;
      font-weight: 800;
      left: 12px;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 1;
    }

    .price-input-prefix .admin-control {
      padding-left: 54px;
    }

    .price-home-toggle {
      align-items: flex-start;
      background: #fbfcf8;
      border: 1px solid var(--gb-line);
      border-radius: 8px;
      display: flex;
      gap: 10px;
      padding: 14px;
    }

    .price-home-toggle input {
      margin-top: 4px;
    }

    .price-home-toggle strong {
      color: var(--gb-green);
      display: block;
      font-size: .9rem;
    }

    .price-home-toggle span {
      color: var(--gb-muted);
      display: block;
      font-size: .8rem;
      font-weight: 600;
      line-height: 1.45;
      margin-top: 3px;
    }

    .price-preview {
      background: #1f5a2c;
      border-radius: 8px;
      color: #fff;
      padding: 28px;
    }

    .price-preview-title {
      color: #F4C400;
      font-size: 1.15rem;
      font-weight: 800;
      line-height: 1.35;
      margin-bottom: 10px;
      text-align: center;
    }

    .price-preview-date {
      color: #e7c85a;
      font-weight: 800;
      margin-bottom: 22px;
      text-align: center;
    }

    .price-preview-card {
      background: #fff;
      border-radius: 8px;
      color: #243129;
      padding: 26px;
      text-align: center;
    }

    .price-preview-label {
      color: #6f756f;
      font-weight: 500;
      margin-bottom: 5px;
    }

    .price-preview-value {
      color: #1f2d25;
      font-size: 1.1rem;
      font-weight: 800;
      margin-bottom: 22px;
    }

    .price-preview-value:last-child {
      margin-bottom: 0;
    }

    .price-preview-row.faded {
      opacity: .18;
    }

    .price-preview-actions {
      display: grid;
      gap: 8px;
      margin: 0;
    }

    @media (max-width: 991.98px) {
      .price-side-panel {
        position: static;
      }
    }
  </style>
@endpush

<div class="row g-3 price-form-grid">
  <div class="col-lg-8">
    <div class="admin-panel price-editor-section">
      <h2 class="price-section-title"><i class="bi bi-card-text"></i> Price Notice <small>Public heading</small></h2>
      <div class="row g-3">
        <div class="col-12">
          <label class="admin-label" for="title">Title</label>
          <input id="title" name="title" value="{{ old('title', $purchasePrice->title) }}" class="admin-control" required>
          <p class="price-field-help">Shown at the top of the homepage price popup.</p>
          @error('title') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
          <label class="admin-label" for="subtitle">Subtitle / Validity Copy</label>
          <input id="subtitle" name="subtitle" value="{{ old('subtitle', $purchasePrice->subtitle) }}" class="admin-control" placeholder="Based on LBMA PM Price | Valid: 2:00 PM - 8:30 PM">
          <p class="price-field-help">Use this for the LBMA note and public validity window.</p>
          @error('subtitle') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>
      </div>
    </div>

    <div class="admin-panel price-editor-section">
      <h2 class="price-section-title"><i class="bi bi-graph-up-arrow"></i> Market Inputs <small>LBMA and rates</small></h2>
      <div class="row g-3">
        <div class="col-md-4">
          <label class="admin-label" for="lbma_price_session">LBMA Session</label>
          <select id="lbma_price_session" name="lbma_price_session" class="admin-select">
            @foreach(['AM', 'PM'] as $session)
              <option value="{{ $session }}" @selected(old('lbma_price_session', $purchasePrice->lbma_price_session ?: 'PM') === $session)>LBMA {{ $session }} Price</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label class="admin-label" for="lbma_pm_price">LBMA Price Per Ounce</label>
          <div class="price-input-prefix">
            <span>{{ old('price_currency', $purchasePrice->price_currency ?: 'USD') }}</span>
            <input id="lbma_pm_price" type="number" step="0.01" min="0" name="lbma_pm_price" value="{{ old('lbma_pm_price', $purchasePrice->lbma_pm_price) }}" class="admin-control" required>
          </div>
          @error('lbma_pm_price') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="admin-label" for="lbma_price_visibility">LBMA Display</label>
          <select id="lbma_price_visibility" name="lbma_price_visibility" class="admin-select">
            @foreach($visibilityOptions as $value => $label)
              <option value="{{ $value }}" @selected($visibility('lbma_price_visibility') === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label class="admin-label" for="rate_label">Primary Rate Label</label>
          <select id="rate_label" name="rate_label" class="admin-select">
            @foreach(['Exchange Rate', 'BRR for financing window'] as $label)
              <option value="{{ $label }}" @selected(old('rate_label', $purchasePrice->rate_label ?: 'Exchange Rate') === $label)>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label class="admin-label" for="exchange_rate">Primary Rate Value</label>
          <input id="exchange_rate" type="number" step="0.0001" min="0" name="exchange_rate" value="{{ old('exchange_rate', $purchasePrice->exchange_rate) }}" class="admin-control" required>
          @error('exchange_rate') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="admin-label" for="rate_visibility">Primary Rate Display</label>
          <select id="rate_visibility" name="rate_visibility" class="admin-select">
            @foreach($visibilityOptions as $value => $label)
              <option value="{{ $value }}" @selected($visibility('rate_visibility') === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-4">
          <label class="admin-label" for="secondary_rate_label">Secondary Rate Label</label>
          <input id="secondary_rate_label" name="secondary_rate_label" value="{{ old('secondary_rate_label', $purchasePrice->secondary_rate_label) }}" class="admin-control" placeholder="Exchange Rate">
        </div>

        <div class="col-md-4">
          <label class="admin-label" for="secondary_rate">Secondary Rate Value</label>
          <input id="secondary_rate" type="number" step="0.0001" min="0" name="secondary_rate" value="{{ old('secondary_rate', $purchasePrice->secondary_rate) }}" class="admin-control" placeholder="{{ old('exchange_rate', $purchasePrice->exchange_rate) }}">
        </div>

        <div class="col-md-4">
          <label class="admin-label" for="secondary_rate_visibility">Secondary Rate Display</label>
          <select id="secondary_rate_visibility" name="secondary_rate_visibility" class="admin-select">
            @foreach($visibilityOptions as $value => $label)
              <option value="{{ $value }}" @selected($visibility('secondary_rate_visibility', 'hidden') === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    <div class="admin-panel price-editor-section">
      <h2 class="price-section-title"><i class="bi bi-calculator"></i> Calculation Output <small>Popup rows</small></h2>
      <div class="row g-3">
        <div class="col-md-8">
          <label class="admin-label" for="discount_rate">Discount Rate (%)</label>
          <input id="discount_rate" type="number" step="0.01" min="0" max="100" name="discount_rate" value="{{ old('discount_rate', $purchasePrice->discount_rate) }}" class="admin-control" required>
          @error('discount_rate') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="admin-label" for="discount_rate_visibility">Discount Display</label>
          <select id="discount_rate_visibility" name="discount_rate_visibility" class="admin-select">
            @foreach($visibilityOptions as $value => $label)
              <option value="{{ $value }}" @selected($visibility('discount_rate_visibility') === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-8">
          <label class="admin-label" for="total_price_per_pound">Total Price Per Pound</label>
          <div class="price-input-prefix">
            <span>{{ old('currency', $purchasePrice->currency ?: 'GHS') }}</span>
            <input id="total_price_per_pound" type="number" step="0.01" min="0" name="total_price_per_pound" value="{{ old('total_price_per_pound', $purchasePrice->total_price_per_pound) }}" class="admin-control" required>
          </div>
          @error('total_price_per_pound') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
          <label class="admin-label" for="total_price_visibility">Total Display</label>
          <select id="total_price_visibility" name="total_price_visibility" class="admin-select">
            @foreach($visibilityOptions as $value => $label)
              <option value="{{ $value }}" @selected($visibility('total_price_visibility') === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-8">
          <label class="admin-label" for="bonus_label">Bonus Label</label>
          <input id="bonus_label" name="bonus_label" value="{{ old('bonus_label', $purchasePrice->bonus_label) }}" class="admin-control" placeholder="NB: SIF programme note for beneficiaries">
        </div>

        <div class="col-md-2">
          <label class="admin-label" for="bonus_amount">Bonus</label>
          <input id="bonus_amount" type="number" step="0.01" min="0" name="bonus_amount" value="{{ old('bonus_amount', $purchasePrice->bonus_amount) }}" class="admin-control">
        </div>

        <div class="col-md-2">
          <label class="admin-label" for="bonus_visibility">Bonus Display</label>
          <select id="bonus_visibility" name="bonus_visibility" class="admin-select">
            @foreach($visibilityOptions as $value => $label)
              <option value="{{ $value }}" @selected($visibility('bonus_visibility', 'hidden') === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-8">
          <label class="admin-label" for="alternate_total_label">Alternate Total Label</label>
          <input id="alternate_total_label" name="alternate_total_label" value="{{ old('alternate_total_label', $purchasePrice->alternate_total_label) }}" class="admin-control" placeholder="Total price per pound">
        </div>

        <div class="col-md-2">
          <label class="admin-label" for="alternate_total_amount">Alt. Total</label>
          <input id="alternate_total_amount" type="number" step="0.01" min="0" name="alternate_total_amount" value="{{ old('alternate_total_amount', $purchasePrice->alternate_total_amount) }}" class="admin-control">
        </div>

        <div class="col-md-2">
          <label class="admin-label" for="alternate_total_visibility">Alt. Display</label>
          <select id="alternate_total_visibility" name="alternate_total_visibility" class="admin-select">
            @foreach($visibilityOptions as $value => $label)
              <option value="{{ $value }}" @selected($visibility('alternate_total_visibility', 'hidden') === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    <div class="admin-panel price-editor-section">
      <h2 class="price-section-title"><i class="bi bi-currency-exchange"></i> Currency and Notes</h2>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="admin-label" for="price_currency">LBMA Currency</label>
          <input id="price_currency" name="price_currency" value="{{ old('price_currency', $purchasePrice->price_currency ?: 'USD') }}" class="admin-control" maxlength="10" required>
        </div>

        <div class="col-md-6">
          <label class="admin-label" for="currency">Local Currency</label>
          <input id="currency" name="currency" value="{{ old('currency', $purchasePrice->currency ?: 'GHS') }}" class="admin-control" maxlength="10" required>
        </div>

        <div class="col-12">
          <label class="admin-label" for="notes">Internal Notes</label>
          <textarea id="notes" name="notes" class="admin-textarea" style="min-height: 120px" placeholder="Optional calculation notes or approval trail.">{{ old('notes', $purchasePrice->notes) }}</textarea>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="price-side-panel">
    <div class="admin-panel mb-3">
      <h2 class="price-section-title"><i class="bi bi-sliders"></i> Publishing</h2>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="admin-label" for="status">Status</label>
          <select id="status" name="status" class="admin-select">
            @foreach(['draft', 'review', 'published', 'archived'] as $status)
              <option value="{{ $status }}" @selected(old('status', $purchasePrice->status ?: 'draft') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6">
          <label class="admin-label" for="sort_order">Sort Order</label>
          <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $purchasePrice->sort_order ?? 0) }}" class="admin-control">
        </div>

        <div class="col-12">
          <label class="admin-label" for="display_at">Display Date</label>
          <input id="display_at" type="datetime-local" name="display_at" value="{{ $displayDate }}" class="admin-control">
          @error('display_at') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div>

        <!-- <div class="col-md-6">
          <label class="admin-label" for="valid_from">Valid From</label>
          <input id="valid_from" type="datetime-local" name="valid_from" value="{{ $validFrom }}" class="admin-control">
        </div>

        <div class="col-md-6">
          <label class="admin-label" for="valid_until">Valid Until</label>
          <input id="valid_until" type="datetime-local" name="valid_until" value="{{ $validUntil }}" class="admin-control">
          @error('valid_until') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </div> -->

        <div class="col-12">
          <label class="price-home-toggle">
            <input type="checkbox" name="show_on_home" value="1" @checked(old('show_on_home', $purchasePrice->show_on_home))>
            <span>
              <strong>Display on Homepage</strong>
              <span>Only one purchase price can be visible on the homepage at a time.</span>
            </span>
          </label>
        </div>
      </div>
    </div>

    <div class="price-preview mb-3">
      <div class="price-preview-title" id="price-preview-title">
        {{ old('title', $purchasePrice->title ?: 'SIF Approved Purchase Price per Pound') }}
        @if(old('subtitle', $purchasePrice->subtitle))
          <br>({{ old('subtitle', $purchasePrice->subtitle) }})
        @endif
      </div>
      <div class="price-preview-date" id="price-preview-date">
        {{ $displayDate ? \Carbon\Carbon::parse($displayDate)->format('l, jS F Y @ g:i A') : 'Display date not set' }}
      </div>
      <div class="price-preview-card">
        @php
          $previewRows = [
              [
                  'key' => 'lbma',
                  'label' => 'LBMA '.old('lbma_price_session', $purchasePrice->lbma_price_session ?: 'PM').' Price (per ounce)',
                  'value' => old('price_currency', $purchasePrice->price_currency ?: 'USD').' '.$formatMoney(old('lbma_pm_price', $purchasePrice->lbma_pm_price)),
                  'visibility' => $visibility('lbma_price_visibility'),
              ],
              [
                  'key' => 'rate',
                  'label' => old('rate_label', $purchasePrice->rate_label ?: 'Exchange Rate'),
                  'value' => old('price_currency', $purchasePrice->price_currency ?: 'USD').' 1 = '.$formatRate(old('exchange_rate', $purchasePrice->exchange_rate)),
                  'visibility' => $visibility('rate_visibility'),
              ],
              [
                  'key' => 'secondary-rate',
                  'label' => old('secondary_rate_label', $purchasePrice->secondary_rate_label ?: 'Exchange Rate'),
                  'value' => old('price_currency', $purchasePrice->price_currency ?: 'USD').' 1 = '.$formatRate(old('secondary_rate', $purchasePrice->secondary_rate ?: old('exchange_rate', $purchasePrice->exchange_rate))),
                  'visibility' => $visibility('secondary_rate_visibility', 'hidden'),
              ],
              [
                  'key' => 'discount',
                  'label' => 'Discount Rate',
                  'value' => rtrim(rtrim(number_format((float) old('discount_rate', $purchasePrice->discount_rate), 2), '0'), '.').'%',
                  'visibility' => $visibility('discount_rate_visibility'),
              ],
              [
                  'key' => 'total',
                  'label' => 'Total Price Per Pound',
                  'value' => old('currency', $purchasePrice->currency ?: 'GHS').' '.$formatLocalMoney(old('total_price_per_pound', $purchasePrice->total_price_per_pound)),
                  'visibility' => $visibility('total_price_visibility'),
              ],
              [
                  'key' => 'bonus',
                  'label' => old('bonus_label', $purchasePrice->bonus_label),
                  'value' => old('currency', $purchasePrice->currency ?: 'GHS').' '.$formatLocalMoney(old('bonus_amount', $purchasePrice->bonus_amount)),
                  'visibility' => $visibility('bonus_visibility', 'hidden'),
              ],
              [
                  'key' => 'alternate-total',
                  'label' => old('alternate_total_label', $purchasePrice->alternate_total_label),
                  'value' => old('currency', $purchasePrice->currency ?: 'GHS').' '.$formatLocalMoney(old('alternate_total_amount', $purchasePrice->alternate_total_amount)),
                  'visibility' => $visibility('alternate_total_visibility', 'hidden'),
              ],
          ];
        @endphp

        @foreach($previewRows as $row)
          @continue($row['visibility'] === 'hidden' || blank($row['label']))
          <div class="price-preview-row {{ $row['visibility'] === 'faded' ? 'faded' : '' }}" data-preview-row="{{ $row['key'] }}">
            <div class="price-preview-label" data-preview-label>{{ $row['label'] }}</div>
            <div class="price-preview-value" data-preview-value>{{ $row['value'] }}</div>
          </div>
        @endforeach
      </div>
    </div>

    <div class="admin-panel">
      <div class="price-preview-actions">
        <button type="submit" class="admin-btn w-100"><i class="bi bi-check2"></i> Save Purchase Price</button>
        <a href="{{ route('admin.purchase-prices.index') }}" class="admin-btn-secondary w-100"><i class="bi bi-arrow-left"></i> Back</a>
      </div>
    </div>
    </div>
  </div>
</div>

@push('scripts')
  @once
    <script>
      const pricePreviewRows = {
        lbma: {
          label: () => `LBMA ${fieldValue('lbma_price_session', 'PM')} Price (per ounce)`,
          value: () => `${fieldValue('price_currency', 'USD')} ${formatMoney(fieldValue('lbma_pm_price', 0))}`,
          visibility: 'lbma_price_visibility',
        },
        rate: {
          label: () => fieldValue('rate_label', 'Exchange Rate'),
          value: () => `${fieldValue('price_currency', 'USD')} 1 = ${formatRate(fieldValue('exchange_rate', 0))}`,
          visibility: 'rate_visibility',
        },
        'secondary-rate': {
          label: () => fieldValue('secondary_rate_label', 'Exchange Rate'),
          value: () => `${fieldValue('price_currency', 'USD')} 1 = ${formatRate(fieldValue('secondary_rate') || fieldValue('exchange_rate', 0))}`,
          visibility: 'secondary_rate_visibility',
        },
        discount: {
          label: () => 'Discount Rate',
          value: () => `${formatPercent(fieldValue('discount_rate', 0))}%`,
          visibility: 'discount_rate_visibility',
        },
        total: {
          label: () => 'Total Price Per Pound',
          value: () => `${fieldValue('currency', 'GHS')} ${formatLocalMoney(fieldValue('total_price_per_pound', 0))}`,
          visibility: 'total_price_visibility',
        },
        bonus: {
          label: () => fieldValue('bonus_label'),
          value: () => `${fieldValue('currency', 'GHS')} ${formatLocalMoney(fieldValue('bonus_amount', 0))}`,
          visibility: 'bonus_visibility',
        },
        'alternate-total': {
          label: () => fieldValue('alternate_total_label'),
          value: () => `${fieldValue('currency', 'GHS')} ${formatLocalMoney(fieldValue('alternate_total_amount', 0))}`,
          visibility: 'alternate_total_visibility',
        },
      };

      function fieldValue(id, fallback = '') {
        return document.getElementById(id)?.value || fallback;
      }

      function formatMoney(value) {
        return Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      }

      function formatRate(value) {
        return Number(value || 0).toLocaleString(undefined, { maximumFractionDigits: 4 });
      }

      function formatLocalMoney(value) {
        return Number(value || 0).toLocaleString(undefined, { maximumFractionDigits: 0 });
      }

      function formatPercent(value) {
        return Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 2 });
      }

      function formatDisplayDate(value) {
        if (!value) return 'Display date not set';
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) return 'Display date not set';
        return date.toLocaleString(undefined, {
          weekday: 'long',
          day: 'numeric',
          month: 'long',
          year: 'numeric',
          hour: 'numeric',
          minute: '2-digit',
        });
      }

      function ensurePreviewRow(key) {
        let row = document.querySelector(`[data-preview-row="${key}"]`);
        if (row) return row;

        row = document.createElement('div');
        row.className = 'price-preview-row';
        row.dataset.previewRow = key;
        row.innerHTML = '<div class="price-preview-label" data-preview-label></div><div class="price-preview-value" data-preview-value></div>';
        document.querySelector('.price-preview-card')?.appendChild(row);
        return row;
      }

      function updatePricePreview() {
        const title = fieldValue('title', 'SIF Approved Purchase Price per Pound');
        const subtitle = fieldValue('subtitle');
        document.getElementById('price-preview-title').innerHTML = subtitle
          ? `${escapePriceHtml(title)}<br>(${escapePriceHtml(subtitle)})`
          : escapePriceHtml(title);

        document.getElementById('price-preview-date').textContent = formatDisplayDate(fieldValue('display_at'));

        document.querySelectorAll('.price-input-prefix span').forEach((prefix) => {
          const input = prefix.parentElement?.querySelector('input');
          prefix.textContent = input?.id === 'lbma_pm_price' ? fieldValue('price_currency', 'USD') : fieldValue('currency', 'GHS');
        });

        Object.entries(pricePreviewRows).forEach(([key, config]) => {
          const row = ensurePreviewRow(key);
          const label = config.label();
          const visibility = fieldValue(config.visibility, 'visible');

          row.hidden = visibility === 'hidden' || !label;
          row.classList.toggle('faded', visibility === 'faded');
          row.querySelector('[data-preview-label]').textContent = label;
          row.querySelector('[data-preview-value]').textContent = config.value();
        });
      }

      function escapePriceHtml(value) {
        return String(value || '').replace(/[&<>"']/g, (char) => ({
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#039;',
        }[char]));
      }

      [
        'title',
        'subtitle',
        'lbma_price_session',
        'lbma_pm_price',
        'lbma_price_visibility',
        'rate_label',
        'exchange_rate',
        'rate_visibility',
        'secondary_rate_label',
        'secondary_rate',
        'secondary_rate_visibility',
        'discount_rate',
        'discount_rate_visibility',
        'total_price_per_pound',
        'total_price_visibility',
        'bonus_label',
        'bonus_amount',
        'bonus_visibility',
        'alternate_total_label',
        'alternate_total_amount',
        'alternate_total_visibility',
        'price_currency',
        'currency',
        'display_at',
      ].forEach((id) => {
        document.getElementById(id)?.addEventListener('input', updatePricePreview);
        document.getElementById(id)?.addEventListener('change', updatePricePreview);
      });

      updatePricePreview();
    </script>
  @endonce
@endpush
