<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function updateAll(Request $request)
    {
        $items_entries = $request->validate([
            'items' => 'required|array',
            'items.*.title' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.amount' => 'required|decimal:0,2|min:0',
            'items.*.unit' => 'required|string|max:5',
            'items.*.unit_price' => 'required|decimal:0,2|min:0',
            'items.*.tav_ratio' => 'nullable|decimal:0,2|min:0',
        ]);
        $items = collect($items_entries['items'])->map(function ($data, $id) {
            /**
             * @var InvoiceItem
             */
            $item = InvoiceItem::find($id);
            InvoiceItemController::userHasAccessToItem($item);
            
            $item->update($data);
            return $item;
        });

        return $this->to_edit($items->first()->invoice)->with([
            'message' => "Les éléments ont bien été modifiés."
        ]);
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
