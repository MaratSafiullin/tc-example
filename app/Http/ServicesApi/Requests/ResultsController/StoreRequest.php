<?php

namespace App\Http\ServicesApi\Requests\ResultsController;

use App\Http\Core\Requests\Keys;
use App\Http\Core\Requests\Request;
use Illuminate\Validation\Rule;

class StoreRequest extends Request
{
    public function rules(): array
    {
        return [
            Keys::TEXTS                                                  =>
                ['required', 'array', 'min:1', 'max:1000'],
            Keys::TEXTS . '.*'                                           =>
                ['required', 'array'],
            Keys::TEXTS . '.*.' . Keys::ID                               =>
                [
                    'required',
                    'integer',
                    Rule::exists('texts', 'id'),
                ],
            Keys::TEXTS . '.*.' . Keys::THEMES                           =>
                ['present', 'array', 'max:10'],
            Keys::TEXTS . '.*.' . Keys::THEMES . '.*'                    =>
                ['required', 'array'],
            Keys::TEXTS . '.*.' . Keys::THEMES . '.*.' . Keys::NAME      =>
                ['required', 'string', 'max:255'],
            Keys::TEXTS . '.*.' . Keys::THEMES . '.*.' . Keys::SENTIMENT =>
                [
                    'required',
                    Rule::in(['positive', 'neutral', 'negative']),
                ],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            Keys::TEXTS => [
                'example' => [
                    [
                        Keys::ID     => 1,
                        Keys::THEMES => [
                            [
                                Keys::NAME      => 'Customer Service',
                                Keys::SENTIMENT => 'positive',
                            ],
                            [
                                Keys::NAME      => 'Product Quality',
                                Keys::SENTIMENT => 'neutral',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
