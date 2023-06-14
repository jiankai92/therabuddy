@php
    if (session('errors')) {
        $errors = session('errors');
    }
    if (session('error')) {
        $error = session('error');
    }
    if (session('message')) {
        $message = session('message');
    }
    if (session('success')) {
        $success = session('success');
    }
@endphp

@if ( isset($error) )
    <div class="alert alert-danger">
        {!! nl2br($error) !!}
    </div>
@endif

@if ( isset($message) )
    <div class="alert alert-info">
        {!! nl2br($message) !!}
    </div>
@endif
@if ( isset($success) )
    <div class="alert alert-success">
        {!! nl2br($success) !!}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
