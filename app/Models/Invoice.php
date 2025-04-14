<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        "use_tva",
        "society_id",
        "client_id",
        "name"
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, "invoice_id");
    }

    public function number()
    {
        return "{$this->society_id}-{$this->client_id}-{$this->id}";
    }
}
