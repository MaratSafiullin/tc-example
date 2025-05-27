<?php

use App\Http\PublicApi\Controllers\SetController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'sets', 'as' => 'sets.'], function (): void {
    Route::get('/', [SetController::class, 'index'])->name('index');
    Route::post('/', [SetController::class, 'store'])->name('store');
});
