@props([
    'name' => null,
    'label' => null,
    'id' => null,
    'min' => 0,
    'max' => 10,
    'value' => null,
    'step' => 1,
    'prefix' => '',
    'suffix' => '',
    'hideMinMax' => false,
])

@php
    $id = $id ?? $name;                                 // if id is not set, use name as id
    $label = $label ?? Str::headline($name);            // if label is not set, use name as label
    $value = $value ?? $max;                            // if value is not set, use max as value
    $model =  $attributes->wire('model')->value();      // get the value of the wire:model attribute
@endphp

<div
    @if($model)
        x-data="{ value: @entangle($model) }"
    @else
        x-data="{ value: {{ $value }} }"
    @endif
    {{ $attributes->except('wire:model') }}>
    <div class="flex justify-between items-start">
        <label for="{{ $id }}"
               class="text-sm font-medium text-zinc-800 dark:text-white">
            {{ $label }}
        </label>
        <span class="text-xs bg-zinc-100 dark:bg-zinc-700 px-2 py-1 rounded">{{ $prefix }}
            <span x-text="value"></span> {{ $suffix }}
        </span>
    </div>
    <input type="range"
           class="w-full accent-zinc-500"
           id="{{ $id }}"
           name="{{ $name }}"
           min="{{ $min }}"
           max="{{ $max }}"
           step="{{ $step }}"
           x-bind:value="value"
           @input="value = $event.target.value"
    />
    @if (!$hideMinMax)
        <div class="flex justify-between text-xs mt-1">
            <span class="px-2 py-1 rounded bg-zinc-100 dark:bg-zinc-700">{{ $prefix }} {{ $min }} {{ $suffix }}</span>
            <span class="px-2 py-1 rounded bg-zinc-100 dark:bg-zinc-700">{{ $prefix }} {{ $max }} {{ $suffix }}</span>
        </div>
    @endif
</div>

@once
    @push('styles')
        <style>
            input[type=range] {
                accent-color: var(--color-accent-content);
            }
        </style>
    @endpush
@endonce
