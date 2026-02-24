@props([
    'sorted' => false,
    'direction' => 'asc',
    'position' => null,
])

@php
    $position = match($position) {
        'center' => 'justify-center ml-5',
        'right' => 'justify-end',
        default => '',
    };
@endphp

<th role="button" {{ $attributes->merge(['class' => 'cursor-pointer']) }}>
    <div class="{{ $position }} flex items-center gap-1 ">
        <span>{{ $slot }}</span>
        @if($sorted && $direction === 'asc')
            <flux:icon.chevron-up variant="micro"/>
        @elseif($sorted && $direction === 'desc')
            <flux:icon.chevron-down variant="micro"/>
        @else
            <flux:icon.chevron-up-down variant="micro" class="opacity-50"/>
        @endif
    </div>
</th>
