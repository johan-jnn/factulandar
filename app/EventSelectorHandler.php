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
            'items' => 'required|array',
            'items.*.title' => 'required|string',
            'items.*.description' => 'nullable|string',
            'items.*.amount' => 'required|decimal:0,2|min:0',
            'items.*.unit' => 'required|string|max:5',
            'items.*.unit_price' => 'required|decimal:0,2|min:0',
            'items.*.tav_ratio' => 'nullable|decimal:0,2|min:0',
        ]);

        return $this->invoice->items()->createMany($validated['items']);
    }
    public function set(Request $request)
    {
        $this->invoice->items()->delete();
        return $this->add($request);
    }
}
