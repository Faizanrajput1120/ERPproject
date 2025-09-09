@extends('full-width-light.index')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-building-gear me-2 text-primary"></i>
                Workspace Management
            </h2>
            <small class="text-muted">Super Admin Panel</small>
        </div>
    </div>

    {{-- Create Admin Form --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="bi bi-person-plus me-2"></i>
            <strong>Create New Admin</strong>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('workspace.createAdmin') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Workspace</label>
                        <select name="workspace_id" class="form-select" required>
                            <option value="">-- Select Workspace --</option>
                            @foreach($workspaces as $workspace)
                                <option value="{{ $workspace->cid }}">{{ $workspace->c_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter admin name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter admin email" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Create Admin
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Create User Form (Super Admin) --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-success text-white d-flex align-items-center">
            <i class="bi bi-person-plus-fill me-2"></i>
            <strong>Create New User</strong>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('workspace.createUser') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Workspace</label>
                        <select name="workspace_id" class="form-select" required>
                            <option value="">-- Select Workspace --</option>
                            @foreach($workspaces as $workspace)
                                <option value="{{ $workspace->cid }}">{{ $workspace->c_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter user name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter user email" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button class="btn btn-success">
                        <i class="bi bi-save me-1"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

   {{-- Workspaces + Admins & Users List --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-secondary text-white d-flex align-items-center">
        <i class="bi bi-people-fill me-2"></i>
        <strong>Workspaces, Admins & Users</strong>
    </div>
    <div class="card-body">
        @forelse($workspaces as $workspace)
            <div class="mb-4">
                <h5 class="fw-bold text-dark">
                    <i class="bi bi-building me-1 text-muted"></i> {{ $workspace->c_name }}
                </h5>

                @if($workspace->admins->isNotEmpty() || $workspace->users->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workspace->admins as $admin)
                                    <tr>
                                        <td><span class="fw-semibold">{{ $admin->name }}</span></td>
                                        <td><span class="badge bg-light text-dark">{{ $admin->email }}</span></td>
                                        <td><span class="badge bg-primary">Admin</span></td>
                                    </tr>
                                @endforeach

                                @foreach($workspace->users as $user)
                                    <tr>
                                        <td><span class="fw-semibold">{{ $user->name }}</span></td>
                                        <td><span class="badge bg-light text-dark">{{ $user->email }}</span></td>
                                        <td><span class="badge bg-success">User</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted fst-italic">
                        <i class="bi bi-person-dash me-1"></i> No users or admins assigned yet.
                    </p>
                @endif
            </div>
            <hr>
        @empty
            <div class="text-center py-4 text-muted">
                <i class="bi bi-building-slash display-6 d-block mb-2"></i>
                <p class="mb-0">No workspaces available.</p>
            </div>
        @endforelse
    </div>
</div>

</div>
@endsection
