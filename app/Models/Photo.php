<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'album_id'];

    public function likes(): HasMany
    {
        return $this->hasMany(PhotoLike::class);
    }
}
