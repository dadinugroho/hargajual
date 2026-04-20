@extends('layouts.app')

@section('title', __('organizations.edit_organization'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('organizations.show', $organization) }}" class="btn btn-sm btn-outline-secondary">&larr; {{ __('common.back') }}</a>
            <h3 class="mb-0">{{ __('organizations.edit_organization') }}</h3>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('organizations.update', $organization) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('common.name') }}</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $organization->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label">{{ __('common.description') }} <small class="text-muted">({{ __('common.optional') }})</small></label>
                        <textarea id="description" name="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $organization->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">{{ __('organizations.update_organization') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
