<x-mail::message>

    # Dear {{ $firstname }} {{ $lastname }}

    <p>
        {{ $carrier }} informs us that your flight {{ $flightnumber }}  has been modified.
        The new schedule will be as follows:
    <ul>
        <li>Departure: <b>{{ $etd }}</b></li>
        <li>Arrival: <b>{{ $eta }}</b></li>
    </ul>
    </p>
    <p>
        We sincerely regret the inconveniences these changes may cause.
    </p>


    Best regards,<br>
    {{ config('app.name') }}
</x-mail::message>
