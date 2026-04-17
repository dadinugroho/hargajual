@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h3 class="mb-3">Dashboard</h3>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="mb-0">Welcome back, <strong>{{ auth()->user()->name }}</strong>!</p>
                <p class="text-muted mb-0">Role: <span class="badge bg-{{ auth()->user()->isSuperAdmin() ? 'danger' : 'secondary' }}">{{ auth()->user()->role }}</span></p>
            </div>
        </div>
    </div>
</div>
@endsection
