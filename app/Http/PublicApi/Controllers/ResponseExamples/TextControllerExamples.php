<?php

namespace App\Http\PublicApi\Controllers\ResponseExamples;

use App\Http\Core\Docs;
use App\Models\ThemeText\Sentiment;

class TextControllerExamples
{
    public const INDEX = [
        'data'  => [
            [
                'id'          => 1,
                'external_id' => 'external_id_1',
                'text'        => 'You shall not pass!',
                'themes'      => [
                    [
                        'id'        => 1,
                        'name'      => 'Theme 1',
                        'sentiment' => Sentiment::Negative,
                    ],
                    [
                        'id'        => 2,
                        'name'      => 'Theme 2',
                        'sentiment' => Sentiment::Neutral,
                    ],
                    [
                        'id'        => 3,
                        'name'      => 'Theme 3',
                        'sentiment' => Sentiment::Positive,
                    ],
                ],
            ],
            [
                'id'          => 2,
                'external_id' => 'external_id_2',
                'text'        => 'The answer is 42.',
                'themes'      => [],
            ],
        ],
        'links' => [
            'first' => Docs::EXAMPLE_URL . '/api/public/sets/1/texts?page=1',
            'last'  => Docs::EXAMPLE_URL . '/api/public/sets/1/texts?page=1',
            'prev'  => null,
            'next'  => null,
        ],
        'meta'  => [
            'path'         => Docs::EXAMPLE_URL . '/api/public/sets/1/texts',
            'total'        => 2,
            'from'         => 1,
            'to'           => 2,
            'current_page' => 1,
            'last_page'    => 1,
            'per_page'     => 10,
        ],
    ];
}
