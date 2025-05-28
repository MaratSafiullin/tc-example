<?php

namespace App\Models;

use App\Models\Set\ContextType;
use App\ModesStates\Set\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\ModelStates\HasStates;
use Spatie\ModelStates\HasStatesContract;

class Set extends Model implements HasStatesContract
{
    use HasFactory;
    use HasStates;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status'       => Status::class,
            'context_type' => ContextType::class,
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function texts(): HasMany
    {
        return $this->hasMany(Text::class);
    }

    public function themes(): HasMany
    {
        return $this->hasMany(Theme::class);
    }
}
