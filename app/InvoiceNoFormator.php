<?php

namespace App;

use App\Models\Invoice;
use Exception;

class InvoiceNoFormator
{
    private const reg = '/%(?<obj>[csi]):(?<attr>id|name|created)/m';

    /**
     * Create a new class instance.
     */
    public function __construct(private Invoice $invoice)
    {
        //
    }

    public function all(string $text)
    {
        preg_match_all(InvoiceNoFormator::reg, $text, $matches, PREG_SET_ORDER, 0);

        foreach ($matches as $match) {
            $obj = $match['obj'];
            $attr = $match['attr'];

            switch ($obj) {
                case 'c':
                    $element = $this->invoice->client;
                    break;
                case 's':
                    $element = $this->invoice->society;
                    break;
                case 'i':
                    $element = $this->invoice;
                    break;
                default:
                    continue 2;
            }

            switch ($attr) {
                case 'id':
                    $value = $element->id;
                    break;
                case 'name':
                    $value = $element->name;
                    break;
                case 'created':
                    $value = $element->created_at->format('Ymd');
                    break;
                default:
                    continue 2;
            }

            $text = str_replace($match[0], $value, $text);
        }

        return $text;
    }
}
