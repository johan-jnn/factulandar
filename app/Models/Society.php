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
        if ($this->invoices_no_format === null) {
            if ($invoice->name)
                return $invoice->name;
            return "{$this->id}-{$invoice->client->id}-{$invoice->id}";
        }

        return "";
    }

    function owner()
    {
        return $this->belongsTo(User::class, "owner_id");
    }
}
