<?php

use App\Http\PublicApi\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'sets/{set}/themes', 'as' => 'sets.themes.'], function (): void {
    Route::get('/', [ThemeController::class, 'index'])->name('index');
});
