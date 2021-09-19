<?php
use App\Exports\ExportBuilder;
if (!function_exists('exportBuilder')) {
    function exportBuilder($collection,$headings, $title)
    {
        return (new ExportBuilder())
            ->setHeadings($headings)
            ->setTitle($title)
            ->setCollection($collection)
            ->get();
    }
}
if (!function_exists('getChunk')) {
    function getChunk($skip, $take = 0 , $model , $map, array $relation = null)
    {
        return $model::with($relation)
            ->skip($skip)
            ->when($take, fn ($model) => $model->take($take))
            ->get()
            ->map($map);
    }
}
