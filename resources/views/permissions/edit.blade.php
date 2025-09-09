@extends('full-width-light.index')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Permissions for {{ $user->name }}</h2>
    <form method="POST" action="{{ route('permissions.update', $user->id) }}">
        @csrf
        @method('PUT')
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Module</th>
                    @foreach($rights as $right)
                        <th>{{ ucfirst($right) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($modules as $module)
                    <tr>
                        <td>{{ ucfirst(str_replace('-', ' ', $module)) }}</td>
                        @foreach($rights as $right)
                            <td>
                                <input type="checkbox" name="perm_{{ $module }}_{{ $right }}" value="1"
                                    @if(isset($userRights[$module]) && $userRights[$module]->$right) checked @endif>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-success">Save Permissions</button>
        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
<div>
    <!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
</div>
