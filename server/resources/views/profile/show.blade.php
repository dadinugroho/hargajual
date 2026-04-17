@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<h3 class="mb-4">My Profile</h3>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="mb-1"><strong>{{ auth()->user()->name }}</strong></p>
                <p class="text-muted mb-1">{{ auth()->user()->email }}</p>
                <span class="badge bg-{{ auth()->user()->isSuperAdmin() ? 'danger' : 'secondary' }}">
                    {{ auth()->user()->role }}
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">My Organizations</div>
            @if(auth()->user()->isSuperAdmin())
                <div class="card-body text-muted">Member of all organizations.</div>
            @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Joined</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($organizations as $org)
                            <tr>
                                <td class="fw-medium">{{ $org->name }}</td>
                                <td class="text-muted">{{ $org->description ?? '—' }}</td>
                                <td class="text-muted">{{ $org->pivot->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <form method="POST"
                                          action="{{ route('profile.leave-organization', $org) }}"
                                          onsubmit="return confirm('Leave {{ $org->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Leave</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    You are not a member of any organization.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
