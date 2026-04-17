<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::withCount('users')->orderBy('name')->paginate(15);
        return view('organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('organizations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:organizations,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Organization::create($request->only('name', 'description'));

        return redirect()->route('organizations.index')->with('success', 'Organization created.');
    }

    public function show(Organization $organization)
    {
        $members = $organization->users()->orderBy('name')->get();
        $nonMembers = User::whereNotIn('id', $members->pluck('id'))->orderBy('name')->get();

        return view('organizations.show', compact('organization', 'members', 'nonMembers'));
    }

    public function edit(Organization $organization)
    {
        return view('organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:organizations,name,' . $organization->id],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $organization->update($request->only('name', 'description'));

        return redirect()->route('organizations.show', $organization)->with('success', 'Organization updated.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return redirect()->route('organizations.index')->with('success', 'Organization deleted.');
    }

    public function addUser(Request $request, Organization $organization)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $organization->users()->syncWithoutDetaching([$request->user_id]);

        return redirect()->route('organizations.show', $organization)->with('success', 'User added to organization.');
    }

    public function removeUser(Organization $organization, User $user)
    {
        $organization->users()->detach($user->id);

        return redirect()->route('organizations.show', $organization)->with('success', 'User removed from organization.');
    }
}
