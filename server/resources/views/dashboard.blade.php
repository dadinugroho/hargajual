@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">Dashboard</h3>
    <a href="{{ route('producers.create') }}" class="btn btn-primary">+ Add Producer</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive" style="overflow: visible">
        <table class="table table-hover mb-0">
            <thead class="table-primary">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($producers as $producer)
                    <tr class="align-middle">
                        <td>{{ $loop->iteration + ($producers->currentPage() - 1) * $producers->perPage() }}</td>
                        <td>
                            <a href="{{ route('producers.show', $producer) }}" class="text-decoration-none fw-medium">
                                {{ $producer->name }}
                            </a>
                        </td>
                        <td>
                            @if($producer->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary px-2" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">&#8942;</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('producers.show', $producer) }}">View Item Prices</a></li>
                                    <li><a class="dropdown-item" href="{{ route('producers.edit', $producer) }}">Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('producers.destroy', $producer) }}"
                                              onsubmit="return confirm('Delete this producer?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No producers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $producers->links() }}</div>
@endsection
