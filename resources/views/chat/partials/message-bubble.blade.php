<li class="text-right clearfix">
    <div class="align-right pb-3.5">
        <span class="text-xs">Me</span>
        <span class="text-xs">{{$time}}</span> &nbsp; &nbsp;
    </div>
    {{--! Adding spacing for the div below will be treated as newline due whitespace-pre-line handling--}}
    <div class="text-base text-left text-white rounded bg-sky-300 mb-7 p-5 leading-6 w-11/12 float-right whitespace-pre-line">{{ html_entity_decode($message) }}</div>
</li>
