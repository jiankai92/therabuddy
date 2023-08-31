<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    const TYPE = [
        'ERROR' => 'error',
        'SUCCESS' => 'success',
        'NOTICE' => 'notice',
        'WARNING' => 'warning'
    ];
    
    private bool $persist;

    /**
     * Create a new component instance.
     */
    public function __construct(public string $type, public mixed $body)
    {
        $this->persist = $this->persistence();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert', [
                'persist' => $this->persist
            ]
        );
    }

    private function persistence(): bool
    {
        return match ($this->type) {
            self::TYPE['SUCCESS'], self::TYPE['NOTICE'] => false,
            default => true
        };
    }
}
