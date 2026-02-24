@props([
    'variant' => null,
    'title' => null,
    'icon' => null,
    'dismissible' => false,
    'selfDestruct' => 0,        // attribute self-destruct must be written as selfDestruct!
])

@php
    $color = match ($variant) {
        'success' => 'border-emerald-200 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-100',
        'error' => 'border-red-200 dark:border-red-700 bg-red-50 dark:bg-red-900/50 text-red-800 dark:text-red-100',
        'warning' => 'border-yellow-200 dark:border-yellow-700 bg-yellow-50 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-100',
        'info' => 'border-blue-200 dark:border-blue-700 bg-blue-50 dark:bg-blue-900/50 text-blue-800 dark:text-blue-100',
        default => 'border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800',
    };
    $color = "p-3 my-4 rounded-xl flex items-start gap-4 border $color";
@endphp

<div
    x-data="{ show: true }"
    @if ($selfDestruct > 0)
        x-init="setTimeout(() => show = false, {{ $selfDestruct }})"
    @endif
    x-show="show"
    x-transition.duration.300ms
    {{ $attributes->merge(['class' => $color]) }}>
    @if ($icon)
        <flux:icon :icon="$icon" variant="outline"/>
    @endif
    <div class="flex-1">
        @if ($title)
            <h3 class="font-bold mb-2">{{ $title }}</h3>
        @endif
        {{ $slot }}
    </div>
    @if ($dismissible)
        <flux:icon.x-circle variant="solid" class="cursor-pointer" @click="show = false"/>
    @endif
</div>
