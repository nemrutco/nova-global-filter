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
            foreach (json_decode($request->filters, true) as $filter => $value) {
                if(in_array($filter, $filters)){
                  $model = (new $filter)->apply($request, $model, $value);
                }
            }
        }
        return $model;
    }
}
