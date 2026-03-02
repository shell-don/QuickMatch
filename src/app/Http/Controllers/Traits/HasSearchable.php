<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait HasSearchable
{
    protected function applySearch(Request $request, $query, array $fields): void
    {
        $search = $request->get('search', '');

        if (! $search) {
            return;
        }

        $query->where(function ($q) use ($search, $fields) {
            foreach ($fields as $field) {
                $q->orWhere($field, 'like', "%{$search}%");
            }
        });
    }

    protected function applyFilters(Request $request, $query, array $filters): void
    {
        foreach ($filters as $param => $callback) {
            $value = $request->get($param);

            if ($value) {
                $callback($query, $value);
            }
        }
    }

    protected function applySorting(Request $request, $query, string $defaultColumn = 'created_at', string $defaultDirection = 'desc'): void
    {
        $column = $request->get('sort', $defaultColumn);
        $direction = $request->get('direction', $defaultDirection);

        if (in_array($direction, ['asc', 'desc'])) {
            $query->orderBy($column, $direction);
        }
    }
}
