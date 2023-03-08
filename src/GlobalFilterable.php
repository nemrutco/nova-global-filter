<?php

namespace Nemrutco\NovaGlobalFilter;

use Illuminate\Database\Eloquent\Builder;

trait GlobalFilterable
{
    public function globalFiltered($request, $model, $filters = [])
    {

        $model = $model instanceof Builder ? $model : (new $model)->newQuery();

        if ($request->has('filters')) {

            //dd(json_decode($request->filters, true));
            $request->range = optional($request)->range ?? 3600;

            foreach (json_decode($request->filters, true) as $filter => $value) {
                if (empty($value)) {
                    continue;
                }

                $model = (new $filter)->apply($request, $model, $value);

            }
        }else {
            foreach ($filters as $filter) {
                $currentFilter = new $filter;
                
                if(!empty($currentFilter->default())) {
                    $model = $currentFilter->apply($request, $model, $currentFilter->default());
                }
            }
        }
        return $model;
    }
}
