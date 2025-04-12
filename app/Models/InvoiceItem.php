<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        "use_tva",
        "title",
        "unit_price",
        "unit",
        "unit_price",
        "amount",
        "invoice_id",
        "description"
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, "invoice_id");
    }
}
