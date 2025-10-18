<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Translation extends Model
{
    use HasFactory;
    protected $table = 'translation';
    public $timestamps = false;
    protected $fillable = ['locale', 'key', 'content'];

    /**
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'translation_tag', 'translation_id', 'tag_id');
    }

}
