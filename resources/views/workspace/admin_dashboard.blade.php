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
    <div class="card">
        <div class="card-header">Users</div>
        <div class="card-body">
            <ul>
                @forelse($users as $user)
                    <li>{{ $user->name }} ({{ $user->email }})</li>
                @empty
                    <li>No Users yet</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
