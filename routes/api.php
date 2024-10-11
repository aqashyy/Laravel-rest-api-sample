<?php

use App\Http\Controllers\Api\MenuitemsController;
use App\Http\Controllers\Api\PageItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('menuitems',MenuitemsController::class);
    Route::apiResource('pageitem',PageItemController::class);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
