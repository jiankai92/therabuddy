<li class="text-left">
    <div class="pb-3.5">
        <span class="text-xs">Therabuddy</span>
        <span class="text-xs">{{$time}}</span>
    </div>
    {{--! Adding spacing for the div below will be treated as newline due whitespace-pre-line handling--}}
    <div class="text-base text-white rounded bg-neutral-500 mb-7 p-5 leading-6 w-11/12 whitespace-pre-line">{{ html_entity_decode($message) }}</div>
</li>