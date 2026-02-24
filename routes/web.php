<?php

use App\Http\Middleware\ActiveUser;
use App\Http\Middleware\Admin;
use App\Livewire\Admin\Airports;
use App\Livewire\Admin\Airportstatuses;
use App\Livewire\Admin\Flightstatuses;
use App\Livewire\Admin\Seatclasses;
use App\Livewire\Arrivals;
use App\Livewire\Boarding;
use App\Livewire\Calendar;
use App\Livewire\Departures;

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::view('playground', 'playground')->name('playground');

Route::view('/', 'home')->name('home');
Route::view('contact', 'contact')->name('contact');
// Route::view('under-construction', 'under-construction')->name('under-construction');

Route::get('arrivals', Arrivals::class)->name('arrivals');
Route::get('boarding', Boarding::class)->name('boarding');
Route::get('departures', Departures::class)->name('departures');
//Route::get('search_flights', SearchFlights::class)->name('search_flights');
//Route::get('check_in', CheckIn::class)->name('check_in');
Route::get('calendar', Calendar::class)->name('calendar');

// php artisan make:middleware Admin
// app/Http/Middleware/Admin.php

Route::middleware(['auth', ActiveUser::class, Admin::class])->prefix('admin')->group(function () {
    Route::redirect('/', '/admin/seatclasses');
    Route::get('airportstatuses', Airportstatuses::class)->name('admin.airportstatuses');
    Route::get('seatclasses', Seatclasses::class)->name('admin.seatclasses');
    Route::get('flightstatuses', Flightstatuses::class)->name('admin.flightstatuses');

    Route::get('airports', Airports::class)->name('admin.airports');
});

/*
Route::middleware(['auth', Admin::class, ActiveUser::class])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/seatclasses');
    Route::get('airportstatuses', Airportstatuses::class)->name('airportstatuses');
    Route::get('seatclasses', Seatclasses::class)->name('seatclasses');
    Route::get('flightstatuses', Flightstatuses::class)->name('flightstatuses');
    Route::get('carriers', Carriers::class)->name('carriers');
    Route::get('airports', Airports::class)->name('airports');
    Route::get('flights', Flights::class)->name('flights');
    Route::get('passengers', Passengers::class)->name('passengers');
});
*/

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', ActiveUser::class])
    ->name('dashboard');

Route::middleware(['auth', ActiveUser::class])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
