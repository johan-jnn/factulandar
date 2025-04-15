<?php

namespace App\Http\Controllers;

use App\EventSelectorHandler;
use App\Models\Client;
use App\Models\Invoice;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InvoiceController
{
    public static function EnsureInvoiceManagedByUser(Invoice $invoice)
    {

    }

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
        ClientController::ensureUserHasClient($client);

        $user = Auth::user();
        $invoice_data = $request->validate([
            'society_id' => [
                'required',
                Rule::exists('societies', 'id')->where('owner_id', $user->id)
            ],
            'tav_ratio' => 'required|decimal:0,2',
            'name' => 'nullable|string|max:100',
        ]);
        $invoice_data['client_id'] = $client->id;

        $created_invoice = Invoice::create($invoice_data);
        new EventSelectorHandler($created_invoice)->add($request);

        // Change the client's prefered society to the one created the invoice
        $client->prefered_society = $created_invoice->society_id;
        $client->save();

        return to_route('invoices.edit', [
            'client' => $client,
            'invoice' => $created_invoice
        ])
            ->with([
                'message' => "Votre facture a bien été créée."
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client, Invoice $invoice)
    {
        ClientController::ensureUserHasClient($client);

        return view('pages.dashboard.client.invoices.edit', [
            'invoice' => $invoice
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client, Invoice $invoice)
    {
        ClientController::ensureUserHasClient($client);

        return view('pages.dashboard.client.invoices.edit', [
            'invoice' => $invoice
        ]);
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
