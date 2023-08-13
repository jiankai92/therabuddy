<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alerts extends Component
{
    const TYPE = [
        'ERROR' => 'error',
        'SUCCESS' => 'success',
        'NOTICE' => 'notice',
    ];
    /**
     * Create a new component instance.
     */
    public function __construct(public string $type, public mixed $body)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alerts');
    }
}
