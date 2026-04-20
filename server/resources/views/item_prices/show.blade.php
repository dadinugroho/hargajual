@extends('layouts.app')

@section('title', $itemPrice->name)

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('item_prices.index') }}" class="btn btn-sm btn-outline-secondary">&larr; {{ __('item_prices.back') }}</a>
    <h3 class="mb-0">{{ $itemPrice->name }}</h3>
    <a href="{{ route('item_prices.edit', $itemPrice) }}" class="btn btn-sm btn-outline-primary ms-auto">{{ __('common.edit') }}</a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted mb-3 small fw-semibold">{{ __('item_prices.section_general') }}</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-5">{{ __('item_prices.organization') }}</dt>
                    <dd class="col-sm-7">{{ $itemPrice->organization->name ?? '—' }}</dd>

                    <dt class="col-sm-5">{{ __('item_prices.base_unit') }}</dt>
                    <dd class="col-sm-7">{{ $itemPrice->base_unit ?: '—' }}</dd>

                    <dt class="col-sm-5">{{ __('item_prices.qty_per_box') }}</dt>
                    <dd class="col-sm-7">{{ $itemPrice->qty_per_box }}</dd>

                    <dt class="col-sm-5">{{ __('item_prices.purchase_price') }}</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->purchase_price, 4) }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted mb-3 small fw-semibold">{{ __('item_prices.section_discounts_costs') }}</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-5">{{ __('item_prices.disc_1') }}</dt>
                    <dd class="col-sm-7">{{ $itemPrice->disc1 }}</dd>

                    <dt class="col-sm-5">{{ __('item_prices.disc_2') }}</dt>
                    <dd class="col-sm-7">{{ $itemPrice->disc2 }}</dd>

                    <dt class="col-sm-5">{{ __('item_prices.disc_3') }}</dt>
                    <dd class="col-sm-7">{{ $itemPrice->disc3 }}</dd>

                    <dt class="col-sm-5">{{ __('item_prices.handling_cost') }}</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->handling_cost, 4) }}</dd>

                    <dt class="col-sm-5">{{ __('item_prices.addl_cost_base_unit') }}</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->additional_cost_base_unit, 4) }}</dd>

                    <dt class="col-sm-5">{{ __('item_prices.addl_cost_box') }}</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->additional_cost_box, 4) }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted mb-3 small fw-semibold">{{ __('item_prices.section_cost_price') }}</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-5">{{ __('item_prices.cost_base_unit') }}</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->cost_price_base_unit, 4) }}</dd>

                    <dt class="col-sm-5">{{ __('item_prices.cost_box') }}</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->cost_price_box, 4) }}</dd>

                    <dt class="col-sm-5">{{ __('item_prices.rounding_base_unit') }}</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->rounding_base_unit, 4) }}</dd>

                    <dt class="col-sm-5">{{ __('item_prices.rounding_box') }}</dt>
                    <dd class="col-sm-7">{{ number_format($itemPrice->rounding_box, 4) }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted mb-3 small fw-semibold">{{ __('item_prices.section_selling_price') }}</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-5">{{ __('item_prices.profit_base_unit') }}</dt>
                    <dd class="col-sm-7">{{ $itemPrice->profit_base_unit }}</dd>

                    <dt class="col-sm-5">{{ __('item_prices.profit_box') }}</dt>
                    <dd class="col-sm-7">{{ $itemPrice->profit_box }}</dd>

                    <dt class="col-sm-5">{{ __('item_prices.selling_base_unit') }}</dt>
                    <dd class="col-sm-7 fw-semibold">{{ number_format($itemPrice->selling_price_base_unit, 4) }}</dd>

                    <dt class="col-sm-5">{{ __('item_prices.selling_box') }}</dt>
                    <dd class="col-sm-7 fw-semibold">{{ number_format($itemPrice->selling_price_box, 4) }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
