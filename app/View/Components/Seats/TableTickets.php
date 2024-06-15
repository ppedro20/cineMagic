<?php

namespace App\View\Components\Seats;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableTickets extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public object $seats,
        public object $screening,
        public string $addForm = '',
        public string $removeForm = '',
        public bool $showView = true,
        public bool $showEdit = true,
        public bool $showDelete = true,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.seats.table-tickets');
    }
}
