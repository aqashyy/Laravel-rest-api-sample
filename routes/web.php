<?php

use App\Http\Controllers\Api\MenuitemsController;
use App\Http\Controllers\MainController;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class,'index']);

Route::get('apitest',[MenuitemsController::class,'index']);
Route::get('login',function () {
    return response()->json([
        'message' => 'Unauthorized',
        'status' => 'error',
    ]);
})->name('login');

$menus  =   MenuItem::where('state', '1')->get();
foreach ($menus as $menu)
{

    // dd($menu->alias);
        // if($menu->viewtype == 'link')
        // {
        //     Route::get($menu->alias)
        // }
    Route::get($menu->alias, [MainController::class,'RoutesContents']);
}


