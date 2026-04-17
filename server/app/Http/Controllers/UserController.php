<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('users.index', compact('users'));
    }

    public function show(User $user)
    {
        $memberOrgs = $user->organizations()->orderBy('name')->get();
        $nonMemberOrgs = Organization::orderBy('name')
            ->whereNotIn('id', $memberOrgs->pluck('id'))
            ->get();

        return view('users.show', compact('user', 'memberOrgs', 'nonMemberOrgs'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::min(8)->letters()->numbers()],
            'role' => ['required', 'in:superadmin,user'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:superadmin,user'],
            'password' => ['nullable', Password::min(8)->letters()->numbers()],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = $request->password;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function addOrganization(Request $request, User $user)
    {
        $request->validate(['org_id' => ['required', 'exists:organizations,id']]);
        $user->organizations()->syncWithoutDetaching([$request->org_id]);

        return redirect()->route('users.show', $user)->with('success', 'User added to organization.');
    }

    public function removeOrganization(User $user, Organization $organization)
    {
        $user->organizations()->detach($organization->id);

        return redirect()->route('users.show', $user)->with('success', 'User removed from organization.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
