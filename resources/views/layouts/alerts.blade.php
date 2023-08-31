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
    <x-alerts type="{{\App\View\Components\Alerts::TYPE['SUCCESS']}}" :body="$alerts->success"></x-alerts>
@endif
@if ( isset($alerts->notice) )
    <x-alerts type="{{\App\View\Components\Alerts::TYPE['NOTICE']}}" :body="$alerts->notice"></x-alerts>
@endif
@if ( isset($alerts->warning) )
    <x-alerts type="{{\App\View\Components\Alerts::TYPE['WARNING']}}" :body="$alerts->warning"></x-alerts>
@endif
@if ( isset($alerts->error) )
    <x-alerts type="{{\App\View\Components\Alerts::TYPE['ERROR']}}" :body="$alerts->error"></x-alerts>
@endif

@if ( isset($alerts->errors) )
    <x-alerts type="{{\App\View\Components\Alerts::TYPE['ERROR']}}" :body="$alerts->errors"></x-alerts>
@endif
