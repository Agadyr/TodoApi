<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/posts', [\App\Http\Controllers\BaseController::class, 'index']);

    Route::post('/post/create', [\App\Http\Controllers\BaseController::class, 'create']);
    Route::put('/post/update/{post}', [\App\Http\Controllers\BaseController::class, 'update']);
    Route::delete('/post/delete/{post}', [\App\Http\Controllers\BaseController::class, 'delete']);

});

Route::post('/personal-access-tokens', [\App\Http\Controllers\BaseController::class, 'personalAccess']);

