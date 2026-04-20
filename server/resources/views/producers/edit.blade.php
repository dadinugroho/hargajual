@extends('layouts.app')

@section('title', __('producers.edit_producer'))

@section('content')
<div class="d-flex align-items-center mb-4 gap-3">
    <a href="{{ route('producers.index') }}" class="btn btn-sm btn-outline-secondary">&larr; {{ __('common.back') }}</a>
    <h3 class="mb-0">{{ __('producers.edit_producer') }}</h3>
</div>

<div class="card border-0 shadow-sm" style="max-width:480px">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('producers.update', $producer) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="org_id" value="{{ $producer->org_id }}">

            <div class="mb-3">
                <label for="name" class="form-label">{{ __('common.name') }}</label>
                <input type="text" id="name" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $producer->name) }}" required autofocus>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label for="status" class="form-label">{{ __('common.status') }}</label>
                <select id="status" name="status"
                        class="form-select @error('status') is-invalid @enderror">
                    <option value="active" {{ old('status', $producer->status) === 'active' ? 'selected' : '' }}>{{ __('common.active') }}</option>
                    <option value="inactive" {{ old('status', $producer->status) === 'inactive' ? 'selected' : '' }}>{{ __('common.inactive') }}</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">{{ __('common.save_changes') }}</button>
                <a href="{{ route('producers.index') }}" class="btn btn-outline-secondary">{{ __('common.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
