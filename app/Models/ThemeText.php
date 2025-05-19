<?php

namespace App\Models;

use App\Models\ThemeText\Sentiment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ThemeText extends Pivot
{
    use HasFactory;

    public    $timestamps = false;
    protected $guarded    = [];

    protected function casts(): array
    {
        return [
            'sentiment' => Sentiment::class,
        ];
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function text(): BelongsTo
    {
        return $this->belongsTo(Text::class);
    }
}
