<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GolferController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/nearest-golfers', [GolferController::class, 'nearestGet']);
Route::get('/nearest-golfers/csv', [GolferController::class, 'nearestCsv']);