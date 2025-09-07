@extends('full-width-light.index')

@section('title', 'Chart of Accounts')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Chart of Accounts</h1>
    <a href="{{ route('chart-of-accounts.create') }}" class="btn btn-primary mb-3">Add Account</a>

    <!-- Dropdown Filter -->
    <form method="GET" class="mb-3">
        <div class="input-group">
            <select name="account" class="form-control select2">
                <option value="">-- Select Account --</option>
                @foreach($all_accounts as $acc)
                    <option value="{{ $acc->acc_id }}" {{ request('account') == $acc->acc_id ? 'selected' : '' }}>
                        {{ $acc->acc_name }}
                    </option>
                @endforeach
            </select>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Filter</button>
            </div>
        </div>
    </form>

    <!-- Table -->
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Account Name</th>
                <th>Level</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $account)
                <tr>
                    <td>{{ $account->acc_name }}</td>
                    <td>{{ $account->level->level_title ?? '-' }}</td>
                    <td class="text-right">
                        <a href="{{ route('chart-of-accounts.edit', $account->acc_id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('chart-of-accounts.destroy', $account->acc_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">No accounts found</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({ placeholder: 'Search account...', allowClear: true });
    });
</script>
@endpush
@endsection
