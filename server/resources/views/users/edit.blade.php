@extends('layouts.app')

@section('title', __('users.edit_user'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">&larr; {{ __('common.back') }}</a>
            <h3 class="mb-0">{{ __('users.edit_user') }}</h3>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('common.name') }}</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('common.email') }}</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('common.new_password') }} <small class="text-muted">({{ __('common.new_password_hint') }})</small></label>
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label for="role" class="form-label">{{ __('common.role') }}</label>
                        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>{{ __('users.role_user') }}</option>
                            <option value="superadmin" {{ old('role', $user->role) === 'superadmin' ? 'selected' : '' }}>{{ __('users.role_superadmin') }}</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">{{ __('users.update_user') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
