<?php

namespace App\View\Components\Seats;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterCard extends Component
{
    public array $listRows;
    /**
     * Create a new component instance.
     */
    public function __construct(
        public array $rows,
        public string $filterAction,
        public string $resetUrl,
        public ?string $row = null
    )
    {
        $this->listRows = (array_merge([null => 'Any Row'], $rows));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.seats.filter-card');
    }
}
