<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Society extends Model
{
    protected $fillable = [
        "name",
        "address",
        "owner_id",
        "paiement_terms"
    ];
}
