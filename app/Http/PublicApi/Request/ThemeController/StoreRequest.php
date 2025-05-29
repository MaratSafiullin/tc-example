<?php

namespace App\Http\PublicApi\Request\ThemeController;

use App\Http\Core\Request\Keys;
use App\Http\Core\Request\Request;

class StoreRequest extends Request
{
    public function rules(): array
    {
        return [
            Keys::THEMES                      => ['required', 'array', 'min:1', 'max:100'],
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
