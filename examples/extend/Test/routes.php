<?php

use Illuminate\Support\Facades\Route;

Route::get('plugin/test', [\App\Plugins\Test\Http\Controllers\TestController::class, 'test']);
Route::get('plugin/test2', [\App\Plugins\Test\Http\Controllers\TestController::class, 'test2']);
