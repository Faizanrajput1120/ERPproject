@extends('full-width-light.index')

@section('title', 'Levels')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-diagram-3-fill text-primary me-2"></i> Levels
        </h2>
        <div>
            <a href="{{ route('levels.create') }}" class="btn btn-primary me-2">
                <i class="bi bi-plus-lg"></i> Add Level
            </a>
            <a href="{{ route('levels.print') }}" target="_blank" class="btn btn-success">
                <i class="bi bi-printer"></i> Print Tree
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-6">
                    <select name="level" class="form-select select2">
                        <option value="">-- Select Level --</option>
                        @foreach($all_levels as $lvl)
                            <option value="{{ $lvl->level_id }}" {{ request('level') == $lvl->level_id ? 'selected' : '' }}>
                                {{ $lvl->level_title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Levels Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Level Title</th>
                            <th>Group</th>
                            <th>Pre-Level</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($levels as $level)
                            <tr>
                                <td>{{ $level->level_title }}</td>
                                <td>{{ $level->group->name ?? '-' }}</td>
                                <td>{{ $level->pre->level_title ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('levels.edit', $level) }}" class="btn btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('levels.destroy', $level) }}" method="POST" onsubmit="return confirmDelete(event)" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">No levels found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- SweetAlert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({ theme: 'bootstrap4', allowClear: true });

    // SweetAlert notifications
    @if(session('success'))
        swal("Success!", "{{ session('success') }}", "success");
    @endif

    @if(session('error'))
        swal("Error!", "{{ session('error') }}", "error");
    @endif
});

// Confirmation with SweetAlert
function confirmDelete(event) {
    event.preventDefault();
    let form = event.target;

    swal({
        title: "Are you sure?",
        text: "This action cannot be undone.",
        icon: "warning",
        buttons: ["Cancel", "Yes, delete it!"],
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            form.submit();
        }
    });

    return false;
}
</script>
@endsection
