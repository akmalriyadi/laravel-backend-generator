<?php
namespace AkmalRiyadi\LaravelBackendGenerator\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BaseScope
{
    public function scopeFilter(Builder $query, array $filters)
    {
        return $query->when($filters['sort'] ?? false, function ($query, $sort) {
            return $query->orderBy($sort['column'], $sort['dir']);
        });
    }
}