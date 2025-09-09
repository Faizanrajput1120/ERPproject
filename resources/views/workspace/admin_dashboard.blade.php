@extends('full-width-light.index')
@section('content')
<div class="container">
    <h2 class="mb-4">Workspace – Admin</h2>

    <h4>Your Workspace: {{ $workspace->c_name ?? 'N/A' }}</h4>

    {{-- Create User Form --}}
    <div class="card mb-4">
        <div class="card-header">Create User</div>
        <div class="card-body">
            <form method="POST" action="{{ route('workspace.createUser') }}">
                @csrf
                <div class="form-group mb-2">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-primary">Create User</button>
            </form>
        </div>
    </div>

    {{-- Company Users List --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white d-flex align-items-center">
        <i class="bi bi-people-fill me-2"></i>
        <strong>Company Users</strong>
    </div>
    <div class="card-body p-0">
        @if($users->count())
            <ul class="list-group list-group-flush">
                @foreach($users as $user)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-person-circle text-secondary me-2"></i>
                            <strong>{{ $user->name }}</strong>
                            <small class="text-muted d-block">{{ $user->email }}</small>
                        </div>
                        <span class="badge bg-light text-dark rounded-pill">
                            {{ ucfirst($user->role ?? 'User') }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="p-3 text-muted text-center">
                <i class="bi bi-exclamation-circle me-1"></i> No users yet.
            </div>
        @endif
    </div>
</div>

</div>
@endsection
