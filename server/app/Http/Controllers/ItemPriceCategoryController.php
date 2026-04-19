<?php

namespace App\Http\Controllers;

use App\Models\ItemPriceCategory;
use Illuminate\Http\Request;

class ItemPriceCategoryController extends Controller
{
    public function index()
    {
        $selectedOrgId = session('selected_org_id');

        $categories = ItemPriceCategory::with('organization')
            ->when($selectedOrgId, fn($q) => $q->where('org_id', $selectedOrgId))
            ->orderBy('name')
            ->paginate(20);

        return view('item_price_categories.index', compact('categories', 'selectedOrgId'));
    }

    public function create()
    {
        $selectedOrgId = session('selected_org_id');

        if (!$selectedOrgId) {
            return redirect()->route('item_price_categories.index')
                ->with('error', 'Please select an organization first.');
        }

        return view('item_price_categories.create', compact('selectedOrgId'));
    }

    public function store(Request $request)
    {
        $data = $this->validateCategory($request);
        ItemPriceCategory::create($data);

        return redirect()
            ->route('item_price_categories.index')
            ->with('success', 'Category added.');
    }

    public function edit(ItemPriceCategory $itemPriceCategory)
    {
        return view('item_price_categories.edit', compact('itemPriceCategory'));
    }

    public function update(Request $request, ItemPriceCategory $itemPriceCategory)
    {
        $data = $this->validateCategory($request);
        $itemPriceCategory->update($data);

        return redirect()
            ->route('item_price_categories.index')
            ->with('success', 'Category updated.');
    }

    public function destroy(ItemPriceCategory $itemPriceCategory)
    {
        $itemPriceCategory->delete();

        return redirect()
            ->route('item_price_categories.index')
            ->with('success', 'Category deleted.');
    }

    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'org_id' => ['required', 'exists:organizations,id'],
            'name'   => ['required', 'string', 'max:255'],
        ]);

        $category = ItemPriceCategory::create([
            'org_id' => $validated['org_id'],
            'name'   => $validated['name'],
            'status' => 'active',
        ]);

        return response()->json(['id' => $category->id, 'name' => $category->name]);
    }

    private function validateCategory(Request $request): array
    {
        return $request->validate([
            'org_id'      => ['required', 'exists:organizations,id'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:active,inactive'],
        ]);
    }
}
