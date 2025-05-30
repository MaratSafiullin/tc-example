<?php

namespace App\Http\PublicApi\Request\ThemeController;

use App\Http\Core\Request\Keys;
use App\Http\Core\Request\Request;
use App\Rules\SetThemesUnique;
use Illuminate\Validation\Validator;

class StoreRequest extends Request
{
    public function rules(): array
    {
        return [
            Keys::THEMES                      => ['required', 'array', 'min:1', 'max:100', 'bail'],
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

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->count() > 0) {
                    return;
                }

                /** @var \App\Models\Set $set */
                $set    = $this->route()->parameter(Keys::SET);
                $rule   = new SetThemesUnique($set);
                $themes = $this->input(Keys::THEMES, []);

                $errorMessage = '';
                $validated    = true;
                $rule->validate(
                    Keys::THEMES,
                    $themes,
                    function (string $message) use (&$validated, &$errorMessage): void {
                        $validated    = false;
                        $errorMessage = $message;
                    }
                );
                if (! $validated) {
                    $validator->errors()->add(Keys::THEMES, $errorMessage);
                }
            },
        ];
    }
}
