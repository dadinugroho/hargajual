@extends('layouts.app')

@section('title', $producer->name . ' — ' . __('item_prices.title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('producers.index') }}" class="btn btn-sm btn-outline-secondary">&larr; {{ __('producers.back') }}</a>
        <h3 class="mb-0">{{ $producer->name }}</h3>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#pdfModal">
            {{ __('producers.print_pdf') }}
        </button>
        <a href="{{ route('item_prices.create') }}" class="btn btn-primary">+ {{ __('producers.add_item_price') }}</a>
    </div>
</div>

{{-- PDF Options Modal --}}
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="pdfForm" method="GET" action="{{ route('producers.pdf', $producer) }}" target="_blank">
                @if($selectedCategoryId)
                <input type="hidden" name="category_id" value="{{ $selectedCategoryId }}">
                @endif
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfModalLabel">{{ __('producers.print_price_list') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @php
                        $defaultTitle2 = 'Per 1 ' . now()->locale('id')->translatedFormat('F Y');
                    @endphp
                    <div class="mb-3">
                        <label for="pdf_title1" class="form-label fw-semibold">{{ __('producers.pdf_title1') }}</label>
                        <input type="text" class="form-control" id="pdf_title1" name="title1" value="Daftar Harga {{ $producer->name }}">
                    </div>
                    <div class="mb-4">
                        <label for="pdf_title2" class="form-label fw-semibold">{{ __('producers.pdf_title2') }}</label>
                        <input type="text" class="form-control" id="pdf_title2" name="title2" value="{{ $defaultTitle2 }}">
                    </div>
                    <div class="mb-2 fw-semibold">{{ __('producers.pdf_columns') }}</div>
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="cols[]" value="name"
                                   id="col_name" checked disabled>
                            <input type="hidden" name="cols[]" value="name">
                            <label class="form-check-label" for="col_name">{{ __('item_prices.name') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="cols[]" value="base_unit" id="col_base_unit">
                            <label class="form-check-label" for="col_base_unit">{{ __('item_prices.base_unit') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="cols[]" value="cost_price" id="col_cost_price">
                            <label class="form-check-label" for="col_cost_price">{{ __('item_prices.cost_price') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="cols[]" value="discount" id="col_discount">
                            <label class="form-check-label" for="col_discount">{{ __('item_prices.disc') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="cols[]" value="profit" id="col_profit">
                            <label class="form-check-label" for="col_profit">{{ __('item_prices.profit') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="cols[]" value="rounding" id="col_rounding">
                            <label class="form-check-label" for="col_rounding">{{ __('item_prices.rounding') }}</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="cols[]" value="selling_price" id="col_selling_price" checked>
                            <label class="form-check-label" for="col_selling_price">{{ __('item_prices.selling_price') }}</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('producers.btn_generate_pdf') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($categories->isNotEmpty())
<div class="mb-3">
    <form method="GET" action="{{ route('producers.show', $producer) }}" class="d-flex align-items-center gap-2">
        <label for="category_filter" class="form-label mb-0 fw-semibold text-nowrap">{{ __('common.category_filter') }}</label>
        <select id="category_filter" name="category_id" class="form-select form-select-sm" style="max-width:220px"
                onchange="this.form.submit()">
            <option value="">{{ __('common.filter_all') }}</option>
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
                    <th>{{ __('item_prices.name') }}</th>
                    <th class="text-end">{{ __('item_prices.purchase_price') }}</th>
                    <th class="text-end">{{ __('item_prices.disc') }}</th>
                    <th class="text-end">{{ __('item_prices.handling_cost') }}</th>
                    <th class="text-end">{{ __('item_prices.cost_price') }}</th>
                    <th class="text-end">{{ __('item_prices.rounding') }}</th>
                    <th class="text-end">{{ __('item_prices.profit') }}</th>
                    <th class="text-end">{{ __('item_prices.selling_price') }}</th>
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
                                    <li><a class="dropdown-item" href="{{ route('item_prices.edit', $item) }}">{{ __('common.edit') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('item_prices.destroy', $item) }}"
                                              onsubmit="return confirm('{{ __('producers.delete_item_price_confirm') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">{{ __('common.delete') }}</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">{{ __('producers.no_item_prices') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $itemPrices->links() }}</div>
@endsection
