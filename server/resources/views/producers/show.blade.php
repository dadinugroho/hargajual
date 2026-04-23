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
        <button type="button" class="btn btn-primary" onclick="ipShowAdd()">+ {{ __('producers.add_item_price') }}</button>
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

<div class="mb-3">
    <form method="GET" action="{{ route('producers.show', $producer) }}" class="d-flex align-items-center gap-3 flex-wrap">
        @if(request('sort'))  <input type="hidden" name="sort" value="{{ request('sort') }}">@endif
        @if(request('dir'))   <input type="hidden" name="dir"  value="{{ request('dir') }}">@endif
        @if($categories->isNotEmpty())
        <div class="d-flex align-items-center gap-2">
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
        </div>
        @endif
        <div class="d-flex align-items-center gap-2 ms-auto">
            <label for="per_page" class="form-label mb-0 text-nowrap small">{{ __('common.per_page') }}</label>
            <select id="per_page" name="per_page" class="form-select form-select-sm" style="width:80px"
                    onchange="this.form.submit()">
                @foreach([5, 10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ request('per_page', 25) == $size ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive" style="overflow: visible">
        <table class="table table-hover mb-0">
            @php
                $sort = request('sort', 'id');
                $dir  = request('dir', 'desc');
                $sortUrl = fn($col) => request()->fullUrlWithQuery([
                    'sort' => $col,
                    'dir'  => ($sort === $col && $dir === 'asc') ? 'desc' : 'asc',
                    'page' => null,
                ]);
                $sortIcon = fn($col) => $sort === $col ? ($dir === 'asc' ? ' ↑' : ' ↓') : '';
            @endphp
            <thead class="table-primary">
                <tr>
                    <th><a href="{{ $sortUrl('id') }}" class="text-decoration-none text-dark">#{{ $sortIcon('id') }}</a></th>
                    <th><a href="{{ $sortUrl('name') }}" class="text-decoration-none text-dark">{{ __('item_prices.name') }}{{ $sortIcon('name') }}</a></th>
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
                            ($item->disc4 ?? 0) > 0 ? $fmt($item->disc4 * 100).'%' : null,
                            ($item->disc5 ?? 0) > 0 ? $fmt($item->disc5 * 100).'%' : null,
                            ($item->disc6 ?? 0) > 0 ? $fmt($item->disc6 * 100).'%' : null,
                        ]);
                        $discStr = count($discParts) ? implode('+', $discParts) : '—';
                        $itemJson = json_encode([
                            'id'                 => $item->id,
                            'name'               => $item->name,
                            'category_id'        => $item->category_id,
                            'base_unit'          => $item->base_unit,
                            'purchase_price'     => (float) $item->purchase_price,
                            'disc1'              => (float) $item->disc1,
                            'disc2'              => (float) $item->disc2,
                            'disc3'              => (float) $item->disc3,
                            'disc4'              => (float) ($item->disc4 ?? 0),
                            'disc5'              => (float) ($item->disc5 ?? 0),
                            'disc6'              => (float) ($item->disc6 ?? 0),
                            'handling_cost'      => (float) $item->handling_cost,
                            'handling_qty'       => $item->handling_qty ?? 1,
                            'rounding_base_unit' => (float) $item->rounding_base_unit,
                            'profit_base_unit'   => (float) $item->profit_base_unit,
                        ]);
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
                                    <li>
                                        <button type="button" class="dropdown-item"
                                                data-item='{{ $itemJson }}'
                                                onclick="ipShowEdit(JSON.parse(this.dataset.item))">{{ __('common.edit') }}</button>
                                    </li>
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

<div class="mt-3 d-flex align-items-center w-100">{{ $itemPrices->links() }}</div>

{{-- Inline Item Price Form --}}
@php
    $hasFormErrors = $errors->any();
    $isEditMode    = $editingItem !== null;
    $formOpen      = $hasFormErrors || $isEditMode || $showAddForm;
    $fmtNum = fn($v) => (float)$v > 0 ? rtrim(rtrim(number_format((float)$v, 4, '.', ''), '0'), '.') : '';
    $fmtPct = fn($v) => (float)$v > 0 ? rtrim(rtrim(number_format((float)$v, 4, '.', ''), '0'), '.') : '';
@endphp

