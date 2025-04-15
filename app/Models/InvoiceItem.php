<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        "tav_ratio",
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
    public function price_ht()
    {
        return $this->unit_price * $this->amount;
    }
    public function price_ttc()
    {
        return $this->price_ht() * (1 + $this->tav());
    }

    public function tav(): float
    {
        return $this->tav_ratio ?? $this->invoice->tav_ratio;
    }
}
