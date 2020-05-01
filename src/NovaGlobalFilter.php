<?php

namespace Nemrutco\NovaGlobalFilter;

use Laravel\Nova\Card;

class NovaGlobalFilter extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    protected $filters;
    public $width = '5/6';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'nova-global-filter';
    }


    public function __construct($filters = []) {
        $this->filters = $filters;
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'filters' => collect($this->filters ?? [])->map(function ($filter) {
                return $filter->jsonSerialize();
            })->values()->all(),
        ]);
    }
}