<div id="ip-form-wrapper" class="mt-4{{ $formOpen ? '' : ' d-none' }}">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
            <h6 id="ip-form-title" class="mb-0">
                {{ $isEditMode ? __('item_prices.edit_item_price') : __('item_prices.add_item_price') }}
            </h6>
            <button type="button" class="btn-close btn-sm" onclick="ipHideForm()"></button>
        </div>
        <div class="card-body p-3">
            @if($hasFormErrors)
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="ip-form" method="POST"
                  action="{{ $isEditMode ? route('item_prices.update', $editingItem) : route('item_prices.store') }}">
                @csrf
                <div id="ip-method-field">
                    @if($isEditMode)<input type="hidden" name="_method" value="PUT">@endif
                </div>
                <input type="hidden" name="producer_id" value="{{ $producer->id }}">
                <input type="hidden" name="org_id" value="{{ $producer->org_id }}">
                <input type="hidden" name="_editing_item_id" id="ip-editing-id" value="{{ $isEditMode ? $editingItem->id : '' }}">
                <input type="hidden" name="cost_price_base_unit" id="ip-hidden-cost">
                <input type="hidden" name="selling_price_base_unit" id="ip-hidden-selling">

                <div class="row g-2">
                    {{-- Row 1: Name, Category, Base Unit --}}
                    <div class="col-md-5">
                        <label for="ip-name" class="form-label small mb-1">{{ __('item_prices.name') }}</label>
                        <input type="text" id="ip-name" name="name"
                               class="form-control form-control-sm{{ $errors->has('name') ? ' is-invalid' : '' }}"
                               value="{{ old('name', $isEditMode ? $editingItem->name : '') }}" required>
                        @if($errors->has('name'))<div class="invalid-feedback">{{ $errors->first('name') }}</div>@endif
                    </div>
                    <div class="col-md-5">
                        <label for="category_id" class="form-label small mb-1">{{ __('item_prices.category') }}</label>
                        <select id="category_id" name="category_id"
                                class="form-select form-select-sm{{ $errors->has('category_id') ? ' is-invalid' : '' }}">
                            <option value="">{{ __('item_prices.category_none') }}</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $isEditMode ? $editingItem->category_id : $prefillCategoryId) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                            <option value="__new__">{{ __('item_prices.category_add_new') }}</option>
                        </select>
                        @if($errors->has('category_id'))<div class="invalid-feedback">{{ $errors->first('category_id') }}</div>@endif
                    </div>
                    <div class="col-md-2">
                        <label for="ip-base-unit" class="form-label small mb-1">{{ __('item_prices.base_unit') }}</label>
                        <input type="text" id="ip-base-unit" name="base_unit"
                               class="form-control form-control-sm{{ $errors->has('base_unit') ? ' is-invalid' : '' }}"
                               value="{{ old('base_unit', $isEditMode ? $editingItem->base_unit : '') }}">
                        @if($errors->has('base_unit'))<div class="invalid-feedback">{{ $errors->first('base_unit') }}</div>@endif
                    </div>

                    {{-- Row 2: Purchase Price + Disc 1–6 all in one line --}}
                    <div class="col-md-2">
                        <label for="ip-purchase" class="form-label small mb-1">{{ __('item_prices.purchase_price') }}</label>
                        <input type="number" id="ip-purchase" name="purchase_price" step="0.0001" min="0"
                               class="form-control form-control-sm{{ $errors->has('purchase_price') ? ' is-invalid' : '' }}"
                               value="{{ old('purchase_price', $isEditMode ? $fmtNum($editingItem->purchase_price) : '') }}">
                        @if($errors->has('purchase_price'))<div class="invalid-feedback">{{ $errors->first('purchase_price') }}</div>@endif
                    </div>
                    @foreach([1,2,3,4,5,6] as $d)
                    <div class="col">
                        <label for="ip-disc{{ $d }}" class="form-label small mb-1">{{ __('item_prices.disc_'.$d) }}</label>
                        <input type="number" id="ip-disc{{ $d }}" name="disc{{ $d }}" step="0.01" min="0" max="100"
                               class="form-control form-control-sm{{ $errors->has('disc'.$d) ? ' is-invalid' : '' }}"
                               value="{{ old('disc'.$d, $isEditMode ? $fmtPct((float)$editingItem->{'disc'.$d} * 100) : '') }}">
                        @if($errors->has('disc'.$d))<div class="invalid-feedback">{{ $errors->first('disc'.$d) }}</div>@endif
                    </div>
                    @endforeach

                    {{-- Row 3: Handling Cost, Rounding, Profit, Calculated --}}
                    <div class="col-md-3">
                        <label class="form-label small mb-1">{{ __('item_prices.handling_cost') }}</label>
                        <div class="d-flex gap-1 align-items-start">
                            <div class="grow">
                                <input type="number" id="ip-handling-cost" name="handling_cost" step="0.0001" min="0"
                                       class="form-control form-control-sm{{ $errors->has('handling_cost') ? ' is-invalid' : '' }}"
                                       value="{{ old('handling_cost', $isEditMode ? $fmtNum($editingItem->handling_cost) : '') }}">
                                @if($errors->has('handling_cost'))<div class="invalid-feedback">{{ $errors->first('handling_cost') }}</div>@endif
                            </div>
                            <span class="pt-1 small text-muted">/</span>
                            <div style="width:50px">
                                <input type="number" id="ip-handling-qty" name="handling_qty" step="1" min="1" max="9999"
                                       class="form-control form-control-sm{{ $errors->has('handling_qty') ? ' is-invalid' : '' }}"
                                       value="{{ old('handling_qty', $isEditMode ? ($editingItem->handling_qty ?? 1) : 1) }}"
                                       placeholder="1">
                                @if($errors->has('handling_qty'))<div class="invalid-feedback">{{ $errors->first('handling_qty') }}</div>@endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="ip-rounding" class="form-label small mb-1">{{ __('item_prices.rounding') }}</label>
                        <input type="number" id="ip-rounding" name="rounding_base_unit" step="0.0001" min="0"
                               class="form-control form-control-sm{{ $errors->has('rounding_base_unit') ? ' is-invalid' : '' }}"
                               value="{{ old('rounding_base_unit', $isEditMode ? $fmtNum($editingItem->rounding_base_unit) : '') }}">
                        @if($errors->has('rounding_base_unit'))<div class="invalid-feedback">{{ $errors->first('rounding_base_unit') }}</div>@endif
                    </div>
                    <div class="col-md-2">
                        <label for="ip-profit" class="form-label small mb-1">{{ __('item_prices.profit') }}</label>
                        <input type="number" id="ip-profit" name="profit_base_unit" step="0.01" min="0" max="100"
                               class="form-control form-control-sm{{ $errors->has('profit_base_unit') ? ' is-invalid' : '' }}"
                               value="{{ old('profit_base_unit', $isEditMode ? $fmtPct((float)$editingItem->profit_base_unit * 100) : '') }}">
                        @if($errors->has('profit_base_unit'))<div class="invalid-feedback">{{ $errors->first('profit_base_unit') }}</div>@endif
                    </div>
                    <div class="col-md-3 d-flex flex-column justify-content-end pb-1 gap-1">
                        <div class="small text-muted">{{ __('item_prices.cost_price') }}: <span id="ip-calc-cost" class="fw-semibold text-dark">—</span></div>
                        <div class="small">{{ __('item_prices.selling_price') }}: <span id="ip-calc-selling" class="fw-bold text-primary">—</span></div>
                    </div>

                    {{-- Actions --}}
                    <div class="col-12 d-flex gap-2 mt-1">
                        <div id="ip-btns-create" class="d-flex gap-2" style="{{ $isEditMode ? 'display:none !important' : '' }}">
                            <button type="submit" name="_after_save" value="done" class="btn btn-sm btn-primary">{{ __('item_prices.btn_add') }}</button>
                            <button type="submit" name="_after_save" value="new" class="btn btn-sm btn-outline-primary">{{ __('item_prices.btn_add_and_new') }}</button>
                        </div>
                        <div id="ip-btns-edit" class="d-flex gap-2" style="{{ $isEditMode ? '' : 'display:none !important' }}">
                            <button type="submit" class="btn btn-sm btn-primary">{{ __('common.save_changes') }}</button>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="ipHideForm()">{{ __('common.cancel') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@include('item_prices._category_modal')

@push('scripts')
<script>
(function () {
    const wrapper      = document.getElementById('ip-form-wrapper');
    const form         = document.getElementById('ip-form');
    const titleEl      = document.getElementById('ip-form-title');
    const btnsCreate   = document.getElementById('ip-btns-create');
    const btnsEdit     = document.getElementById('ip-btns-edit');
    const methodField  = document.getElementById('ip-method-field');
    const editingIdFld = document.getElementById('ip-editing-id');
    const storeUrl     = '{{ route("item_prices.store") }}';
    const updateBase   = '{{ url("item-prices") }}';
    const titleAdd     = @json(__('item_prices.add_item_price'));
    const titleEdit    = @json(__('item_prices.edit_item_price'));
    const prefillCat   = @json($prefillCategoryId);

    function ipRecalculate() {
        const purchase = parseFloat(document.getElementById('ip-purchase').value) || 0;
        let price = purchase;
        for (let n = 1; n <= 6; n++) {
            const d = parseFloat(document.getElementById('ip-disc' + n).value) || 0;
            price = price * (1 - d / 100);
        }
        const handlingRaw = parseFloat(document.getElementById('ip-handling-cost').value) || 0;
        const handlingQty = parseInt(document.getElementById('ip-handling-qty').value) || 1;
        const costPrice   = price + Math.ceil(handlingRaw / handlingQty);
        const profit      = parseFloat(document.getElementById('ip-profit').value) || 0;
        const rounding    = parseFloat(document.getElementById('ip-rounding').value) || 0;
        const rawSelling  = costPrice * (1 + profit / 100);
        const selling     = rounding > 0 ? Math.ceil(rawSelling / rounding) * rounding : rawSelling;

        const fmt = v => v.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 4 });
        document.getElementById('ip-calc-cost').textContent    = purchase > 0 ? fmt(costPrice) : '—';
        document.getElementById('ip-calc-selling').textContent = purchase > 0 ? fmt(selling)   : '—';
        document.getElementById('ip-hidden-cost').value    = purchase > 0 ? costPrice : '';
        document.getElementById('ip-hidden-selling').value = purchase > 0 ? selling   : '';
    }

    ['ip-purchase','ip-disc1','ip-disc2','ip-disc3','ip-disc4','ip-disc5','ip-disc6',
     'ip-handling-cost','ip-handling-qty','ip-profit','ip-rounding']
        .forEach(id => document.getElementById(id).addEventListener('input', ipRecalculate));

    form.addEventListener('submit', ipRecalculate);

    // Enter key navigation through form fields
    const fields = Array.from(form.querySelectorAll('input:not([type=hidden]):not([disabled]), select'));
    fields.forEach(function (field, idx) {
        field.addEventListener('keydown', function (e) {
            if (e.key !== 'Enter') return;
            if (this.id === 'category_id' && this.value === '__new__') { e.preventDefault(); return; }
            e.preventDefault();
            if (e.shiftKey) { if (idx > 0) fields[idx - 1].focus(); }
            else if (idx < fields.length - 1) { fields[idx + 1].focus(); }
            else if (btnsCreate.style.display !== 'none') { form.querySelector('#ip-btns-create button[value="new"]').focus(); }
        });
    });

    function switchMode(editMode) {
        titleEl.textContent = editMode ? titleEdit : titleAdd;
        btnsCreate.style.setProperty('display', editMode ? 'none' : 'flex', 'important');
        btnsEdit.style.setProperty('display', editMode ? 'flex' : 'none', 'important');
    }

    function openForm() {
        wrapper.classList.remove('d-none');
        wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
        setTimeout(() => document.getElementById('ip-name').focus(), 300);
    }

    const pctVal = v => v > 0 ? String(parseFloat((v * 100).toFixed(4))) : '';
    const numVal = v => v > 0 ? String(parseFloat(parseFloat(v).toFixed(4))) : '';

    window.ipShowAdd = function () {
        form.action = storeUrl;
        methodField.innerHTML = '';
        editingIdFld.value = '';
        switchMode(false);

        document.getElementById('ip-name').value = '';
        document.getElementById('category_id').value = prefillCat || '';
        document.getElementById('ip-base-unit').value = '';
        document.getElementById('ip-purchase').value = '';
        for (let n = 1; n <= 6; n++) document.getElementById('ip-disc' + n).value = '';
        document.getElementById('ip-handling-cost').value = '';
        document.getElementById('ip-handling-qty').value = 1;
        document.getElementById('ip-rounding').value = '';
        document.getElementById('ip-profit').value = '';
        ipRecalculate();
        openForm();
    };

    window.ipShowEdit = function (item) {
        form.action = updateBase + '/' + item.id;
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        editingIdFld.value = item.id;
        switchMode(true);

        document.getElementById('ip-name').value        = item.name || '';
        document.getElementById('category_id').value    = item.category_id || '';
        document.getElementById('ip-base-unit').value   = item.base_unit || '';
        document.getElementById('ip-purchase').value    = numVal(item.purchase_price);
        for (let n = 1; n <= 6; n++) {
            document.getElementById('ip-disc' + n).value = pctVal(item['disc' + n]);
        }
        document.getElementById('ip-handling-cost').value = numVal(item.handling_cost);
        document.getElementById('ip-handling-qty').value  = item.handling_qty || 1;
        document.getElementById('ip-rounding').value      = numVal(item.rounding_base_unit);
        document.getElementById('ip-profit').value        = pctVal(item.profit_base_unit);
        ipRecalculate();
        openForm();
    };

    window.ipHideForm = function () {
        wrapper.classList.add('d-none');
    };

    @if($formOpen)
    ipRecalculate();
    @endif
})();
</script>
@endpush
@endsection
