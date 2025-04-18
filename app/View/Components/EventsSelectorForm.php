<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EventsSelectorForm extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $action, public array $calendar, public ?float $hoursPrice = 0)
    {
        // 
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.events-selector-form');
    }
}
