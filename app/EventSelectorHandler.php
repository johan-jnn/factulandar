<?php

namespace App;

use App\Models\Invoice;
use Illuminate\Http\Request;

class EventSelectorHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected readonly Invoice $invoice)
    {
    }

    public function add(Request $request) {
        // dd($request->input('groups'), $request->input('events'));
        $validated = $request->validate([
            'events' => 'array|present_if:groups,null',
            'groups' => 'array|present_if:events,null',
        ]);

        $items= [];

        if(isset($validated['groups'])) {
            foreach($validated['groups'] as $value) {

            }
        }else {
            
        }



        dd($validated);
    }
    public function set(Request $request) {
        $this->invoice->items()->delete();
        return $this->add($request);
    }
}
