<x-layouts.airports title="">
    <div class="grid grid-rows-2 grid-flow-col gap-4">
        <p class="row-span-2 text-red-700 dark:text-red-300 text-5xl text-right border-r-2 border-zinc-200 dark:border-b-zinc-700 pr-4">
            @yield('code')
        </p>
        <p class="text-2xl font-light text-zinc-400 dark:text-zinc-300">
            @yield('message')
        </p>
        <div>
            <flux:button variant="primary" icon="home" href="{{ route('home') }}">Home</flux:button>
            <flux:button variant="primary" href="#" icon="arrow-uturn-left" onclick="window.history.back();">Back</flux:button>
        </div>
    </div>
    @push('scripts')
        <script>
            document.getElementById('toggle-mode').remove();
            document.getElementById('auth-buttons').remove();
        </script>
    @endpush
</x-layouts.airports>
