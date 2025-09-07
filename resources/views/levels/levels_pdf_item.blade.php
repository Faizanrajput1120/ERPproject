@php
    $children = collect($level->children)->merge($level->accounts ?? []);
    $total = count($children);
    $count = 0;
@endphp

<li class="level">
    {{ $prefix }}@if($total > 0)└─ @else ├─ @endif {{ $level->level_title }}

    @if($children->count())
        <ul>
            @foreach($level->children as $child)
                @php
                    $count++;
                    $childPrefix = $prefix . ($count == $total ? '    ' : '|   ');
                @endphp
                @include('levels.levels_pdf_item', ['level' => $child, 'prefix' => $childPrefix, 'isLast' => $count == $total])
            @endforeach

            @foreach($level->accounts as $account)
                @php $count++; @endphp
                <li class="account">{{ $prefix . ($count == $total ? '    ' : '|   ') }}├─ {{ $account->acc_name }}</li>
            @endforeach
        </ul>
    @endif
</li>
