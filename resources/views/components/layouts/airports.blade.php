<!DOCTYPE html>
{{-- kleuren voor app in resources/css/app.css --}}
<html lang="{{ config('app.locale', 'en') }}">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airports {{ isset($title) ? ': ' . strtolower($title) : '' }}</title>
    <meta name="description" content="{{ $description ?? 'Welcome to Airports' }}">
    <x-layouts.airports.favicons/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @stack('styles')
</head>
<body class="min-h-screen bg-white antialiased dark:bg-zinc-900 dark:text-white">
    {{-- Sidebar section --}}
    <flux:sidebar sticky collapsible="mobile"
                  class="bg-zinc-50/80 dark:bg-zinc-900/80 border-r border-zinc-200 dark:border-zinc-700 backdrop-blur-xs">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>
        <a href="{{ route('home') }}" class="mr-5 flex items-center space-x-2">
            <x-app-logo class="size-8" href="#"></x-app-logo>
        </a>
        <flux:separator variant="subtle"/>
        {{-- Navbar at the top of the sidebat --}}
        <x-layouts.airports.navbar/>
        <flux:spacer/>
        {{-- Profile section on the bottom of the sidebar --}}
        <x-layouts.airports.profile/>
    </flux:sidebar>

    {{-- Open/close sidebar on mibile --}}
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left"/>
    </flux:header>

    {{-- Main section --}}
    <flux:main class="max-w-[1600px]">
        <div class="flex items-center justify-between h-10">
            <flux:heading size="xl" level="1">{{ $title ?? "Welcome to Airports" }}</flux:heading>
            <x-layouts.airports.toggle_mode/>
        </div>
        <flux:separator variant="subtle" class="mt-2 mb-4"/>
        {{ $slot }}
    </flux:main>

    {{-- Info section --}}
    <x-layouts.airports.info/>

    <x-itf.notifications />
    @fluxScripts
    @stack('scripts')
</body>
</html>
