@extends('full-width-light.index')

@section('title', isset($level) ? 'Edit Level' : 'Add Level')

@section('content')
<div class="container-fluid">
    <h1>{{ isset($level) ? 'Edit Level' : 'Add Level' }}</h1>

    <form method="POST" action="{{ isset($level) ? route('levels.update', $level->level_id) : route('levels.store') }}">
        @csrf
        @if(isset($level))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="level_title">Level Title</label>
            <input type="text" name="level_title" class="form-control" value="{{ old('level_title', $level->level_title ?? '') }}">
            @error('level_title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="group">Group</label>
            <select name="group_id" id="id_group" class="form-control select2">
                <option value="">-- Select Group --</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" {{ old('group_id', $level->group_id ?? '') == $group->id ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="pre">Pre-Level</label>
            <select name="pre_id" id="id_pre" class="form-control select2">
                <option value="">-- Select Pre-Level --</option>
                @foreach($pre_levels as $pre)
                    <option value="{{ $pre->level_id }}" {{ old('pre_id', $level->pre_id ?? '') == $pre->level_id ? 'selected' : '' }}>
                        {{ $pre->level_title }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($level) ? 'Update' : 'Create' }}</button>
        <a href="{{ route('levels.index') }}" class="btn btn-link">Back to List</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const groupSelect = $('#id_group').select2({ theme: 'bootstrap4', placeholder: "Select Group", allowClear: true });
    const preSelect = $('#id_pre').select2({ theme: 'bootstrap4', placeholder: "Select Pre-Level", allowClear: true });

    function toggleFields() {
        if ($('#id_group').val()) {
            preSelect.prop('disabled', true).trigger('change');
        } else {
            preSelect.prop('disabled', false).trigger('change');
        }
        if ($('#id_pre').val()) {
            groupSelect.prop('disabled', true).trigger('change');
        } else {
            groupSelect.prop('disabled', false).trigger('change');
        }
    }

    toggleFields();
    $('#id_group, #id_pre').on('change', toggleFields);
});
</script>
@endpush
