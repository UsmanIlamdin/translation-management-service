<?php

namespace App\Repositories;

use App\Models\Translation;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

interface TranslationRepositoryInterface
{
    public function baseQuery(): Builder;
    public function paginate(Builder $query, int $perPage): Paginator;
    public function getById(int $id): Translation;
    public function store(array $data);
    public function update(array $data, int $id);
    public function delete(int $id);
}
