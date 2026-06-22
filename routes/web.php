<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotionController;

Route::get('/', [NotionController::class, 'dashboard'])->name('notion.dashboard');

Route::post('/notion/sync', [NotionController::class, 'sync'])->name('notion.sync');

Route::get('/notion/pages', [NotionController::class, 'index'])->name('notion.pages');

Route::post('/notion/create', [NotionController::class, 'create'])->name('notion.create');

Route::post('/notion/archive/{id}', [NotionController::class, 'archive'])->name('notion.archive');