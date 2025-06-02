<?php

use App\Http\PublicApi\Controllers\SetController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'sets', 'as' => 'sets.'], function (): void {
    Route::get('/', [SetController::class, 'index'])->name('index');

    Route::post('/', [SetController::class, 'store'])->name('store');

    Route::get('/{set}', [SetController::class, 'show'])->name('show');

    Route::get('by-external-id/{external_id}', [SetController::class, 'showByExternalId'])->name('show-by-external-id');

    Route::delete('/{set}', [SetController::class, 'delete'])->name('delete');

    Route::post('/{set}/start-tc', [SetController::class, 'startTc'])->name('start-tc');
});
