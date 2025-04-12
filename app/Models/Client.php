<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        "calendar_url",
        "name",
        "address",
        "user_id",
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, "client_id");
    }
}
