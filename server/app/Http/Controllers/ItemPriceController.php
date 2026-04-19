<?php

namespace App\Http\Controllers;

use App\Models\ItemPrice;
use App\Models\ItemPriceCategory;
use App\Models\Producer;
use Illuminate\Http\Request;

class ItemPriceController extends Controller
{
    public function create()
    {
        $producerId = session('selected_producer_id');

        if (!$producerId) {
            return redirect()->route('producers.index')
                ->with('error', 'Please select a producer first.');
        }

        $producer = Producer::findOrFail($producerId);
        $categories = ItemPriceCategory::where('org_id', $producer->org_id)->where('status', 'active')->orderBy('name')->get();

        return view('item_prices.create', compact('producer', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateItemPrice($request);
        $data = $this->percentToDecimal($data);

        $producer = Producer::findOrFail($data['producer_id']);
        $data['org_id'] = $producer->org_id;

        ItemPrice::create($data);

        if ($request->input('_after_save') === 'new') {
            return redirect()->route('item_prices.create')->with('success', 'Item price added. Add another.');
        }

        return redirect()->route('producers.show', $producer)->with('success', 'Item price added.');
    }

    public function show(ItemPrice $itemPrice)
    {
        $itemPrice->load('organization', 'producer');
        return view('item_prices.show', compact('itemPrice'));
    }

    public function edit(ItemPrice $itemPrice)
    {
        $categories = ItemPriceCategory::where('org_id', $itemPrice->org_id)->where('status', 'active')->orderBy('name')->get();
        $producer = $itemPrice->producer;

        return view('item_prices.edit', compact('itemPrice', 'categories', 'producer'));
    }

    public function update(Request $request, ItemPrice $itemPrice)
    {
        $data = $this->validateItemPrice($request);
        $data = $this->percentToDecimal($data);

        $producer = Producer::findOrFail($data['producer_id']);
        $data['org_id'] = $producer->org_id;

        $itemPrice->update($data);

        return redirect()->route('producers.show', $producer)->with('success', 'Item price updated.');
    }

    public function destroy(ItemPrice $itemPrice)
    {
        $producer = $itemPrice->producer;
        $itemPrice->delete();

        if ($producer) {
            return redirect()->route('producers.show', $producer)->with('success', 'Item price deleted.');
        }

        return redirect()->route('producers.index')->with('success', 'Item price deleted.');
    }

    private function percentToDecimal(array $data): array
    {
        foreach (['disc1', 'disc2', 'disc3', 'profit_base_unit', 'profit_box'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = $data[$field] / 100;
            }
        }
        return $data;
    }

    private function validateItemPrice(Request $request): array
    {
        return $request->validate([
            'producer_id'               => ['required', 'exists:producers,id'],
            'category_id'               => ['nullable', 'exists:item_price_categories,id'],
            'name'                      => ['required', 'string', 'max:255'],
            'base_unit'                 => ['nullable', 'string', 'max:50'],
            'qty_per_box'               => ['nullable', 'numeric', 'min:0'],
            'purchase_price'            => ['nullable', 'numeric', 'min:0'],
            'disc1'                     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'disc2'                     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'disc3'                     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'handling_cost'             => ['nullable', 'numeric', 'min:0'],
            'handling_qty'              => ['nullable', 'integer', 'min:1', 'max:9999'],
            'additional_cost_base_unit' => ['nullable', 'numeric', 'min:0'],
            'additional_cost_box'       => ['nullable', 'numeric', 'min:0'],
            'cost_price_base_unit'      => ['nullable', 'numeric', 'min:0'],
            'cost_price_box'            => ['nullable', 'numeric', 'min:0'],
            'rounding_base_unit'        => ['nullable', 'numeric', 'min:0'],
            'rounding_box'              => ['nullable', 'numeric', 'min:0'],
            'profit_base_unit'          => ['nullable', 'numeric', 'min:0', 'max:100'],
            'profit_box'                => ['nullable', 'numeric', 'min:0', 'max:100'],
            'selling_price_base_unit'   => ['nullable', 'numeric', 'min:0'],
            'selling_price_box'         => ['nullable', 'numeric', 'min:0'],
        ]);
    }
}
