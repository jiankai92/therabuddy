@php
    $alerts = collect();
    if (session('errors')) {
        $alerts->errors = session('errors');
    }
    if (session('error')) {
        $alerts->error = session('error');
    }
    if (session('notice')) {
        $alerts->notice = session('notice');
    }
    if (session('warning')) {
        $alerts->success = session('warning');
    }
    if (session('success')) {
        $alerts->success = session('success');
    }
@endphp
@if ( isset($alerts->success) )
    <x-alert type="{{\App\View\Components\Alert::TYPE['SUCCESS']}}" :body="$alerts->success"></x-alert>
@endif
@if ( isset($alerts->notice) )
    <x-alert type="{{\App\View\Components\Alert::TYPE['NOTICE']}}" :body="$alerts->notice"></x-alert>
@endif
@if ( isset($alerts->warning) )
    <x-alert type="{{\App\View\Components\Alert::TYPE['WARNING']}}" :body="$alerts->warning"></x-alert>
@endif
@if ( isset($alerts->error) )
    <x-alert type="{{\App\View\Components\Alert::TYPE['ERROR']}}" :body="$alerts->error"></x-alert>
@endif
@if ( isset($alerts->errors) )
    <x-alert type="{{\App\View\Components\Alert::TYPE['ERROR']}}" :body="$alerts->errors"></x-alert>
@endif
