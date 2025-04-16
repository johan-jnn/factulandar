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
    public static function ensureInvoiceManagedByUser(Invoice $invoice)
    {
        SocietyController::ensureUserHasSociety($invoice->society);
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
            'period_start' => 'required|date',
            'period_end' => 'required|date',
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

        return view('pages.dashboard.client.invoices.show', [
            'invoice' => $invoice
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client, Invoice $invoice)
    {
        ClientController::ensureUserHasClient($client);

        if ($invoice->validated)
            return redirect()
                ->action(
                    [self::class, 'show'],
                    [
                        'client' => $client,
                        'invoice' => $invoice
                    ]
                )
                ->with([
                    'message' => 'La facture a été validée. Elle ne peut plus être modifiée.'
                ]);

        return view('pages.dashboard.client.invoices.edit', [
            'invoice' => $invoice
        ]);
    }

    public function update(Request $request, Client $client, Invoice $invoice)
    {
        InvoiceController::ensureInvoiceManagedByUser($invoice);
        $new_invoice_informations = $request->validate([
            'validated' => 'boolean'
        ]);
        $invoice->update($new_invoice_informations);

        return redirect()
            ->action([self::class, 'show'], [
                'client' => $client,
                'invoice' => $invoice
            ])
            ->with([
                'message' => 'La modification a bien été apporté'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client, Invoice $invoice)
    {
        InvoiceController::ensureInvoiceManagedByUser($invoice);
        $invoice->delete();
        return to_route('invoices.index', [
            'client' => $client
        ])
            ->with([
                'message' => "La facture n°{$invoice->number()} a bien été supprimé."
            ]);
    }
}
