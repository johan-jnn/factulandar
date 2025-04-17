<?php

namespace App\Models;

use Http;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        "calendar_url",
        "name",
        "address",
        "user_id",
        "prefered_society",
        "prefered_hours_price"
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, "client_id");
    }

    public function calendar()
    {
        $json = Http::get('https://ical.mathieutu.dev/json', [
            'url' => $this->calendar_url,
        ]);
        abort_if(!$json->ok(), $json->status());
        return $json->json();
    }

    public function nullable_calendar()
    {
        try {
            return $this->calendar();
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
