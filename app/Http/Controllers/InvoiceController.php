<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InvoiceController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Client $client)
    {
        ClientController::ensureUserHasClient($client);
        $invoices = $client->invoices();
        return view('pages.dashboard.client.invoices.index', [
            'client' => $client,
            'invoices' => $invoices
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Client $client)
    {
        ClientController::ensureUserHasClient($client);

        try {
            $calendar = $client->calendar();
        } catch (\Throwable $th) {
            return to_route('clients.edit', [
                'client' => $client
            ])->withErrors([
                        'message' => "Le calendrier de ce client n'est pas valide.",
                        'calendar_url' => "Invalid calendar URL. Please verify it."
                    ]);
        }

        return view('pages.dashboard.client.invoices.create', [
            'client' => $client,
            'calendar' => $calendar
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Client $client)
    {
        $user = Auth::user();

        $invoice_data = $request->validate([
            'society_id' => [
                'required',
                'society_id' => Rule::exists('societies')->where('owner_id', $user->id)
            ],
            'events' => 'array',
            'use_tva' => 'required|boolean'
        ]);
        $invoice_data['client_id'] = $client->id;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
