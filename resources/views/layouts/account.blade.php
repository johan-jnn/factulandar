@extends('layouts.app', [
    'title' => $title ?? $user->name,
])

@section('head')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <x-use-toasts />
    @isset($message)
        <x-toast text="{{ $message }}" />
    @endisset
    @error('message')
        <x-toast text="{{ $message }}" type="error" />
    @enderror
@endsection

@section('body')
    <main>
        @yield('before_page')

        <x-tabs :tabs="[
            [
                'label' => 'Mon compte',
                'url' => route('account'),
            ],
            [
                'label' => 'Mes sociétés',
                'url' => route('account_societies'),
            ],
        ]">
            @yield('page')
        </x-tabs>
    </main>
@endsection
