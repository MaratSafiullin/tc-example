<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function themeTexts(): HasMany
    {
        return $this->hasMany(ThemeText::class);
    }

    public function texts(): BelongsToMany
    {
        return $this->belongsToMany(Text::class)->using(ThemeText::class);
    }
}
