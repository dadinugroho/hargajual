@extends('layouts.app')

@section('title', __('users.title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">{{ __('users.title') }}</h3>
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ {{ __('users.add_user') }}</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>{{ __('common.name') }}</th>
                    <th>{{ __('common.email') }}</th>
                    <th>{{ __('common.role') }}</th>
                    <th>{{ __('common.created') }}</th>
                    <th class="text-end">{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->isSuperAdmin() ? 'danger' : 'secondary' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-secondary">{{ __('common.view') }}</a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">{{ __('common.edit') }}</a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline"
                                      onsubmit="return confirm('{{ __('users.delete_confirm') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">{{ __('common.delete') }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">{{ __('users.no_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $users->links() }}
</div>
@endsection
