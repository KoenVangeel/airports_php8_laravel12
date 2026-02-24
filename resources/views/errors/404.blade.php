{{-- php artisan vendor:publish --tag=laravel-errors --}}
{{-- resources/views/errors/minimal.blade.php --}}

@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found'))
