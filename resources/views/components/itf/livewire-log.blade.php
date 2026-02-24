@php

    //******************************
    // te gebruiken met <x-itf.livewire-log :users="$users" /> in de view van de livewire
    // ZIE playground.blade.php
    //******************************

    // Configuration: Set to 'false' to allow the log component even when app.debug is false
    $onlyDebugMode = true;

    // Safety Check: Only render this component if app.debug is true (or if $onlyDebugMode is false)
    // This prevents accidentally exposing debug info in production.
    if(!config('app.debug') && $onlyDebugMode) return;

    // Initialize an array to hold the data we want to display
    $output = [];
    // Define prefixes for variables we want to ignore (internal Laravel/Livewire/Blade variables)
    $prefixes = ['__', 'app', 'errors', 'attributes', 'slot', 'output', 'prefixes', 'show', 'onlyDebugMode', 'sidebar', 'console', 'consoleJson'];
    // Get all variables currently defined in the scope where this component is included
    $vars = get_defined_vars();

    // Iterate through all defined variables
    foreach($vars as $key => $value) {
        // Check if the variable key starts with any of the ignored prefixes
        $ignore = false;
        foreach ($prefixes as $prefix) {
            if (str_starts_with($key, $prefix)) {
                $ignore = true;
                break; // Stop checking prefixes for this key if one matches
            }
        }

        // If the key should not be ignored...
        if (!$ignore) {
            // Livewire components pass their public properties within the '_instance' variable.
            // Check if this is the '_instance' (the Livewire component itself)
            if ($key === '_instance') {
                // Only add public properties if the component has any (check if encoding is not empty object/array)
                if (json_encode($value) !== '{}' && json_encode($value) !== '[]') {
                    $output['public properties'] = $value; // Store the component instance under 'public properties'
                }
            } else {
                // For any other variable not ignored, add it directly to the output array
                $output[$key] = $value;
            }
        }
     }
@endphp

<div class="fixed top-2 right-2 z-[1000]">
    <flux:modal.trigger name="livewire-log">
        <flux:button icon="bug-ant" size="xs" variant="danger"/>
    </flux:modal.trigger>

    <flux:modal name="livewire-log" variant="flyout" class="w-[500px] bg-white/75 backdrop-blur-sm">
        <flux:heading size="lg">Livewire properties</flux:heading>
        <div class="overflow-auto">
            @foreach(array_reverse($output) as $key => $value)
                <div x-data="{show: false}" x-cloak>
                    <hr class="my-2 border-slate-500">
                    <button @click="show = !show" class="w-full flex items-center cursor-pointer gap-2">
                        <span class="text-xl transition-all block"
                              :class="show ? 'rotate-45' : ''">+</span>
                        <p class="text-sm italic cursor-pointer">{{ $key }}</p>
                    </button>
                    <pre class="text-sm overflow-x-auto bg-slate-100 dark:bg-slate-600 p-2 rounded" x-show="show"
                         x-transition>{{ json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endforeach
        </div>
    </flux:modal>
</div>
