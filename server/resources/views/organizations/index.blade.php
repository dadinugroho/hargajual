@extends('layouts.app')

@section('title', __('organizations.title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">{{ __('organizations.title') }}</h3>
    <a href="{{ route('organizations.create') }}" class="btn btn-primary">+ {{ __('organizations.add_organization') }}</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>{{ __('common.name') }}</th>
                    <th>{{ __('common.description') }}</th>
                    <th>{{ __('common.members') }}</th>
                    <th class="text-end">{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($organizations as $org)
                    <tr>
                        <td>{{ $loop->iteration + ($organizations->currentPage() - 1) * $organizations->perPage() }}</td>
                        <td>
                            <a href="{{ route('organizations.show', $org) }}" class="text-decoration-none fw-medium">
                                {{ $org->name }}
                            </a>
                        </td>
                        <td class="text-muted">{{ $org->description ?? '—' }}</td>
                        <td><span class="badge bg-secondary">{{ $org->users_count }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('organizations.show', $org) }}" class="btn btn-sm btn-outline-secondary">{{ __('common.view') }}</a>
                            <a href="{{ route('organizations.edit', $org) }}" class="btn btn-sm btn-outline-primary">{{ __('common.edit') }}</a>
                            <form method="POST" action="{{ route('organizations.destroy', $org) }}" class="d-inline"
                                  onsubmit="return confirm('{{ __('organizations.delete_confirm') }}')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">{{ __('common.delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">{{ __('organizations.no_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $organizations->links() }}</div>
@endsection
