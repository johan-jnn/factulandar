<?php

namespace App;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use stdClass;

class EventSelectorHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected readonly Invoice $invoice)
    {
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'events' => 'required|array',
            'events.*.title' => 'required|string',
            'events.*.description' => 'string',
            'events.*.amount' => 'required|decimal:0,2|min:0',
            'events.*.unit' => 'required|string|max:5',
            'events.*.unit_price' => 'required|decimal:0,2|min:0',
            'events.*.tav_ratio' => 'decimal:0,2|min:0',
        ]);

        return $this->invoice->items()->createMany($validated['events']);
    }
    public function set(Request $request)
    {
        $this->invoice->items()->delete();
        return $this->add($request);
    }
}
