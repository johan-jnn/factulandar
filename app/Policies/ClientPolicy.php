<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientPolicy
{
    /**
     * Determine if the user can manage the society
     */
    public function manage(User $user, Client $client)
    {
        return $client->user->id === $user->id ? Response::allow() : Response::denyAsNotFound();
    }
}
