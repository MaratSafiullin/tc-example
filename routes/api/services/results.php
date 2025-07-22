<?php

use App\Http\ServicesApi\Controllers\ResultsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'sets/{set}/results', 'as' => 'sets.results.'], function (): void {
    Route::post('/', [ResultsController::class, 'store'])->name('store');
});
