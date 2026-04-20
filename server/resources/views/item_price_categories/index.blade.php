@extends('layouts.app')

@section('title', __('categories.title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">{{ __('categories.title') }}</h3>
    <a href="{{ route('item_price_categories.create') }}" class="btn btn-primary">+ {{ __('categories.add_category') }}</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive" style="overflow: visible">
        <table class="table table-hover mb-0">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>{{ __('common.name') }}</th>
                    <th>{{ __('common.description') }}</th>
                    <th>{{ __('common.status') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr class="align-middle">
                        <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                        <td class="fw-medium">{{ $category->name }}</td>
                        <td class="text-muted">{{ $category->description ?? '—' }}</td>
                        <td>
                            @if($category->status === 'active')
                                <span class="badge bg-success">{{ __('common.active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('common.inactive') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary px-2" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">&#8942;</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('item_price_categories.edit', $category) }}">{{ __('common.edit') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('item_price_categories.destroy', $category) }}"
                                              onsubmit="return confirm('{{ __('categories.delete_confirm') }}')">
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
                        <td colspan="5" class="text-center text-muted py-4">{{ __('categories.no_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $categories->links() }}</div>
@endsection
