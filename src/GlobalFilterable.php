<?php

namespace Nemrutco\NovaGlobalFilter;

use Illuminate\Database\Eloquent\Builder;

trait GlobalFilterable
{
    public function globalFiltered($model, $filters = [])
    {
        $request = request();
        if ($request->has('filters')) {
            $request->range = optional($request)->range ?? 3600;

            $model = $model instanceof Builder ? $model : (new $model)->newQuery();

            foreach (json_decode($request->filters, true) as $filter => $value) {
                if(in_array($filter, $filters)){
                  $model = (new $filter)->apply($request, $model, $value);
                }
            }
        }
        return $model;
    }
}
