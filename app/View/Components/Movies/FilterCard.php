<?php

namespace App\View\Components\Movies;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterCard extends Component
{
    public array $listGenres;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public array $genres,
        public string $filterAction,
        public string $resetUrl,
        public ?string $title = null,
        public ?string $genre = null,
    )
    {
        $this->listGenres = (array_merge([null => 'Any genre'], $genres));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.movies.filter-card');
    }
}
