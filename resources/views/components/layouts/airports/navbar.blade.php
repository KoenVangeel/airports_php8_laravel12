{{-- only visible for guests --}}
@guest
    <flux:button.group id="auth-buttons">
        <flux:button icon="user" href="{{ route('login') }}">Login</flux:button>
        <flux:button icon="user-plus" href="{{ route('register') }}">Register</flux:button>
    </flux:button.group>
@endguest

{{-- visible for all --}}
<flux:navlist variant="outline">
    <flux:navlist.item icon="cloud" href="{{ route('arrivals') }}">Current Weather</flux:navlist.item>
    <flux:navlist.item icon="clock" href="{{ route('departures') }}">Live Departures</flux:navlist.item>
    <flux:navlist.item icon="paper-airplane" href="{{ route('boarding') }}">Currently Boarding</flux:navlist.item>
    <flux:navlist.item icon="plane-takeoff" href="#">Search and compare Flights</flux:navlist.item>
    <flux:navlist.item icon="calendar" href="{{ route('calendar') }}">Flights Calendar View</flux:navlist.item>
    <flux:separator variant="subtle"/>
    <flux:navlist.item icon="envelope" href="{{ route('contact') }}">Contact</flux:navlist.item>
    <flux:navlist.item icon="wrench-screwdriver" href="{{ route('playground') }}">Playground</flux:navlist.item>
</flux:navlist>

{{-- only visible for authenticated users --}}
@auth
    <flux:navlist variant="outline">
        <flux:navlist.item icon="ticket" href="#">Online Check-in</flux:navlist.item>
    </flux:navlist>
    {{-- only visible for site administartors --}}
    @if (auth()->user()->admin)
        <flux:separator variant="subtle"/>
        <flux:navlist.group expandable heading="Admin">
            <flux:navlist.item href="{{ route('admin.airportstatuses') }}">Airportstatuses</flux:navlist.item>
            <flux:navlist.item href="{{ route('admin.seatclasses') }}">Seatclasses</flux:navlist.item>
            <flux:navlist.item href="{{ route('admin.flightstatuses') }}">Flightstatuses</flux:navlist.item>
            <flux:separator variant="subtle"/>
            <flux:navlist.item href="{{ route('admin.airports') }}">Airports</flux:navlist.item>
            <flux:navlist.item href="#">Airlines</flux:navlist.item>
            <flux:navlist.item href="#">Flights</flux:navlist.item>
            <flux:navlist.item href="#">Passengers</flux:navlist.item>
        </flux:navlist.group>
    @endif
@endauth
