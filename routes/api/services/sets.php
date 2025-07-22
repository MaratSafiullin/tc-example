<?php

use App\Http\ServicesApi\Controllers\SetController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'sets', 'as' => 'sets.'], function (): void {
    Route::get('/', [SetController::class, 'show'])->name('show');
});
