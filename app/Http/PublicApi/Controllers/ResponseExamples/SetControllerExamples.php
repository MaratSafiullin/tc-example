<?php

namespace App\Http\PublicApi\Controllers\ResponseExamples;

use App\Http\Core\Docs;
use App\Models\Set\ContextType;
use App\ModesStates\Set\StatusName;

class SetControllerExamples
{
    public const INDEX = [
        'data'  => [
            [
                'id'           => 1,
                'owner_id'     => 1,
                'external_id'  => null,
                'name'         => 'Set 1',
                'status'       => StatusName::Draft,
                'context_type' => 'question_answer',
                'context'      => 'Question text',
                'callback_url' => 'https://my.domain/tc-callback',

            ],
            [
                'id'           => 2,
                'owner_id'     => 1,
                'external_id'  => 'custom_id',
                'name'         => 'Set 2',
                'status'       => StatusName::Processing,
                'context_type' => 'question_answer',
                'context'      => 'Question text',
                'callback_url' => null,

            ],
        ],
        'links' => [
            'first' => Docs::EXAMPLE_URL . '/api/public/sets?page=1',
            'last'  => Docs::EXAMPLE_URL . '/api/public/sets?page=1',
            'prev'  => null,
            'next'  => null,
        ],
        'meta'  => [
            'path'         => Docs::EXAMPLE_URL . '/api/public/sets',
            'total'        => 2,
            'from'         => 1,
            'to'           => 2,
            'current_page' => 1,
            'last_page'    => 1,
            'per_page'     => 10,
        ],
    ];
    public const SHOW  = [
        'data' => [
            [
                'id'           => 1,
                'external_id'  => 'external_id',
                'owner_id'     => 1,
                'name'         => 'New set',
                'status'       => StatusName::Draft,
                'context_type' => ContextType::QuestionAnswer,
                'context'      => 'What`s your opinion on ducks?',
                'callback_url' => 'https://my.domain/tc-callback',
            ],
        ],
    ];
    public const STORE = self::SHOW;
}
