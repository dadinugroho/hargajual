@extends('layouts.app')

@section('title', 'Add Item Price')

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('item_prices.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Back</a>
    <h3 class="mb-0">Add Item Price</h3>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('item_prices.store') }}">
            @csrf

            <div class="row g-3">
                {{-- General --}}
                <div class="col-12">
                    <h6 class="text-uppercase text-muted small fw-semibold mb-1">General</h6>
                    <hr class="mt-0">
                </div>

                <div class="col-md-6">
                    <label for="org_id" class="form-label">Organization</label>
                    <select id="org_id" name="org_id" class="form-select @error('org_id') is-invalid @enderror" required>
                        <option value="">— Select —</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id }}" {{ old('org_id') == $org->id ? 'selected' : '' }}>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('org_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="base_unit" class="form-label">Base Unit <small class="text-muted">(optional)</small></label>
                    <input type="text" id="base_unit" name="base_unit" class="form-control @error('base_unit') is-invalid @enderror"
                           value="{{ old('base_unit') }}">
                    @error('base_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="qty_per_box" class="form-label">Qty per Box <small class="text-muted">(optional)</small></label>
                    <input type="number" id="qty_per_box" name="qty_per_box" step="0.0001" min="0"
                           class="form-control @error('qty_per_box') is-invalid @enderror"
                           value="{{ old('qty_per_box', 0) }}">
                    @error('qty_per_box')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="purchase_price" class="form-label">Purchase Price <small class="text-muted">(optional)</small></label>
                    <input type="number" id="purchase_price" name="purchase_price" step="0.0001" min="0"
                           class="form-control @error('purchase_price') is-invalid @enderror"
                           value="{{ old('purchase_price', 0) }}">
                    @error('purchase_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Discounts & Costs --}}
                <div class="col-12 mt-2">
                    <h6 class="text-uppercase text-muted small fw-semibold mb-1">Discounts & Costs</h6>
                    <hr class="mt-0">
                </div>

                <div class="col-md-4">
                    <label for="disc1" class="form-label">Disc 1 <small class="text-muted">(0–1, optional)</small></label>
                    <input type="number" id="disc1" name="disc1" step="0.001" min="0" max="1"
                           class="form-control @error('disc1') is-invalid @enderror"
                           value="{{ old('disc1', 0) }}">
                    @error('disc1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="disc2" class="form-label">Disc 2 <small class="text-muted">(0–1, optional)</small></label>
                    <input type="number" id="disc2" name="disc2" step="0.001" min="0" max="1"
                           class="form-control @error('disc2') is-invalid @enderror"
                           value="{{ old('disc2', 0) }}">
                    @error('disc2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="disc3" class="form-label">Disc 3 <small class="text-muted">(0–1, optional)</small></label>
                    <input type="number" id="disc3" name="disc3" step="0.001" min="0" max="1"
                           class="form-control @error('disc3') is-invalid @enderror"
                           value="{{ old('disc3', 0) }}">
                    @error('disc3')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="handling_cost" class="form-label">Handling Cost <small class="text-muted">(optional)</small></label>
                    <input type="number" id="handling_cost" name="handling_cost" step="0.0001" min="0"
                           class="form-control @error('handling_cost') is-invalid @enderror"
                           value="{{ old('handling_cost', 0) }}">
                    @error('handling_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="additional_cost_base_unit" class="form-label">Addl. Cost / Base Unit <small class="text-muted">(optional)</small></label>
                    <input type="number" id="additional_cost_base_unit" name="additional_cost_base_unit" step="0.0001" min="0"
                           class="form-control @error('additional_cost_base_unit') is-invalid @enderror"
                           value="{{ old('additional_cost_base_unit', 0) }}">
                    @error('additional_cost_base_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label for="additional_cost_box" class="form-label">Addl. Cost / Box <small class="text-muted">(optional)</small></label>
                    <input type="number" id="additional_cost_box" name="additional_cost_box" step="0.0001" min="0"
                           class="form-control @error('additional_cost_box') is-invalid @enderror"
                           value="{{ old('additional_cost_box', 0) }}">
                    @error('additional_cost_box')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Cost Price --}}
                <div class="col-12 mt-2">
                    <h6 class="text-uppercase text-muted small fw-semibold mb-1">Cost Price</h6>
                    <hr class="mt-0">
                </div>

                <div class="col-md-3">
                    <label for="cost_price_base_unit" class="form-label">Cost / Base Unit <small class="text-muted">(optional)</small></label>
                    <input type="number" id="cost_price_base_unit" name="cost_price_base_unit" step="0.0001" min="0"
                           class="form-control @error('cost_price_base_unit') is-invalid @enderror"
                           value="{{ old('cost_price_base_unit', 0) }}">
                    @error('cost_price_base_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label for="cost_price_box" class="form-label">Cost / Box <small class="text-muted">(optional)</small></label>
                    <input type="number" id="cost_price_box" name="cost_price_box" step="0.0001" min="0"
                           class="form-control @error('cost_price_box') is-invalid @enderror"
                           value="{{ old('cost_price_box', 0) }}">
                    @error('cost_price_box')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label for="rounding_base_unit" class="form-label">Rounding / Base Unit <small class="text-muted">(optional)</small></label>
                    <input type="number" id="rounding_base_unit" name="rounding_base_unit" step="0.0001" min="0"
                           class="form-control @error('rounding_base_unit') is-invalid @enderror"
                           value="{{ old('rounding_base_unit', 0) }}">
                    @error('rounding_base_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label for="rounding_box" class="form-label">Rounding / Box <small class="text-muted">(optional)</small></label>
                    <input type="number" id="rounding_box" name="rounding_box" step="0.0001" min="0"
                           class="form-control @error('rounding_box') is-invalid @enderror"
                           value="{{ old('rounding_box', 0) }}">
                    @error('rounding_box')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Selling Price --}}
                <div class="col-12 mt-2">
                    <h6 class="text-uppercase text-muted small fw-semibold mb-1">Selling Price</h6>
                    <hr class="mt-0">
                </div>

                <div class="col-md-3">
                    <label for="profit_base_unit" class="form-label">Profit / Base Unit <small class="text-muted">(0–1)</small></label>
                    <input type="number" id="profit_base_unit" name="profit_base_unit" step="0.001" min="0" max="1"
                           class="form-control @error('profit_base_unit') is-invalid @enderror"
                           value="{{ old('profit_base_unit', 0) }}">
                    @error('profit_base_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label for="profit_box" class="form-label">Profit / Box <small class="text-muted">(0–1)</small></label>
                    <input type="number" id="profit_box" name="profit_box" step="0.001" min="0" max="1"
                           class="form-control @error('profit_box') is-invalid @enderror"
                           value="{{ old('profit_box', 0) }}">
                    @error('profit_box')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label for="selling_price_base_unit" class="form-label">Selling / Base Unit <small class="text-muted">(optional)</small></label>
                    <input type="number" id="selling_price_base_unit" name="selling_price_base_unit" step="0.0001" min="0"
                           class="form-control @error('selling_price_base_unit') is-invalid @enderror"
                           value="{{ old('selling_price_base_unit', 0) }}">
                    @error('selling_price_base_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label for="selling_price_box" class="form-label">Selling / Box <small class="text-muted">(optional)</small></label>
                    <input type="number" id="selling_price_box" name="selling_price_box" step="0.0001" min="0"
                           class="form-control @error('selling_price_box') is-invalid @enderror"
                           value="{{ old('selling_price_box', 0) }}">
                    @error('selling_price_box')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-primary w-100">Create Item Price</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
