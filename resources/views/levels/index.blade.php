@extends('full-width-light.index')

@section('title', 'Levels')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Levels</h1>
    <div class="mb-3 d-flex justify-content-between">
        <div>
            <a href="{{ route('levels.create') }}" class="btn btn-primary">Add Level</a>
            <a href="{{ route('levels.print') }}" target="_blank" class="btn btn-success">Print Tree</a>
        </div>
    </div>

    <!-- Filter -->
    <form method="GET" class="mb-3">
        <select name="level" class="form-control select2">
            <option value="">-- Select Level --</option>
            @foreach($all_levels as $lvl)
                <option value="{{ $lvl->level_id }}" {{ request('level') == $lvl->level_id ? 'selected' : '' }}>
                    {{ $lvl->level_title }}
                </option>
            @endforeach
        </select>
        <button class="btn btn-outline-secondary mt-2" type="submit">Filter</button>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Level Title</th>
                    <th>Group</th>
                    <th>Pre-Level</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($levels as $level)
                    <tr>
                        <td>{{ $level->level_title }}</td>
                        <td>{{ $level->group->name ?? '-' }}</td>
                        <td>{{ $level->pre->level_title ?? '-' }}</td>
                        <td>
                            <a href="{{ route('levels.edit', $level) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('levels.destroy', $level) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No levels found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- SweetAlert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({ theme: 'bootstrap4', allowClear: true });

    // SweetAlert notifications
    @if(session('success'))
        swal({
            title: "Success!",
            text: "{{ session('success') }}",
            icon: "success",
            timer: 3000,
            buttons: false,
        });
    @endif

    @if(session('error'))
        swal({
            title: "Error!",
            text: "{{ session('error') }}",
            icon: "error",
            timer: 3000,
            buttons: false,
        });
    @endif
});
</script>

@endsection