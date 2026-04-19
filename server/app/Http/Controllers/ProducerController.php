<?php

namespace App\Http\Controllers;

use App\Models\ItemPrice;
use App\Models\Producer;
use Illuminate\Http\Request;

class ProducerController extends Controller
{
    public function index()
    {
        $selectedOrgId = session('selected_org_id');

        $producers = Producer::with('organization')
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

        return view('producers.create', compact('selectedOrgId'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProducer($request);
        Producer::create($data);

        return redirect()->route('producers.index')->with('success', 'Producer added.');
    }

    public function show(Request $request, Producer $producer)
    {
        session(['selected_producer_id' => $producer->id]);

        $selectedCategoryId = $request->query('category_id');

        $categories = \App\Models\ItemPriceCategory::where('org_id', $producer->org_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $itemPrices = ItemPrice::with('category')
            ->where('producer_id', $producer->id)
            ->when($selectedCategoryId, fn($q) => $q->where('category_id', $selectedCategoryId))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('producers.show', compact('producer', 'itemPrices', 'categories', 'selectedCategoryId'));
    }

    public function edit(Producer $producer)
    {
        return view('producers.edit', compact('producer'));
    }

    public function update(Request $request, Producer $producer)
    {
        $data = $this->validateProducer($request);
        $producer->update($data);

        return redirect()->route('producers.index')->with('success', 'Producer updated.');
    }

    public function destroy(Producer $producer)
    {
        $producer->delete();

        return redirect()->route('producers.index')->with('success', 'Producer deleted.');
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
