<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Gate;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InvoiceItemController
{
    private function to_edit(Invoice $invoice)
    {
        return to_route('invoices.edit', [
            'client' => $invoice->client,
            'invoice' => $invoice
        ]);
    }

    public function store_blank(Request $request, Invoice $invoice)
    {
        $item_data = [
            'title' => "",
            'unit' => "u",
            'amount' => 1,
            'unit_price' => "0",
            'invoice_id' => $invoice->id
        ];
        InvoiceItem::create($item_data);

        return $this->to_edit($invoice);
    }

    public function updateAll(Request $request, Invoice $invoice)
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

        $items = collect($items_entries['items'])->map(function ($data, $id) use ($invoice) {
            /**
             * @var InvoiceItem|null
             */
            $item = $invoice->items()->find($id);
            Gate::denyIf(fn(User $user) => $item === null);

            $item->update($data);
            return $item;
        });

        return $this->to_edit($invoice)->with([
            'message' => "Les éléments ont bien été modifiés."
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Invoice $invoice)
    {
        // return $this->to_edit($invoiceItem->invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice, InvoiceItem $item)
    {
        return $this->to_edit($item->invoice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice, InvoiceItem $item)
    {
        $item->delete();
        return $this->to_edit($item->invoice);
    }
}
