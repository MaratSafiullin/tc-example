<?php

namespace App\Http\PublicApi\Requests\SetController;

use App\Http\Core\Requests\Keys;
use App\Http\Core\Requests\Request;
use App\Models\Set\ContextType;
use Illuminate\Validation\Rule;

class StoreRequest extends Request
{
    public function rules(): array
    {
        return [
            Keys::NAME         => ['required', 'string', 'max:255'],
            Keys::EXTERNAL_ID  => [
                'string',
                'max:255',
                Rule::unique('sets')->where(
                    fn($query) => $query->where('owner_id', auth()->id())
                ),
            ],
            Keys::CONTEXT_TYPE => ['required', Rule::in(ContextType::cases())],
            Keys::CONTEXT      => ['required', 'string', 'max:65535'],
            Keys::CALLBACK_URL => ['url', 'max:255'],
        ];
    }

    public function bodyParameters(): array
    {
        return [
            Keys::NAME         => [
                'example' => 'New set',
            ],
            Keys::EXTERNAL_ID  => [
                'example' => 'external_id',
            ],
            Keys::CALLBACK_URL => [
                'example' => 'https://my.domain/tc-callback',
            ],
            Keys::CONTEXT      => [
                'example' => 'What`s your opinion on ducks?',
            ],
            Keys::CONTEXT_TYPE => [
                'description' => 'Enum.',
                'example'     => ContextType::QuestionAnswer->value,
            ],
        ];
    }
}
