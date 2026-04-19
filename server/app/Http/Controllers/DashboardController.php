<?php

namespace App\Http\Controllers;

use App\Models\Producer;

class DashboardController extends Controller
{
    public function index()
    {
        $selectedOrgId = session('selected_org_id');

        $producers = Producer::with('organization')
            ->when($selectedOrgId, fn($q) => $q->where('org_id', $selectedOrgId))
            ->orderBy('name')
            ->paginate(20);

        return view('dashboard', compact('producers', 'selectedOrgId'));
    }
}
