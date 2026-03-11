<?php

use App\Http\Controllers\NotionController;

Route::get('notion', [NotionController::class, 'index']);
Route::post('notion', [NotionController::class, 'create']);