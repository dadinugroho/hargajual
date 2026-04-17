<?php

namespace App\Http\Controllers;

use App\Models\Organization;

class ProfileController extends Controller
{
    public function show()
    {
        $organizations = auth()->user()->organizations()->orderBy('name')->get();
        return view('profile.show', compact('organizations'));
    }

    public function leaveOrganization(Organization $organization)
    {
        auth()->user()->organizations()->detach($organization->id);

        return redirect()->route('profile.show')->with('success', 'You have left "' . $organization->name . '".');
    }
}
