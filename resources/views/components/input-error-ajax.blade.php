<template x-if="{{ $fields }} && {{ $fields }}.length > 0">
    <template x-for="item in {{ $fields }}">
        <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1 mt-2']) }}>
            <li x-text="item"></li>
        </ul>
    </template>
</template>
