<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        "use_tva",
        "user_id",
        "client_id"
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, "invoice_id");
    }
}
