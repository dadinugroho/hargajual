@extends('layouts.app')

@section('title', __('item_prices.add_item_price'))

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('producers.show', $producer) }}" class="btn btn-sm btn-outline-secondary">&larr; {{ __('item_prices.back') }}</a>
    <h3 class="mb-0">{{ __('item_prices.add_item_price') }} &mdash; {{ $producer->name }}</h3>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form id="item-price-form" method="POST" action="{{ route('item_prices.store') }}">
            @csrf
            <input type="hidden" name="producer_id" value="{{ $producer->id }}">
            <input type="hidden" name="org_id" value="{{ $producer->org_id }}">
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
                                   value="{{ old('name') }}" required autofocus>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="category_id" class="form-label">{{ __('item_prices.category') }}</label>
                            <select id="category_id" name="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">{{ __('item_prices.category_none') }}</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                   value="{{ old('base_unit') }}">
                            @error('base_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Row 2: Purchase Price + Disc 1–6 all in one line --}}
                        <div class="col-md-2">
                            <label for="purchase_price" class="form-label">{{ __('item_prices.purchase_price') }}</label>
                            <input type="number" id="purchase_price" name="purchase_price" step="0.0001" min="0"
                                   class="form-control @error('purchase_price') is-invalid @enderror"
                                   value="{{ old('purchase_price') }}">
                            @error('purchase_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @foreach([1,2,3,4,5,6] as $d)
                        <div class="col">
                            <label for="disc{{ $d }}" class="form-label">{{ __('item_prices.disc_'.$d) }}</label>
                            <input type="number" id="disc{{ $d }}" name="disc{{ $d }}" step="0.01" min="0" max="100"
                                   class="form-control{{ $errors->has('disc'.$d) ? ' is-invalid' : '' }}"
                                   value="{{ old('disc'.$d) }}">
                            @if($errors->has('disc'.$d))<div class="invalid-feedback">{{ $errors->first('disc'.$d) }}</div>@endif
                        </div>
                        @endforeach

                        {{-- Row 3: Handling Cost, Rounding, Profit --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('item_prices.handling_cost') }}</label>
                            <div class="d-flex gap-2 align-items-start">
                                <div class="grow">
                                    <input type="number" id="handling_cost" name="handling_cost" step="0.0001" min="0"
                                           class="form-control @error('handling_cost') is-invalid @enderror"
                                           value="{{ old('handling_cost') }}">
                                    @error('handling_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <span class="pt-2">/</span>
                                <div style="width:70px">
                                    <input type="number" id="handling_qty" name="handling_qty" step="1" min="1" max="9999"
                                           class="form-control @error('handling_qty') is-invalid @enderror"
                                           value="{{ old('handling_qty', 1) }}" placeholder="1">
                                    @error('handling_qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="rounding_base_unit" class="form-label">{{ __('item_prices.rounding') }}</label>
                            <input type="number" id="rounding_base_unit" name="rounding_base_unit" step="0.0001" min="0"
                                   class="form-control @error('rounding_base_unit') is-invalid @enderror"
                                   value="{{ old('rounding_base_unit') }}">
                            @error('rounding_base_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="profit_base_unit" class="form-label">{{ __('item_prices.profit') }}</label>
                            <input type="number" id="profit_base_unit" name="profit_base_unit" step="0.01" min="0" max="100"
                                   class="form-control @error('profit_base_unit') is-invalid @enderror"
                                   value="{{ old('profit_base_unit') }}">
                            @error('profit_base_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Actions --}}
                        <div class="col-12 mt-2 d-flex gap-2">
                            <button type="submit" name="_after_save" value="done" class="btn btn-primary">
                                {{ __('item_prices.btn_add') }}
                            </button>
                            <button id="btn-add-new" type="submit" name="_after_save" value="new"
                                    class="btn btn-outline-primary">
                                {{ __('item_prices.btn_add_and_new') }}
                            </button>
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
    const btnNew = document.getElementById('btn-add-new');
    const fields = Array.from(form.querySelectorAll(
        'input:not([type=hidden]):not([disabled]), select, textarea'
    ));

    function recalculate() {
        const purchase = parseFloat(document.getElementById('purchase_price').value) || 0;
        let price = purchase;
        for (let n = 1; n <= 6; n++) {
            const d = parseFloat(document.getElementById('disc' + n).value) || 0;
            price = price * (1 - d / 100);
        }
        const handlingRaw = parseFloat(document.getElementById('handling_cost').value) || 0;
        const handlingQty = parseInt(document.getElementById('handling_qty').value) || 1;
        const handling    = Math.ceil(handlingRaw / handlingQty);
        const profit      = parseFloat(document.getElementById('profit_base_unit').value) || 0;
        const rounding    = parseFloat(document.getElementById('rounding_base_unit').value) || 0;
        const costPrice   = price + handling;
        const rawSelling  = costPrice * (1 + profit / 100);
        const sellingPrice = rounding > 0 ? Math.ceil(rawSelling / rounding) * rounding : rawSelling;

        const fmt = v => v.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 4 });
        document.getElementById('calc_cost_price').textContent    = purchase > 0 ? fmt(costPrice)    : '—';
        document.getElementById('calc_selling_price').textContent = purchase > 0 ? fmt(sellingPrice) : '—';
        document.getElementById('hidden_cost_price').value    = purchase > 0 ? costPrice    : '';
        document.getElementById('hidden_selling_price').value = purchase > 0 ? sellingPrice : '';
    }

    ['purchase_price','disc1','disc2','disc3','disc4','disc5','disc6',
     'handling_cost','handling_qty','profit_base_unit','rounding_base_unit']
        .forEach(id => document.getElementById(id).addEventListener('input', recalculate));

    document.getElementById('item-price-form').addEventListener('submit', recalculate);

    document.getElementById('category_id').addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && this.value === '__new__') e.preventDefault();
    });

    fields.forEach(function (field, idx) {
        field.addEventListener('keydown', function (e) {
            if (e.key !== 'Enter') return;
            e.preventDefault();
            if (e.shiftKey) {
                if (idx > 0) fields[idx - 1].focus();
            } else if (idx === fields.length - 1) {
                btnNew.focus();
            } else {
                fields[idx + 1].focus();
            }
        });
    });
</script>

@include('item_prices._category_modal')
@endsection
