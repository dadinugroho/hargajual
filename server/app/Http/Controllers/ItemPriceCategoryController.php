<?php

namespace App\Http\Controllers;

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
        $this->authorizeOrg($itemPriceCategory->org_id);
        $data = $this->validateCategory($request);
        $itemPriceCategory->update($data);

        return redirect()
            ->route('item_price_categories.index')
            ->with('success', 'Category updated.');
    }

    public function destroy(ItemPriceCategory $itemPriceCategory)
    {
        $this->authorizeOrg($itemPriceCategory->org_id);
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
