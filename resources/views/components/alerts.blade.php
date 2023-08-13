@php
    $class_list = match ($type) {
        \App\View\Components\Alerts::TYPE['SUCCESS'] => 'bg-green-500',
        \App\View\Components\Alerts::TYPE['ERROR'] => 'bg-red-500',
        default => 'bg-blue-500'
    };
@endphp
<template x-data="{ show: true }" x-if="show">
    <div x-init="fadeOut($el)"
         class="fixed w-full z-[1] p-4 text-base text-white transition-opacity opacity-100 {{$class_list}}">
        @if(is_object($body))
            @foreach ($body->all() as $entry)
                <li>{{ $entry }}</li>
            @endforeach
        @else
            {!! nl2br($body) !!}
        @endif
    </div>
</template>
@push('scripts2')
    <script>
        async function fadeOut(elem) {
            setTimeout(function () {
                elem.classList.remove('opacity-100');
                elem.classList.add('opacity-0')
                setTimeout(function () {
                    elem.remove();
                }, 100);
            }, 2500)
        }
    </script>
@endpush