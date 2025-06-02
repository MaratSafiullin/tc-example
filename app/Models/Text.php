<?php

namespace App\Models;

use App\ModesStates\Text\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStates\HasStatesContract;

class Text extends Model implements HasStatesContract
{
    use HasFactory;
    use HasStates;

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }

    public function set(): BelongsTo
    {
        return $this->belongsTo(Set::class);
    }

    public function themeTexts(): HasMany
    {
        return $this->hasMany(ThemeText::class);
    }

    public function themes(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class, 'theme_text')->using(ThemeText::class);
    }
}
