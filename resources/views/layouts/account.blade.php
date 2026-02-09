@extends('layouts.app', [
    'title' => $title ?? $user->name,
    'indexURL' => route('app.index'),
])

@section('head')
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
