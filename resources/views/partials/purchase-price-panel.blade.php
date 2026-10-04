@php
  $money = function ($currency, $value, $decimals = 0) {
      if ($value === null || $value === '') {
          return null;
      }

      return trim($currency.' '.number_format((float) $value, $decimals));
  };

  $rows = [
      [
          'label' => 'LBMA '.$price->lbma_price_session.' Price (per ounce)',
          'value' => $money($price->price_currency, $price->lbma_pm_price, 2),
          'visibility' => $price->lbma_price_visibility,
      ],
      [
          'label' => $price->rate_label ?: 'Exchange Rate',
          'value' => $price->exchange_rate !== null && $price->exchange_rate !== '' ? 'USD 1 = '.rtrim(rtrim(number_format((float) $price->exchange_rate, 4), '0'), '.') : null,
          'visibility' => $price->rate_visibility,
      ],
      [
          'label' => $price->secondary_rate_label,
          'value' => $price->secondary_rate !== null && $price->secondary_rate !== '' ? 'USD 1 = '.rtrim(rtrim(number_format((float) $price->secondary_rate, 4), '0'), '.') : null,
          'visibility' => $price->secondary_rate_visibility,
      ],
      [
          'label' => 'Discount Rate',
          'value' => $price->discount_rate !== null && $price->discount_rate !== '' ? number_format((float) $price->discount_rate, 2).'%' : null,
          'visibility' => $price->discount_rate_visibility,
      ],
      [
          'label' => 'Total price per pound',
          'value' => $money($price->currency, $price->total_price_per_pound),
          'visibility' => $price->total_price_visibility,
      ],
      [
          'label' => $price->bonus_label,
          'value' => $money($price->currency, $price->bonus_amount),
          'visibility' => $price->bonus_visibility,
      ],
      [
          'label' => $price->alternate_total_label,
          'value' => $money($price->currency, $price->alternate_total_amount),
          'visibility' => $price->alternate_total_visibility,
      ],
  ];
@endphp

<div class="gold-price-panel reveal">
  <div>
    <span class="eyebrow">Published Purchase Price</span>
    <h3>{{ $price->title }}</h3>
    @if($price->subtitle)<p>{{ $price->subtitle }}</p>@endif
  </div>

  <div class="gold-price-rows">
    @foreach($rows as $row)
      @continue(($row['visibility'] ?? 'visible') === 'hidden' || ! $row['label'] || ! $row['value'])
      <div class="gold-price-row {{ ($row['visibility'] ?? 'visible') === 'faded' ? 'faded' : '' }}">
        <span>{{ $row['label'] }}</span>
        <strong>{{ $row['value'] }}</strong>
      </div>
    @endforeach
  </div>
</div>
