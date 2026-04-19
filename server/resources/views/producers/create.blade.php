@extends('layouts.app')

@section('title', 'Add Producer')

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('producers.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Back</a>
    <h3 class="mb-0">Add Producer</h3>
</div>

<div class="card border-0 shadow-sm" style="max-width:480px">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('producers.store') }}">
            @csrf
            <input type="hidden" name="org_id" value="{{ $selectedOrgId }}">

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required autofocus>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status"
                        class="form-select @error('status') is-invalid @enderror">
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary">Add Producer</button>
        </form>
    </div>
</div>
@endsection
