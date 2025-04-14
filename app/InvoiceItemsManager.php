<?php

namespace App;

use App\Models\Invoice;
use DB;
use Request;

class InvoiceItemsManager
{
    /**
     * Create a new class instance.
     */
    public function __construct(public Invoice $invoice)
    {
    }

    public function fromEventSelectorForm(Request $request, bool $override = false)
    {

    }

    public function addItems()
    {

    }

    public function setItems()
    {

    }

    public function clearItems()
    {
        DB::table('invoice_items')->where('invoice_id', $this->invoice->id)->delete();
    }
}
