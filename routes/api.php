<?php

use App\Http\Core\Requests\Middleware\ForceJsonResponse;
use App\Models\AccessToken\Ability;
use Illuminate\Support\Facades\Route;

$publicApi   = Ability::PublicApi->value;
$servicesApi = Ability::ServicesApi->value;

Route::group(
    [
        'prefix'     => 'public',
        'as'         => 'api.public.',
        'middleware' => [ForceJsonResponse::class, 'auth:sanctum', "abilities:$publicApi"],
    ],
    function (): void {
        Route::group([], __DIR__ . '/api/public/sets.php');
        Route::group([], __DIR__ . '/api/public/themes.php');
        Route::group([], __DIR__ . '/api/public/texts.php');
    }
);

Route::group(
    [
        'prefix'     => 'services',
        'as'         => 'api.services.',
        'middleware' => [ForceJsonResponse::class, 'auth:sanctum', "abilities:$servicesApi"],
    ],
    function (): void {
        Route::group([], __DIR__ . '/api/services/sets.php');
        Route::group([], __DIR__ . '/api/services/themes.php');
        Route::group([], __DIR__ . '/api/services/texts.php');
    }
);
