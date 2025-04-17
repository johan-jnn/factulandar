<?php

namespace App\Policies;

use App\Models\Society;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SocietyPolicy
{
    /**
     * Determine if the user can manage the society
     */
    public function manage(User $user, Society $society)
    {
        return $society->owner->id === $user->id ? Response::allow() : Response::denyAsNotFound();
    }
}
