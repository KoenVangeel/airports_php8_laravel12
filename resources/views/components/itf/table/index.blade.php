@props([
    'border' => false,
    'cols' => [],       // e.g. cols="w-12, w-auto, w-[200px]"
])

@php
    $border = $border ? 'border border-zinc-800/10 dark:border-white/20' : '';
    if($cols) {
        $cols = explode(',', $cols);
    }
@endphp
<table {{ $attributes->merge(['class' => "
            $border w-full text-left align-top
            text-sm  text-zinc-500 dark:text-zinc-300
            [&_tr]:border-b [&_tr]:border-zinc-800/10 [&_tr]:dark:border-white/20
            [&_tbody_tr:last-child]:border-b-0 [&_tfoot_tr:last-child]:border-b-0
            [&_th]:border-b-3 [&_th]:border-zinc-800/10 [&_th]:dark:border-white/20
            [&_td]:p-2 [&_th]:p-2
            "]) }}>
    @if($cols)
        <colgroup>
            @foreach($cols as $col)
                <col class="{{ trim($col) }}">
            @endforeach
        </colgroup>
    @endif
    {{ $slot }}
</table>
