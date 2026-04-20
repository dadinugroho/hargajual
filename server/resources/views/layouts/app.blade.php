<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">{{ config('app.name') }}</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    @auth
                        @if(auth()->user()->isSuperAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('organizations.*') ? 'active' : '' }}" href="{{ route('organizations.index') }}">Organizations</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('item_price_categories.*') ? 'active' : '' }}" href="{{ route('item_price_categories.index') }}">Categories</a>
                        </li>
                        @if(isset($navOrganizations) && $navOrganizations->isNotEmpty())
                            <li class="nav-item">
                                <select class="form-select form-select-sm text-dark"
                                        style="max-width:200px; background-color: rgba(255,255,255,0.15); color: white; border-color: rgba(255,255,255,0.4)"
                                        onchange="window.location='/select-org?org_id='+this.value">
                                    @foreach($navOrganizations as $org)
                                        <option value="{{ $org->id }}" {{ $navSelectedOrgId == $org->id ? 'selected' : '' }}
                                                style="color: #212529; background-color: white">
                                            {{ $org->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                                {{ auth()->user()->name }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-light">{{ __('common.logout') }}</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
