@extends('full-width-light.index')
@section('content')
<div class="container">
    <h2 class="mb-4">Add User to Workspace</h2>
    <div class="card mb-4">
        <div class="card-header">Create User</div>
        <div class="card-body">
            <form method="POST" action="{{ route('workspace.storeUser') }}">
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
</div>
@endsection
