<?php

use App\Http\ServicesApi\Controllers\TextController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'sets/{set}/texts', 'as' => 'sets.texts.'], function (): void {
    Route::get('/', [TextController::class, 'index'])->name('index');
});
