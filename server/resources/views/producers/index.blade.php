@extends('layouts.app')

@section('title', __('producers.title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">{{ __('producers.title') }}</h3>
    <a href="{{ route('producers.create') }}" class="btn btn-primary">+ {{ __('producers.add_producer') }}</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive" style="overflow: visible">
        <table class="table table-hover mb-0">
            <thead class="table-primary">
                <tr>
                    <th style="width:3rem">#</th>
                    <th>{{ __('common.name') }}</th>
                    <th style="width:6rem" class="text-nowrap">{{ __('common.status') }}</th>
                    <th style="width:3rem"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($producers as $producer)
                    <tr class="align-middle">
                        <td class="text-nowrap">{{ $loop->iteration + ($producers->currentPage() - 1) * $producers->perPage() }}</td>
                        <td>
                            <a href="{{ route('producers.show', $producer) }}" class="text-decoration-none fw-medium">
                                {{ $producer->name }}
                            </a>
                        </td>
                        <td class="text-nowrap">
                            @if($producer->status === 'active')
                                <span class="badge bg-success">{{ __('common.active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('common.inactive') }}</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary px-2" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">&#8942;</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('producers.show', $producer) }}">{{ __('producers.view_item_prices') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('producers.edit', $producer) }}">{{ __('common.edit') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('producers.destroy', $producer) }}"
                                              onsubmit="return confirm('{{ __('producers.delete_confirm') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">{{ __('common.delete') }}</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">{{ __('producers.no_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $producers->links() }}</div>
@endsection
