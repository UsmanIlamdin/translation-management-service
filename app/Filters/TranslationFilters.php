<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TranslationFilters
{
    protected ?string $locale;
    protected ?string $tag;
    protected int $perPage;

    /**
     * @param Request $request
     * @param string|null $locale
     * @param string|null $tag
     */
    public function __construct(Request $request, ?string $locale = null, ?string $tag = null)
    {
        $this->locale = $locale;
        $this->tag = $tag;
        $this->perPage = (int) $request->query('per_page', 15);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function apply(Builder $query): Builder
    {
        if (!empty($this->locale)) {
            $query->where('locale', $this->locale);
        }

        if (!empty($this->tag)) {
            $query->whereHas('tags', fn($q) => $q->where('name', $this->tag));
        }

        return $query;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }
}
