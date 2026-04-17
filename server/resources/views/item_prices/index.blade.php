@extends('layouts.app')

@section('title', 'Item Prices')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Item Prices</h3>
    <a href="{{ route('item_prices.create') }}" class="btn btn-primary">+ Add Item Price</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Organization</th>
                    <th>Base Unit</th>
                    <th class="text-end">Purchase Price</th>
                    <th class="text-end">Selling / Base Unit</th>
                    <th class="text-end">Selling / Box</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($itemPrices as $item)
                    <tr>
                        <td>{{ $loop->iteration + ($itemPrices->currentPage() - 1) * $itemPrices->perPage() }}</td>
                        <td>
                            <a href="{{ route('item_prices.show', $item) }}" class="text-decoration-none fw-medium">
                                {{ $item->name }}
                            </a>
                        </td>
                        <td class="text-muted">{{ $item->organization->name ?? '—' }}</td>
                        <td>{{ $item->base_unit ?: '—' }}</td>
                        <td class="text-end">{{ number_format($item->purchase_price, 2) }}</td>
                        <td class="text-end">{{ number_format($item->selling_price_base_unit, 2) }}</td>
                        <td class="text-end">{{ number_format($item->selling_price_box, 2) }}</td>
                        <td class="text-end">
                            <a href="{{ route('item_prices.show', $item) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            <a href="{{ route('item_prices.edit', $item) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="POST" action="{{ route('item_prices.destroy', $item) }}" class="d-inline"
                                  onsubmit="return confirm('Delete this item price?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No item prices found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $itemPrices->links() }}</div>
@endsection
