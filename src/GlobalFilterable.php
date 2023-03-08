<?php

namespace Nemrutco\NovaGlobalFilter;

use Illuminate\Database\Eloquent\Builder;

trait GlobalFilterable
{
    public function globalFiltered($request, $model, $filters = [])
    {

        $model = $model instanceof Builder ? $model : (new $model)->newQuery();

        // manage filters changed on UI
        if ($request->has('filters')) {

            //dd(json_decode($request->filters, true));
            $request->range = optional($request)->range ?? 3600;

            $requestFilters = array_keys(json_decode($request->filters, true));

            foreach (json_decode($request->filters, true) as $filter => $value) {
                if (empty($value)) {
                    continue;
                }

                $model = (new $filter)->apply($request, $model, $value);

            }

            // need to apply default filters if they are not changed on UI
            foreach ($filters as $filter) {
                $currentFilter = new $filter;

                if (!empty($currentFilter->default()) && !in_array(get_class($currentFilter), $requestFilters)) {
                    $model = $currentFilter->apply($request, $model, $currentFilter->default());
                }
            }

        } // manage default filter values (no filter changed on UI still)
        else {
            foreach ($filters as $filter) {
                $currentFilter = new $filter;

                if (!empty($currentFilter->default())) {
                    $model = $currentFilter->apply($request, $model, $currentFilter->default());
                }
            }
        }
        return $model;
    }
}
