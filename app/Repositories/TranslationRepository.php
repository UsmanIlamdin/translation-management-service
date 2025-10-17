<?php

namespace App\Repositories;

use App\Models\Translation;

class TranslationRepository implements TranslationRepositoryInterface
{
    public function getAll(){
        return Translation::all();
    }

    public function getById($id){
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
