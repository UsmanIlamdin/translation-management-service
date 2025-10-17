<?php

namespace App\Repositories;

use App\Models\Translation;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;


class TranslationRepository implements TranslationRepositoryInterface
{
    public function baseQuery(): Builder
    {
        return Translation::query()->with('tags');
    }

    public function paginate(Builder $query, int $perPage): Paginator
    {
        return $query->simplePaginate($perPage);
    }

    public function getById($id): Translation
    {
        return Translation::findOrFail($id);
    }

    public function store(array $data){
        return Translation::create($data);
    }

    public function update(array $data,$id){
        return Translation::whereId($id)->update($data);
    }

    public function delete($id){
        Translation::destroy($id);
    }
}
