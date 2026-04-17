<?php

namespace App\Http\Controllers;

use App\Models\ItemPrice;
use App\Models\Organization;
use Illuminate\Http\Request;

class ItemPriceController extends Controller
{
    public function index()
    {
        $itemPrices = ItemPrice::with('organization')->orderBy('name')->paginate(15);
        return view('item_prices.index', compact('itemPrices'));
    }

    public function create()
    {
        $organizations = Organization::orderBy('name')->get();
        return view('item_prices.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $data = $this->validateItemPrice($request);

        ItemPrice::create($data);

        return redirect()->route('item_prices.index')->with('success', 'Item price created.');
    }

    public function show(ItemPrice $itemPrice)
    {
        $itemPrice->load('organization');
        return view('item_prices.show', compact('itemPrice'));
    }

    public function edit(ItemPrice $itemPrice)
    {
        $organizations = Organization::orderBy('name')->get();
        return view('item_prices.edit', compact('itemPrice', 'organizations'));
    }

    public function update(Request $request, ItemPrice $itemPrice)
    {
        $data = $this->validateItemPrice($request);

        $itemPrice->update($data);

        return redirect()->route('item_prices.show', $itemPrice)->with('success', 'Item price updated.');
    }

    public function destroy(ItemPrice $itemPrice)
    {
        $itemPrice->delete();
        return redirect()->route('item_prices.index')->with('success', 'Item price deleted.');
    }

    private function validateItemPrice(Request $request): array
    {
        return $request->validate([
            'org_id'                    => ['required', 'exists:organizations,id'],
            'name'                      => ['required', 'string', 'max:255'],
            'base_unit'                 => ['nullable', 'string', 'max:50'],
            'qty_per_box'               => ['nullable', 'numeric', 'min:0'],
            'purchase_price'            => ['nullable', 'numeric', 'min:0'],
            'disc1'                     => ['nullable', 'numeric', 'min:0', 'max:1'],
            'disc2'                     => ['nullable', 'numeric', 'min:0', 'max:1'],
            'disc3'                     => ['nullable', 'numeric', 'min:0', 'max:1'],
            'handling_cost'             => ['nullable', 'numeric', 'min:0'],
            'additional_cost_base_unit' => ['nullable', 'numeric', 'min:0'],
            'additional_cost_box'       => ['nullable', 'numeric', 'min:0'],
            'cost_price_base_unit'      => ['nullable', 'numeric', 'min:0'],
            'cost_price_box'            => ['nullable', 'numeric', 'min:0'],
            'rounding_base_unit'        => ['nullable', 'numeric', 'min:0'],
            'rounding_box'              => ['nullable', 'numeric', 'min:0'],
            'profit_base_unit'          => ['nullable', 'numeric', 'min:0', 'max:1'],
            'profit_box'                => ['nullable', 'numeric', 'min:0', 'max:1'],
            'selling_price_base_unit'   => ['nullable', 'numeric', 'min:0'],
            'selling_price_box'         => ['nullable', 'numeric', 'min:0'],
        ]);
    }
}
