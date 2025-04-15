<?php

namespace App;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventSelectorHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected readonly Invoice $invoice)
    {
    }

    public static function eventToInvoiceLineData(array $event)
    {
        // todo - finish validator
        $validator = Validator::make($event, [
            'summary' => 'required|string'
        ]);
        if ($validator->fails())
            throw back()->withErrors($validator);
        $event = $validator->validated();

        $line = [
            'title' => $event['summary'],
            'unit' => 'h',
            'unit_price' => 0,
            'amount' => $event['totalHours'],
            'description' => $event['description']
        ];

        if ($event['grouped']) {
            $used_events_len = count($event['events']);
            $line['description'] .= " | $used_events_len événements";
        }

        return $line;
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'events' => 'array|present_if:groups,null',
            'groups' => 'array|present_if:events,null',
        ]);

        $lines = collect($validated['events'] ?? $validated['groups'])
            ->map(function ($str) {
                $item = json_decode($str);
                return EventSelectorHandler::EventToInvoiceLineData($item);
            });

        $invoice_lines = $this->invoice->items()->createMany($lines);
        return $invoice_lines;
    }
    public function set(Request $request)
    {
        $this->invoice->items()->delete();
        return $this->add($request);
    }
}
