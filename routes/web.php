<?php

use App\Http\Web\Controllers\DocsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/docs', [DocsController::class, 'publicPage'])->name('scribe_public.page');
Route::get('/docs-openapi', [DocsController::class, 'publicOpenApi'])->name('scribe_public.openapi');
Route::get('/docs-postman', [DocsController::class, 'publicPostman'])->name('scribe_public.postman');
