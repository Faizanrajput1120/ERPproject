<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Accounts Hierarchy</title>
    <style>
        body { font-family: DejaVu Sans, monospace; }
        ul { list-style-type: none; padding-left: 20px; }
        li { margin: 2px 0; }
        .group { font-weight: bold; }
        .level { font-weight: normal; }
        .account { font-style: italic; }
    </style>
</head>
<body>
    <h1>Accounts Hierarchy</h1>
    <ul>
        @foreach($groups as $group)
            <li class="group">{{ $group->name }}
                @if($group->levels)
                    <ul>
                        @foreach($group->levels as $level)
                            @include('levels.levels_pdf_item', ['level' => $level, 'prefix' => '', 'isLast' => true])
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</body>
</html>
