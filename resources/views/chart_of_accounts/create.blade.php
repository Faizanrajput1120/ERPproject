@extends('full-width-light.index')

@section('title', isset($account) ? 'Edit Account' : 'Add Account')

@section('content')
<div class="container-fluid">
    <h1>{{ isset($account) ? 'Edit Account' : 'Add Account' }}</h1>

    <form method="POST" action="{{ isset($account) ? route('chart-of-accounts.update', $account->acc_id) : route('chart-of-accounts.store') }}">
        @csrf
        @if(isset($account))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="acc_name">Account Name</label>
            <input type="text" name="acc_name" class="form-control" value="{{ old('acc_name', $account->acc_name ?? '') }}">
            @error('acc_name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="level_id">Level</label>
            <select name="level_id" class="form-control select2">
                <option value="">-- Select Level --</option>
                @foreach($levels as $level)
                    <option value="{{ $level->level_id }}" {{ old('level_id', $account->level_id ?? '') == $level->level_id ? 'selected' : '' }}>
                        {{ $level->level_title }}
                    </option>
                @endforeach
            </select>
            @error('level_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($account) ? 'Update' : 'Create' }}</button>
        <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-link">Back to List</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({ theme: 'bootstrap4', allowClear: true });
    });
</script>
@endpush
