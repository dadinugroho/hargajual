@extends('layouts.app')

@section('title', $itemPrice->name)

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('item_prices.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Back</a>
    <h3 class="mb-0">{{ $itemPrice->name }}</h3>
    <a href="{{ route('item_prices.edit', $itemPrice) }}" class="btn btn-sm btn-outline-primary ms-auto">Edit</a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted mb-3 small fw-semibold">General</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-5">Organization</dt>
                    <dd class="col-sm-7">{{ $itemPrice->organization->name ?? '—' }}</dd>

                    <dt class="col-sm-5">Base Unit</dt>
                    <dd class="col-sm-7">{{ $itemPrice->base_unit ?: '—' }}</dd>

                    <dt class="col-sm-5">Qty per Box</dt>
                    <dd class="col-sm-7">{{ $itemPrice->qty_per_box }}</dd>

                    <dt class="col-sm-5">Purchase Price</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->purchase_price, 4) }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted mb-3 small fw-semibold">Discounts & Costs</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-5">Disc 1</dt>
                    <dd class="col-sm-7">{{ $itemPrice->disc1 }}</dd>

                    <dt class="col-sm-5">Disc 2</dt>
                    <dd class="col-sm-7">{{ $itemPrice->disc2 }}</dd>

                    <dt class="col-sm-5">Disc 3</dt>
                    <dd class="col-sm-7">{{ $itemPrice->disc3 }}</dd>

                    <dt class="col-sm-5">Handling Cost</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->handling_cost, 4) }}</dd>

                    <dt class="col-sm-5">Addl. Cost / Base Unit</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->additional_cost_base_unit, 4) }}</dd>

                    <dt class="col-sm-5">Addl. Cost / Box</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->additional_cost_box, 4) }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted mb-3 small fw-semibold">Cost Price</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-5">Cost / Base Unit</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->cost_price_base_unit, 4) }}</dd>

                    <dt class="col-sm-5">Cost / Box</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->cost_price_box, 4) }}</dd>

                    <dt class="col-sm-5">Rounding / Base Unit</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->rounding_base_unit, 4) }}</dd>

                    <dt class="col-sm-5">Rounding / Box</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->rounding_box, 4) }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted mb-3 small fw-semibold">Selling Price</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-5">Profit / Base Unit</dt>
                    <dd class="col-sm-7">{{ $itemPrice->profit_base_unit }}</dd>

                    <dt class="col-sm-5">Profit / Box</dt>
                    <dd class="col-sm-7">{{ $itemPrice->profit_box }}</dd>

                    <dt class="col-sm-5">Selling / Base Unit</dt>
                    <dd class="col-sm-7 fw-semibold">{{ number_format($itemPrice->selling_price_base_unit, 4) }}</dd>

                    <dt class="col-sm-5">Selling / Box</dt>
                    <dd class="col-sm-7 fw-semibold">{{ number_format($itemPrice->selling_price_box, 4) }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
