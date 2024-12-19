<?php

declare(strict_types=1);

namespace Performing\Harmony\Components\Filters\Styles;

use Illuminate\Database\Eloquent\Builder;

class GithubFiltersStyle
{
    public function __construct(
        /** @var FilterType[] */
        protected array $filters = []
    ) {
    }

    public function apply(Builder $query)
    {
        $qs = request()->input('table.filters');

        $qs = explode(' ', $qs);

        foreach ($qs as $q) {
            $q = explode(':', $q);


            if (count($q) == 3) {
                [$key, $operator, $value] = $q;

                $filter = collect($this->filters)->first(fn ($filter) => $filter->name() === $key);

                if ($filter) {
                    if ($operator) {
                        $filter->withOperator($operator);
                    }

                    if ($filter->hasStandaloneOperator()) {
                        $filter->apply($query);
                    } elseif (! empty($value)) {
                        $filter->withValue($value)->apply($query);
                    }
                }
            }
        }
    }
}
