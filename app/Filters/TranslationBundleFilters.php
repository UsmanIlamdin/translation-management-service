<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TranslationBundleFilters
{
    protected array $locales;
    protected array $tags;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->locales = $request->query('locale')
            ? array_filter(explode(',', $request->query('locale')))
            : [];

        $this->tags = $request->query('tags')
            ? array_filter(explode(',', $request->query('tags')))
            : [];
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function apply(Builder $query): Builder
    {
        if (!empty($this->locales)) {
            $query->whereIn('locale', $this->locales);
        }

        if (!empty($this->tags)) {
            $query->whereHas('tags', function ($q) {
                $q->whereIn('name', $this->tags);
            });
        }

        return $query;
    }
}
