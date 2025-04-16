<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    protected $fillable = [
        "name",
        "address",
        "owner_id",
        "paiement_terms",
        "invoices_no_format"
    ];

    public function get_invoice_number(Invoice $invoice)
    {
        if ($this->invoices_no_format === null)
            return "{$this->id}-{$invoice->client->id}-{$invoice->id}";
        return "";
    }
}
