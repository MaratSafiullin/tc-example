<?php

use App\Http\Core\Middleware\ForceJsonResponse;
use App\Models\AccessToken\Ability;
use Illuminate\Support\Facades\Route;

$publicApi = Ability::PublicApi->value;

Route::group(
    [
        'prefix' => 'public',
        'as' => 'api.public.',
        'middleware' => [ForceJsonResponse::class, 'auth:sanctum', "abilities:$publicApi"],
    ],
    function (): void {
        Route::group([], __DIR__ . '/api/public/sets.php');
        Route::group([], __DIR__ . '/api/public/themes.php');
    }
);
