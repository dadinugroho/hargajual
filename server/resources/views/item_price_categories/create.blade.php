@extends('layouts.app')

@section('title', 'Add Category')

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('item_price_categories.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Back</a>
    <h3 class="mb-0">Add Category</h3>
</div>

<div class="card border-0 shadow-sm" style="max-width:600px">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('item_price_categories.store') }}">
            @csrf
            <input type="hidden" name="org_id" value="{{ $selectedOrgId }}">
            <div class="row g-3">
                <div class="col-12">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required autofocus>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label for="description" class="form-label">Description <span class="text-muted">(optional)</span></label>
                    <textarea id="description" name="description" rows="3"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status"
                            class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-primary">Add Category</button>
                    <a href="{{ route('item_price_categories.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
