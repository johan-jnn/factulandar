<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceItemController
{
    public static function userHasAccessToItem(InvoiceItem $invoiceItem)
    {
        InvoiceController::ensureInvoiceManagedByUser($invoiceItem->invoice);
    }

    private function to_edit(Invoice $invoice)
    {
        return to_route('invoices.edit', [
            'client' => $invoice->client,
            'invoice' => $invoice
        ]);
    }

    public function store_blank(Request $request)
    {
        $item_data = $request->validate([
            'invoice_id' => 'required|exists:invoices,id'
        ]);
        /**
         * @var Invoice
         */
        $invoice = Invoice::find($item_data['invoice_id']);
        InvoiceController::ensureInvoiceManagedByUser($invoice);

        $item_data = [
            ...$item_data,
            'title' => "Titre de facture",
            'unit' => "u",
            'amount' => 1,
            'unit_price' => "0"
        ];
        InvoiceItem::create($item_data);

        return $this->to_edit($invoice);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $this->to_edit($invoiceItem->invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InvoiceItem $item)
    {
        return $this->to_edit($item->invoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceItem $item)
    {
        InvoiceItemController::userHasAccessToItem($item);
        $item->delete();
        return $this->to_edit($item->invoice);
    }
}
