@php
    /**
     * @var App\Models\User
     */
    $user = Auth::user();
@endphp

@extends('layouts.dashboard', [
    'page' => 'Clients',
])
