@extends('layouts.dashboard', [
    'page' => 'Nouvelle facture',
    'client' => $invoice->client,
    'clientClickable' => true
])

@section('body')
    
@endsection
