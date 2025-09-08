@extends('full-width-light.index')
@section('content')
<div class="container">
    <h2 class="mb-4">Create Workspace</h2>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('workspace.stores') }}">
                @csrf
                <div class="form-group mb-3">
                    <label for="c_name">Workspace Name</label>
                    <input type="text" name="c_name" id="c_name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>

    @if(isset($workspaces) && count($workspaces))
    <div class="card mt-4">
        <div class="card-header">All Workspaces</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workspaces as $workspace)
                        <tr>
                            <td>{{ $workspace->cid }}</td>
                            <td>{{ $workspace->c_name }}</td>
                            <td>{{ $workspace->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

