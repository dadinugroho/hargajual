@extends('layouts.app')

@section('title', $organization->name)

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('organizations.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Back</a>
    <div>
        <h3 class="mb-0">{{ $organization->name }}</h3>
        @if($organization->description)
            <p class="text-muted mb-0 mt-1">{{ $organization->description }}</p>
        @endif
    </div>
    <a href="{{ route('organizations.edit', $organization) }}" class="btn btn-sm btn-outline-primary ms-auto">Edit</a>
</div>

<div class="row g-4">
    {{-- Members list --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                Members <span class="badge bg-secondary ms-1">{{ $members->count() }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td class="text-muted">{{ $member->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $member->isSuperAdmin() ? 'danger' : 'secondary' }}">
                                        {{ $member->role }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <form method="POST"
                                          action="{{ route('organizations.users.remove', [$organization, $member]) }}"
                                          onsubmit="return confirm('Remove {{ $member->name }} from this organization?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No members yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add user --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Add User</div>
            <div class="card-body">
                @if($nonMembers->isEmpty())
                    <p class="text-muted mb-0">All users are already members.</p>
                @else
                    <form method="POST" action="{{ route('organizations.users.add', $organization) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Select user</label>
                            <select id="user_id" name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">— choose a user —</option>
                                @foreach($nonMembers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add to Organization</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
