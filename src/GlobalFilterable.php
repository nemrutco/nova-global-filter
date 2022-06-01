<?php

namespace Nemrutco\NovaGlobalFilter;

use Illuminate\Database\Eloquent\Builder;

trait GlobalFilterable
{
    public function globalFiltered($model, $filters = [])
    {
        $request = request();

        $model = $model instanceof Builder ? $model : (new $model)->newQuery();

        if ($request->has('filters')) {

            $request->range = optional($request)->range ?? 3600;

            $filtersByKeys = [];
            $filtersKeys = [];
            foreach ($filters as $filter) {
                $currentFilter = gettype($filter) === 'string' ? new $filter : $filter;
                $key = $currentFilter->key() ?? $currentFilter::class;
                $filtersByKeys[$key] = $currentFilter;
                $filtersKeys[] = $key;
            }
            foreach (json_decode($request->filters, true) as $filter => $value) {
                if (empty($value) || !in_array($filter, $filtersKeys)) {
                    continue;
                }

                $model = $filtersByKeys[$filter]->apply($request, $model, $value);
            }
        }else {
            foreach ($filters as $filter) {
                $currentFilter = gettype($filter) === 'string' ? new $filter : $filter;

                if(!empty($currentFilter->default())) {
                    $model = $currentFilter->apply($request, $model, $currentFilter->default());
                }
            }
        }

        return $model;
    }
}
