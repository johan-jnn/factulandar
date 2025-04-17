<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Gate;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
{
    /**
     * Determine if the user can manage the society
     */
    public function manage(User $user, Invoice $invoice)
    {
        return Gate::inspect('manage', $invoice->society);
    }
}
