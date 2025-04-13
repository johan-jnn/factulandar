@extends('layouts.app', [
    'title' => $title ?? $user->name,
])

@section('head')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <x-use-toasts />

    @if (session('message'))
        <x-toast :text="session('message')" />
    @endif
    @error('message')
        <x-toast :text="$message" type="error" />
    @enderror
@endsection

@section('body')
    <main>
        @yield('before_page')

        <x-tabs :tabs="[
            [
                'label' => 'Mon compte',
                'url' => route('user.edit'),
            ],
            [
                'label' => 'Mes sociétés',
                'url' => route('societies.index'),
            ],
        ]">
            @yield('page')
        </x-tabs>
    </main>
@endsection
