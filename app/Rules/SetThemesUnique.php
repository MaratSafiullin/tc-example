<?php

namespace App\Rules;

use App\Models\Set;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

readonly class SetThemesUnique implements ValidationRule
{
    public function __construct(private Set $set)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $names = array_map(
            fn($theme) => $theme['name'] ?? '',
            $value
        );

        if (! $this->namesUnique($names) || $this->namesExist($names)) {
            $fail('Theme names must be unique within the set.');
        }
    }

    private function namesUnique(array $names): bool
    {
        return count($names) === count(array_unique($names));
    }

    private function namesExist($names): bool
    {
        return $this->set->themes()->whereIn('name', $names)->exists();
    }
}
