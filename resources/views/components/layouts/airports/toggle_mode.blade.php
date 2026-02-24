<flux:button id="toggle-mode" x-data x-on:click="$flux.dark = ! $flux.dark" variant="subtle" square class="group">
    <flux:icon.sun x-show="$flux.appearance === 'light'" variant="mini" class="text-zinc-500 dark:text-white" />
    <flux:icon.moon x-show="$flux.appearance === 'dark'" variant="mini" class="text-zinc-500 dark:text-white" />
    <flux:icon.moon x-show="$flux.appearance === 'system' && $flux.dark" variant="mini" />
    <flux:icon.sun x-show="$flux.appearance === 'system' && ! $flux.dark" variant="mini" />
</flux:button>
