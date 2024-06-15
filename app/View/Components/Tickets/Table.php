<?php

namespace App\View\Components\Tickets;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public object $tickets,
        public bool $showView = true,
        public bool $showEdit = true,
        public bool $showDelete = true,
        public bool $showStatus = true,
        public bool $showTotal = false,
        public bool $showRemoveFromCart = true,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tickets.table');
    }
}
