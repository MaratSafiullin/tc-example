<?php

namespace App\Http\PublicApi\Controllers\ResponseExamples;

use App\Http\Core\Docs;

class ThemeControllerExamples
{
    public const INDEX = [
        'data'  => [
            [
                'id'   => 1,
                'name' => 'Theme 1',
            ],
            [
                'id'   => 2,
                'name' => 'Theme 2',
            ],
        ],
        'links' => [
            'first' => Docs::EXAMPLE_URL . '/api/public/sets/1/themes?page=1',
            'last'  => Docs::EXAMPLE_URL . '/api/public/sets/1/themes?page=1',
            'prev'  => null,
            'next'  => null,
        ],
        'meta'  => [
            'path'         => Docs::EXAMPLE_URL . '/api/public/sets/1/themes',
            'total'        => 2,
            'from'         => 1,
            'to'           => 2,
            'current_page' => 1,
            'last_page'    => 1,
            'per_page'     => 10,
        ],
    ];
}
