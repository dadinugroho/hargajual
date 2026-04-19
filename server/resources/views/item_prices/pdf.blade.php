<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 9pt;
        color: #111;
        padding: 10mm 12mm;
    }

    .header {
        text-align: center;
        margin-bottom: 4mm;
    }
    .header .title1 { font-size: 12pt; font-weight: bold; }
    .header .title2 { font-size: 9pt; margin-top: 1pt; }

    table {
        width: 100%;
        border-collapse: collapse;
    }


    thead tr th, tbody td, tr.category-row td {
        border: 0.5pt solid #111;
        font-size: 8.5pt;
    }

    thead tr th {
        padding: 3pt 4pt;
        text-align: left;
        font-weight: bold;
    }
    thead tr th.num { text-align: right; white-space: nowrap; }
    thead tr th.name { width: 100%; }

    tbody td {
        padding: 3pt 4pt;
    }
    tbody td.num  { text-align: right; white-space: nowrap; }
    tbody td.name { width: 100%; }

    tr.category-row td {
        font-weight: bold;
        padding: 3pt 4pt;
    }

    .footer {
        margin-top: 6mm;
        font-size: 7.5pt;
        text-align: right;
    }
</style>
</head>
<body>

<div class="header">
    @if($title1)<div class="title1">{{ $title1 }}</div>@endif
    @if($title2)<div class="title2">{{ $title2 }}</div>@endif
</div>

@php
    $fmt = fn($v) => rtrim(rtrim(number_format((float)$v, 2), '0'), '.');
    $colCount = count($cols) + 1; // +1 for # column

    // Group by category, preserving order
    $grouped = collect();
    foreach ($itemPrices as $item) {
        $key = $item->category_id ?? 0;
        if (!$grouped->has($key)) {
            $grouped->put($key, [
                'label' => $item->category?->name ?? null,
                'items' => collect(),
            ]);
        }
        $grouped[$key]['items']->push($item);
    }
    $showCategories = !$selectedCategoryId && (
        $grouped->count() > 1 || ($grouped->count() === 1 && $grouped->first()['label'] !== null)
    );
@endphp

<table>
    <thead>
        <tr>
            <th class="num">#</th>
            @if(in_array('name', $cols))<th class="name">Nama</th>@endif
            @if(in_array('base_unit', $cols))<th>Satuan</th>@endif
            @if(in_array('cost_price', $cols))<th class="num">Harga Pokok</th>@endif
            @if(in_array('discount', $cols))<th class="num">Diskon</th>@endif
            @if(in_array('profit', $cols))<th class="num">Profit</th>@endif
            @if(in_array('rounding', $cols))<th class="num">Pembulatan</th>@endif
            @if(in_array('selling_price', $cols))<th class="num">Harga Jual</th>@endif
        </tr>
    </thead>
    <tbody>
        @php $rowNum = 1; @endphp
        @forelse($grouped as $group)
            @if($showCategories)
            <tr class="category-row">
                <td></td>
                @if(in_array('name', $cols))<td class="name">{{ $group['label'] ?? '— Tanpa Kategori —' }}</td>@endif
                @if(in_array('base_unit', $cols))<td></td>@endif
                @if(in_array('cost_price', $cols))<td></td>@endif
                @if(in_array('discount', $cols))<td></td>@endif
                @if(in_array('profit', $cols))<td></td>@endif
                @if(in_array('rounding', $cols))<td></td>@endif
                @if(in_array('selling_price', $cols))<td></td>@endif
            </tr>
            @endif
            @foreach($group['items'] as $item)
            @php
                $discParts = array_filter([
                    $item->disc1 > 0 ? $fmt($item->disc1 * 100).'%' : null,
                    $item->disc2 > 0 ? $fmt($item->disc2 * 100).'%' : null,
                    $item->disc3 > 0 ? $fmt($item->disc3 * 100).'%' : null,
                ]);
                $discStr = count($discParts) ? implode('+', $discParts) : '—';
            @endphp
            <tr>
                <td class="num">{{ $rowNum++ }}</td>
                @if(in_array('name', $cols))<td class="name">{{ $item->name }}</td>@endif
                @if(in_array('base_unit', $cols))<td>{{ $item->base_unit ?: '—' }}</td>@endif
                @if(in_array('cost_price', $cols))<td class="num">{{ $fmt($item->cost_price_base_unit) }}</td>@endif
                @if(in_array('discount', $cols))<td class="num">{{ $discStr }}</td>@endif
                @if(in_array('profit', $cols))<td class="num">{{ $fmt($item->profit_base_unit * 100) }}%</td>@endif
                @if(in_array('rounding', $cols))<td class="num">{{ $fmt($item->rounding_base_unit) }}</td>@endif
                @if(in_array('selling_price', $cols))<td class="num">{{ $fmt($item->selling_price_base_unit) }}</td>@endif
            </tr>
            @endforeach
        @empty
            <tr><td colspan="{{ $colCount }}" style="text-align:center;padding:10pt">Tidak ada item.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="footer">Dicetak: {{ now()->locale('id')->translatedFormat('d F Y H:i') }}</div>

</body>
</html>
