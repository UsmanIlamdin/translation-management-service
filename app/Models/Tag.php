<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;
    protected $table = 'tag';
    public $timestamps = false;
    protected $fillable = ['name'];

    public function translations()
    {
        return $this->belongsToMany(Translation::class, 'translation_tag', 'tag_id', 'translation_id');
    }
}
