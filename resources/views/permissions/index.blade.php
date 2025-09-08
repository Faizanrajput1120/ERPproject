@extends('full-width-light.index')

@section('content')
<div class="container">
    <h2 class="mb-4">User Permissions</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a href="{{ route('permissions.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit Permissions</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
<div>
    <!-- Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh -->
</div>
