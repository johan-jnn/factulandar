<?php

namespace App\View\Components;

use App\Models\Invoice;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InvoiceRenderer extends Component
{
    public string $contenteditable;
    /**
     * Create a new component instance.
     */
    public function __construct(public Invoice $invoice, public bool $editable = false)
    {
        $this->contenteditable = $editable ? "true" : "false";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.invoice-renderer');
    }
}
