<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CarController;

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

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('list-category',[CategoryController::class, 'categoryList']);
    Route::post('add-category',[CategoryController::class,  'addCategory']);
    Route::get('edit-category/{id}',[CategoryController::class,  'editCategory']);
    Route::patch('update-category',[CategoryController::class,  'updateCategory']);
    Route::get('delete-category/{id}',[CategoryController::class,  'deleteCategory']);
    Route::get('list-car',[CarController::class, 'carList']);
    Route::post('add-car',[CarController::class,  'addCar']);
    Route::get('edit-car/{id}',[CarController::class,  'editCar']);
    Route::patch('update-car',[CarController::class,  'updateCar']);
    Route::get('delete-car/{id}',[CarController::class,  'deleteCar']);
    Route::get('logout',[UserController::class,  'logout']);


});

Route::post('register',[UserController::class, 'signUp']);
Route::post('login',[UserController::class, 'login']);













