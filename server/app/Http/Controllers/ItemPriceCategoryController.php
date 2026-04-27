<?php

namespace App\Http\Controllers;

use App\Models\ItemPrice;
use App\Models\ItemPriceCategory;
use Illuminate\Http\Request;

class ItemPriceCategoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $selectedOrgId = session('selected_org_id');
        $allowedOrgIds = $user->isSuperAdmin() ? null : $user->orgIds();

        $categories = ItemPriceCategory::with('organization')
            ->when($allowedOrgIds, fn($q) => $q->whereIn('org_id', $allowedOrgIds))
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

        $this->authorizeOrg($selectedOrgId);

        return view('item_price_categories.create', compact('selectedOrgId'));
    }

    public function store(Request $request)
    {
        $data = $this->validateCategory($request);
        $this->authorizeOrg($data['org_id']);
        ItemPriceCategory::create($data);

        return redirect()
            ->route('item_price_categories.index')
            ->with('success', 'Category added.');
    }

    public function edit(ItemPriceCategory $itemPriceCategory)
    {
        $this->authorizeOrg($itemPriceCategory->org_id);
        return view('item_price_categories.edit', compact('itemPriceCategory'));
    }

    public function update(Request $request, ItemPriceCategory $itemPriceCategory)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);
        $this->authorizeOrg($itemPriceCategory->org_id);
        $data = $this->validateCategory($request);
        $itemPriceCategory->update($data);

        return redirect()
            ->route('item_price_categories.index')
            ->with('success', 'Category updated.');
    }

    public function destroy(ItemPriceCategory $itemPriceCategory)
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403);
        $this->authorizeOrg($itemPriceCategory->org_id);
        $itemPriceCategory->delete();

        return redirect()
            ->route('item_price_categories.index')
            ->with('success', 'Category deleted.');
    }

    public function bulkUpdate(Request $request, ItemPriceCategory $itemPriceCategory)
    {
        $this->authorizeOrg($itemPriceCategory->org_id);

        $validated = $request->validate([
            'producer_id'        => ['nullable', 'exists:producers,id'],
            'disc1'              => ['nullable', 'numeric', 'min:0', 'max:100'],
            'disc2'              => ['nullable', 'numeric', 'min:0', 'max:100'],
            'disc3'              => ['nullable', 'numeric', 'min:0', 'max:100'],
            'disc4'              => ['nullable', 'numeric', 'min:0', 'max:100'],
            'disc5'              => ['nullable', 'numeric', 'min:0', 'max:100'],
            'disc6'              => ['nullable', 'numeric', 'min:0', 'max:100'],
            'handling_cost'      => ['nullable', 'numeric', 'min:0'],
            'profit_base_unit'   => ['nullable', 'numeric', 'min:0', 'max:100'],
            'profit_box'         => ['nullable', 'numeric', 'min:0', 'max:100'],
            'rounding_base_unit' => ['nullable', 'numeric', 'min:0'],
            'rounding_box'       => ['nullable', 'numeric', 'min:0'],
        ]);

        $producerId = $validated['producer_id'] ?? null;
        unset($validated['producer_id']);

        $updates = array_filter($validated, fn($v) => $v !== null);

        foreach (['disc1','disc2','disc3','disc4','disc5','disc6','profit_base_unit','profit_box'] as $pct) {
            if (isset($updates[$pct])) {
                $updates[$pct] = $updates[$pct] / 100;
            }
        }

        if (isset($updates['handling_cost'])) {
            $updates['handling_qty'] = 1;
        }

        if (!empty($updates)) {
            $itemPriceCategory->itemPrices()
                ->when($producerId, fn($q) => $q->where('producer_id', $producerId))
                ->update($updates);
        }

        return redirect()->back()->with('success', __('categories.bulk_update_success'));
    }

    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'org_id' => ['required', 'exists:organizations,id'],
            'name'   => ['required', 'string', 'max:255'],
        ]);

        $this->authorizeOrg($validated['org_id']);

        $category = ItemPriceCategory::create([
            'org_id' => $validated['org_id'],
            'name'   => $validated['name'],
            'status' => 'active',
        ]);

        return response()->json(['id' => $category->id, 'name' => $category->name]);
    }

    private function authorizeOrg(int $orgId): void
    {
        $user = auth()->user();
        abort_unless($user->isSuperAdmin() || in_array($orgId, $user->orgIds()), 403);
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
