<?php

namespace App\Http\PublicApi\Requests\TextController;

use App\Http\Core\Requests\Keys;
use App\Http\Core\Requests\Request;

class StoreRequest extends Request
{
    public function rules(): array
    {
        return [
            Keys::TEXTS                             => ['required', 'array', 'min:1', 'max:1000'],
            Keys::TEXTS . '.*'                      => ['required', 'array'],
            Keys::TEXTS . '.*.' . Keys::TEXT        => ['required', 'string', 'max:2000'],
            Keys::TEXTS . '.*.' . Keys::EXTERNAL_ID => ['string', 'max:255'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            Keys::TEXTS . '.*.' . Keys::TEXT        => [
                'example' => 'I like the prices.',
            ],
            Keys::TEXTS . '.*.' . Keys::EXTERNAL_ID => [
                'example' => 'answer_id_123',
            ],
        ];
    }
}
