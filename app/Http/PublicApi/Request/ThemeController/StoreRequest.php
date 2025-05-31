<?php

namespace App\Http\PublicApi\Request\ThemeController;

use App\Http\Core\Request\Keys;
use App\Http\Core\Request\Request;
use App\Rules\SetThemesUnique;

class StoreRequest extends Request
{
    public function rules(): array
    {
        /** @var \App\Models\Set $set */
        $set        = $this->route()->parameter(Keys::SET);
        $uniqueRule = new SetThemesUnique($set);

        return [
            Keys::THEMES                      => ['required', 'array', 'min:1', 'max:100', 'bail', $uniqueRule],
            Keys::THEMES . '.*'               => ['required', 'array'],
            Keys::THEMES . '.*.' . Keys::NAME => ['required', 'string', 'max:255'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            Keys::THEMES . '.*.' . Keys::NAME => [
                'example' => 'Prices and discounts',
            ],
        ];
    }
}
