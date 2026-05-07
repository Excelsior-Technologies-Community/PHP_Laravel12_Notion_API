<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotionController;

Route::get('/notion', [NotionController::class, 'index']);

Route::post('/notion', [NotionController::class, 'create']);

Route::get('/notion/search', [NotionController::class, 'search']);

Route::delete('/notion/archive/{id}', [NotionController::class, 'archive']);