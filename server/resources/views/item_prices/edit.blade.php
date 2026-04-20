@extends('layouts.app')

@section('title', __('item_prices.edit_item_price'))

@section('content')
@php
    $fmtNum = fn($v) => $v > 0 ? rtrim(rtrim(number_format((float)$v, 4, '.', ''), '0'), '.') : '';
    $fmtPct = fn($v) => $v > 0 ? rtrim(rtrim(number_format((float)$v, 4, '.', ''), '0'), '.') : '';
@endphp
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ $producer ? route('producers.show', $producer) : route('producers.index') }}" class="btn btn-sm btn-outline-secondary">&larr; {{ __('item_prices.back') }}</a>
    <h3 class="mb-0">{{ __('item_prices.edit_item_price') }} @if($producer)&mdash; {{ $producer->name }}@endif</h3>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form id="item-price-form" method="POST" action="{{ route('item_prices.update', $itemPrice) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="producer_id" value="{{ $itemPrice->producer_id }}">
            <input type="hidden" name="org_id" value="{{ $itemPrice->org_id }}">
            <input type="hidden" name="cost_price_base_unit" id="hidden_cost_price">
            <input type="hidden" name="selling_price_base_unit" id="hidden_selling_price">

            <div class="row g-3">
                {{-- Left: form fields --}}
                <div class="col-md-10">
                    <div class="row g-3">
                        {{-- Row 1: Name, Category, Base Unit --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label">{{ __('item_prices.name') }}</label>
                            <input type="text" id="name" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $itemPrice->name) }}" required autofocus>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="category_id" class="form-label">{{ __('item_prices.category') }}</label>
                            <select id="category_id" name="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">{{ __('item_prices.category_none') }}</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $itemPrice->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                                <option value="__new__">{{ __('item_prices.category_add_new') }}</option>
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-2">
                            <label for="base_unit" class="form-label">{{ __('item_prices.base_unit') }}</label>
                            <input type="text" id="base_unit" name="base_unit"
                                   class="form-control @error('base_unit') is-invalid @enderror"
                                   value="{{ old('base_unit', $itemPrice->base_unit) }}">
                            @error('base_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Row 2: Purchase Price, Disc 1, Disc 2, Disc 3 --}}
                        <div class="col-md-3">
                            <label for="purchase_price" class="form-label">{{ __('item_prices.purchase_price') }}</label>
                            <input type="number" id="purchase_price" name="purchase_price" step="0.0001" min="0"
                                   class="form-control @error('purchase_price') is-invalid @enderror"
                                   value="{{ old('purchase_price', $fmtNum($itemPrice->purchase_price)) }}">
                            @error('purchase_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label for="disc1" class="form-label">{{ __('item_prices.disc_1') }}</label>
                            <input type="number" id="disc1" name="disc1" step="0.01" min="0" max="100"
                                   class="form-control @error('disc1') is-invalid @enderror"
                                   value="{{ old('disc1', $fmtPct($itemPrice->disc1 * 100)) }}">
                            @error('disc1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label for="disc2" class="form-label">{{ __('item_prices.disc_2') }}</label>
                            <input type="number" id="disc2" name="disc2" step="0.01" min="0" max="100"
                                   class="form-control @error('disc2') is-invalid @enderror"
                                   value="{{ old('disc2', $fmtPct($itemPrice->disc2 * 100)) }}">
                            @error('disc2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label for="disc3" class="form-label">{{ __('item_prices.disc_3') }}</label>
                            <input type="number" id="disc3" name="disc3" step="0.01" min="0" max="100"
                                   class="form-control @error('disc3') is-invalid @enderror"
                                   value="{{ old('disc3', $fmtPct($itemPrice->disc3 * 100)) }}">
                            @error('disc3')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Row 3: Handling Cost, Rounding, Profit --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('item_prices.handling_cost') }}</label>
                            <div class="d-flex gap-2 align-items-start">
                                <div class="grow">
                                    <input type="number" id="handling_cost" name="handling_cost" step="0.0001" min="0"
                                           class="form-control @error('handling_cost') is-invalid @enderror"
                                           value="{{ old('handling_cost', $fmtNum($itemPrice->handling_cost)) }}">
                                    @error('handling_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <span class="pt-2">/</span>
                                <div style="width:70px">
                                    <input type="number" id="handling_qty" name="handling_qty" step="1" min="1" max="9999"
                                           class="form-control @error('handling_qty') is-invalid @enderror"
                                           value="{{ old('handling_qty', $itemPrice->handling_qty ?? 1) }}" placeholder="1">
                                    @error('handling_qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="rounding_base_unit" class="form-label">{{ __('item_prices.rounding') }}</label>
                            <input type="number" id="rounding_base_unit" name="rounding_base_unit" step="0.0001" min="0"
                                   class="form-control @error('rounding_base_unit') is-invalid @enderror"
                                   value="{{ old('rounding_base_unit', $fmtNum($itemPrice->rounding_base_unit)) }}">
                            @error('rounding_base_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="profit_base_unit" class="form-label">{{ __('item_prices.profit') }}</label>
                            <input type="number" id="profit_base_unit" name="profit_base_unit" step="0.01" min="0" max="100"
                                   class="form-control @error('profit_base_unit') is-invalid @enderror"
                                   value="{{ old('profit_base_unit', $fmtPct($itemPrice->profit_base_unit * 100)) }}">
                            @error('profit_base_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Actions --}}
                        <div class="col-12 mt-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('common.save_changes') }}</button>
                            <a href="{{ $producer ? route('producers.show', $producer) : route('producers.index') }}" class="btn btn-outline-secondary">{{ __('common.cancel') }}</a>
                        </div>
                    </div>
                </div>

                {{-- Right: Calculated values --}}
                <div class="col-md-2 d-flex flex-column gap-3 pt-1">
                    <div>
                        <div class="text-muted small mb-1">{{ __('item_prices.cost_price') }}</div>
                        <div id="calc_cost_price" class="fs-5 fw-semibold text-end">—</div>
                    </div>
                    <div>
                        <div class="text-muted small mb-1">{{ __('item_prices.selling_price') }}</div>
                        <div id="calc_selling_price" class="fs-4 fw-bold text-end text-primary">—</div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    const form   = document.getElementById('item-price-form');
    const fields = Array.from(form.querySelectorAll(
        'input:not([type=hidden]):not([disabled]), select, textarea'
    ));

    function recalculate() {
        const purchase = parseFloat(document.getElementById('purchase_price').value) || 0;
        const disc1    = parseFloat(document.getElementById('disc1').value) || 0;
        const disc2    = parseFloat(document.getElementById('disc2').value) || 0;
        const disc3    = parseFloat(document.getElementById('disc3').value) || 0;
        const handlingRaw = parseFloat(document.getElementById('handling_cost').value) || 0;
        const handlingQty = parseInt(document.getElementById('handling_qty').value) || 1;
        const handling    = Math.ceil(handlingRaw / handlingQty);
        const profit   = parseFloat(document.getElementById('profit_base_unit').value) || 0;
        const rounding = parseFloat(document.getElementById('rounding_base_unit').value) || 0;

        const afterDisc1 = purchase * (1 - disc1 / 100);
        const afterDisc2 = afterDisc1 * (1 - disc2 / 100);
        const afterDisc3 = afterDisc2 * (1 - disc3 / 100);
        const costPrice  = afterDisc3 + handling;

        const rawSelling   = costPrice * (1 + profit / 100);
        const sellingPrice = rounding > 0
            ? Math.ceil(rawSelling / rounding) * rounding
            : rawSelling;

        const fmt = v => v.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 4 });
        document.getElementById('calc_cost_price').textContent    = purchase > 0 ? fmt(costPrice)    : '—';
        document.getElementById('calc_selling_price').textContent = purchase > 0 ? fmt(sellingPrice) : '—';

        document.getElementById('hidden_cost_price').value    = purchase > 0 ? costPrice    : '';
        document.getElementById('hidden_selling_price').value = purchase > 0 ? sellingPrice : '';
    }

    ['purchase_price','disc1','disc2','disc3','handling_cost','handling_qty','profit_base_unit','rounding_base_unit']
        .forEach(id => document.getElementById(id).addEventListener('input', recalculate));

    document.getElementById('item-price-form').addEventListener('submit', recalculate);

    fields.forEach(function (field, idx) {
        field.addEventListener('keydown', function (e) {
            if (e.key !== 'Enter') return;
            e.preventDefault();
            if (e.shiftKey) {
                if (idx > 0) fields[idx - 1].focus();
            } else {
                if (idx < fields.length - 1) fields[idx + 1].focus();
            }
        });
    });

    recalculate();
</script>

@include('item_prices._category_modal')
@endsection
