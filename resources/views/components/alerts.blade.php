@php
    $class_list = match ($type) {
        \App\View\Components\Alerts::TYPE['SUCCESS'] => 'bg-green-500',
        \App\View\Components\Alerts::TYPE['ERROR'] => 'bg-red-500',
        \App\View\Components\Alerts::TYPE['WARNING'] => 'bg-orange-400',
        default => 'bg-blue-500'
    };
@endphp
<template x-data="{ show: true }" x-if="show">
    <div x-data="{}" x-ref="alert" @if(!$persist) x-init="fadeOut($refs.alert)" @endif 
         class="w-full z-[1] p-4 text-base text-white transition-opacity opacity-100 {{$class_list}}">
        <div class="flex">
            <div class="w-[99%]">
                @if(is_object($body))
                    @foreach ($body->all() as $entry)
                        <li>{{ $entry }}</li>
                    @endforeach
                @else
                    {!! nl2br($body) !!}
                @endif
            </div>
            <div class="w-[1%] flex items-center">
                <i class="fa fa-times cursor-pointer" @click="dismiss($refs.alert)" aria-hidden="true"></i>
            </div>
        </div>
    </div>
</template>
@push('scripts2')
    <script>
        async function fadeOut(elem) {
            setTimeout(function () {
                dismiss(elem)
            }, 2500)
        }
        function dismiss(elem) {
            elem.classList.remove('opacity-100');
            elem.classList.add('opacity-0')
            setTimeout(function () {
                elem.remove();
            }, 100);
        }
    </script>
@endpush