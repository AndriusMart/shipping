<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        
    </head>
    <body >
        @forelse($results as $result)
                <div>
                    {{$result['date']}} {{$result['size']}} {{$result['provider']}} {{$result['price']}} {{$result['discount']}} 
                    @if($result['size'] == null)
                    {{$result['status']}}
                    @endif
                </div>
        @empty
        <li class="list-group-item">No books found</li>
        @endforelse
    </body>
</html>
