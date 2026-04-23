<?php

namespace App\Http\Controllers;

use App\Models\ItemPrice;
use App\Models\ItemPriceCategory;
use App\Models\Producer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ProducerController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $selectedOrgId = session('selected_org_id');
        $allowedOrgIds = $user->isSuperAdmin() ? null : $user->orgIds();

        $producers = Producer::with('organization')
            ->when($allowedOrgIds, fn($q) => $q->whereIn('org_id', $allowedOrgIds))
            ->when($selectedOrgId, fn($q) => $q->where('org_id', $selectedOrgId))
            ->orderBy('name')
            ->paginate(20);

        return view('producers.index', compact('producers', 'selectedOrgId'));
    }

    public function create()
    {
        $selectedOrgId = session('selected_org_id');

        if (!$selectedOrgId) {
            return redirect()->route('producers.index')
                ->with('error', 'Please select an organization first.');
        }

        $this->authorizeOrg($selectedOrgId);

        return view('producers.create', compact('selectedOrgId'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProducer($request);
        $this->authorizeOrg($data['org_id']);
        Producer::create($data);

        return redirect()->route('producers.index')->with('success', 'Producer added.');
    }

    public function show(Request $request, Producer $producer)
    {
        $this->authorizeOrg($producer->org_id);
        session(['selected_producer_id' => $producer->id]);

        $selectedCategoryId = $request->query('category_id');

        $sort    = $request->query('sort', 'id');
        $dir     = $request->query('dir', 'desc');
        $perPage = (int) $request->query('per_page', 25);
        if (!in_array($sort, ['id', 'name'])) $sort = 'id';
        if (!in_array($dir, ['asc', 'desc'])) $dir = 'desc';
        if (!in_array($perPage, [5, 10, 25, 50, 100])) $perPage = 25;

        $categories = \App\Models\ItemPriceCategory::where('org_id', $producer->org_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $itemPrices = ItemPrice::with('category')
            ->where('producer_id', $producer->id)
            ->when($selectedCategoryId, fn($q) => $q->where('category_id', $selectedCategoryId))
            ->orderBy($sort === 'id' ? 'id' : 'name', $dir)
            ->paginate($perPage)
            ->withQueryString();

        $editingItemId = old('_editing_item_id');
        $editingItem = $editingItemId
            ? ItemPrice::where('id', $editingItemId)->where('producer_id', $producer->id)->first()
            : null;

        $showAddForm = session('show_add_form', false);
        $prefillCategoryId = session('prefill_category', null);

        return view('producers.show', compact(
            'producer', 'itemPrices', 'categories', 'selectedCategoryId',
            'editingItem', 'showAddForm', 'prefillCategoryId'
        ));
    }

    public function pdf(Request $request, Producer $producer)
    {
        $this->authorizeOrg($producer->org_id);

        $selectedCategoryId = $request->query('category_id');
        $cols = $request->query('cols', ['name', 'selling_price']);
        $title1 = $request->query('title1', '');
        $title2 = $request->query('title2', '');

        $itemPrices = ItemPrice::with('category')
            ->where('producer_id', $producer->id)
            ->when($selectedCategoryId, fn($q) => $q->where('category_id', $selectedCategoryId))
            ->orderBy('name')
            ->get();

        $pdf = Pdf::loadView('item_prices.pdf', compact(
            'producer', 'itemPrices', 'cols', 'title1', 'title2', 'selectedCategoryId'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream($producer->name . '-price-list.pdf');
    }

    public function edit(Producer $producer)
    {
        $this->authorizeOrg($producer->org_id);
        return view('producers.edit', compact('producer'));
    }

    public function update(Request $request, Producer $producer)
    {
        $this->authorizeOrg($producer->org_id);
        $data = $this->validateProducer($request);
        $producer->update($data);

        return redirect()->route('producers.index')->with('success', 'Producer updated.');
    }

    public function destroy(Producer $producer)
    {
        $this->authorizeOrg($producer->org_id);
        $producer->delete();

        return redirect()->route('producers.index')->with('success', 'Producer deleted.');
    }

    private function authorizeOrg(int $orgId): void
    {
        $user = auth()->user();
        abort_unless($user->isSuperAdmin() || in_array($orgId, $user->orgIds()), 403);
    }

    private function validateProducer(Request $request): array
    {
        return $request->validate([
            'org_id' => ['required', 'exists:organizations,id'],
            'name'   => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);
    }
}
