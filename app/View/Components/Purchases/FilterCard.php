<?php

namespace App\View\Components\Purchases;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterCard extends Component
{

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $filterAction,
        public string $resetUrl,
        public bool $showKeyword=false,
        public ?string $keyword = null,
        public ?string $before = null,
        public ?string $after = null
    )
    {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.purchases.filter-card');
    }
}
