<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        "tav_ratio",
        "society_id",
        "client_id",
        "name",
        "period_start",
        "period_end"
    ];

    protected $casts = [
        "period_start" => "date",
        "period_end" => "date"
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function number()
    {
        return "{$this->society_id}-{$this->client_id}-{$this->id}";
    }

    public function price_ht()
    {
        return $this->items->sum(fn($line) => $line->price_ht());
    }
    public function price_ttc()
    {
        return $this->items->sum(fn($line) => $line->price_ttc());
    }
}
