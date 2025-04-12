<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * List all the user's clients
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Client, User>
     */
    public function clients()
    {
        return $this->hasMany(Client::class, "user_id");
    }

    /**
     * List all the user's societies
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Society, User>
     */
    public function societies()
    {
        return $this->hasMany(Society::class, "owner_id");
    }
}
