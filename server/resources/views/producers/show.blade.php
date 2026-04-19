@extends('layouts.app')

@section('title', $producer->name . ' — Item Prices')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('producers.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Producers</a>
        <h3 class="mb-0">{{ $producer->name }}</h3>
    </div>
    <a href="{{ route('item_prices.create') }}" class="btn btn-primary">+ Add Item Price</a>
</div>

@if($categories->isNotEmpty())
<div class="mb-3">
    <form method="GET" action="{{ route('producers.show', $producer) }}" class="d-flex align-items-center gap-2">
        <label for="category_filter" class="form-label mb-0 fw-semibold text-nowrap">Category:</label>
        <select id="category_filter" name="category_id" class="form-select form-select-sm" style="max-width:220px"
                onchange="this.form.submit()">
            <option value="">— All —</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $selectedCategoryId == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </form>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="table-responsive" style="overflow: visible">
        <table class="table table-hover mb-0">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th class="text-end">Purchase Price</th>
                    <th class="text-end">Disc</th>
                    <th class="text-end">Handling Cost</th>
                    <th class="text-end">Cost Price</th>
                    <th class="text-end">Rounding</th>
                    <th class="text-end">Profit</th>
                    <th class="text-end">Selling Price</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($itemPrices as $item)
                    @php
                        $fmt = fn($v) => rtrim(rtrim(number_format((float)$v, 2), '0'), '.');
                        $discParts = array_filter([
                            $item->disc1 > 0 ? $fmt($item->disc1 * 100).'%' : null,
                            $item->disc2 > 0 ? $fmt($item->disc2 * 100).'%' : null,
                            $item->disc3 > 0 ? $fmt($item->disc3 * 100).'%' : null,
                        ]);
                        $discStr = count($discParts) ? implode('+', $discParts) : '—';
                    @endphp
                    <tr class="align-middle">
                        <td>{{ $loop->iteration + ($itemPrices->currentPage() - 1) * $itemPrices->perPage() }}</td>
                        <td>
                            <a href="{{ route('item_prices.show', $item) }}" class="text-decoration-none fw-medium">
                                {{ $item->name }}
                            </a>
                        </td>
                        <td class="text-end">{{ $fmt($item->purchase_price) }}</td>
                        <td class="text-end">{{ $discStr }}</td>
                        <td class="text-end">{{ $fmt(ceil($item->handling_cost / max(1, $item->handling_qty))) }}</td>
                        <td class="text-end">{{ $fmt($item->cost_price_base_unit) }}</td>
                        <td class="text-end">{{ $fmt($item->rounding_base_unit) }}</td>
                        <td class="text-end">{{ $fmt($item->profit_base_unit * 100) }}%</td>
                        <td class="text-end">{{ $fmt($item->selling_price_base_unit) }}</td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary px-2" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">&#8942;</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('item_prices.edit', $item) }}">Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('item_prices.destroy', $item) }}"
                                              onsubmit="return confirm('Delete this item price?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">No item prices yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $itemPrices->links() }}</div>
@endsection
