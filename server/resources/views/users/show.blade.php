@extends('layouts.app')

@section('title', $user->name)

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">&larr; {{ __('common.back') }}</a>
    <div>
        <h3 class="mb-0">{{ $user->name }}</h3>
        <p class="text-muted mb-0 mt-1">{{ $user->email }}</p>
    </div>
    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary ms-auto">{{ __('common.edit') }}</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Organizations list --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                {{ __('common.organizations') }} <span class="badge bg-secondary ms-1">{{ $memberOrgs->count() }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('common.name') }}</th>
                            <th>{{ __('common.description') }}</th>
                            <th class="text-end">{{ __('common.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($memberOrgs as $org)
                            <tr>
                                <td>
                                    <a href="{{ route('organizations.show', $org) }}" class="text-decoration-none">
                                        {{ $org->name }}
                                    </a>
                                </td>
                                <td class="text-muted">{{ $org->description ?? '—' }}</td>
                                <td class="text-end">
                                    <form method="POST"
                                          action="{{ route('users.organizations.remove', [$user, $org]) }}"
                                          onsubmit="return confirm('{{ __('users.remove_confirm', ['user' => $user->name, 'org' => $org->name]) }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">{{ __('common.remove') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">{{ __('users.no_organizations') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add to organization --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">{{ __('users.add_to_organization') }}</div>
            <div class="card-body">
                @if($user->isSuperAdmin())
                    <p class="text-muted mb-0">{{ __('users.superadmin_access') }}</p>
                @elseif($nonMemberOrgs->isEmpty())
                    <p class="text-muted mb-0">{{ __('users.already_member_all') }}</p>
                @else
                    <form method="POST" action="{{ route('users.organizations.add', $user) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="org_id" class="form-label">{{ __('users.select_organization') }}</label>
                            <select id="org_id" name="org_id" class="form-select @error('org_id') is-invalid @enderror" required>
                                <option value="">{{ __('users.choose_organization') }}</option>
                                @foreach($nonMemberOrgs as $org)
                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                @endforeach
                            </select>
                            @error('org_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">{{ __('users.add_to_organization') }}</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
