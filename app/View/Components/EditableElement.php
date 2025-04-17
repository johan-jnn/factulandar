<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditableElement extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $element = "span", public string $model, public string $label, public string $value = '$el.innerText.value', public ?bool $editable = true, public ?string $placeholder = null, public ?string $initVal = '""', public ?string $text = null)
    {
        $this->placeholder ??= $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.editable-element');
    }
}
