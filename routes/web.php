<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\ApiAuthMiddleware;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// rutas de controller usuario
Route::post('api/user/register', [UserController::class, 'register'])->name('register');
Route::post('api/user/login', [UserController::class, 'login'])->name('login');
Route::put('api/user/update', [UserController::class, 'update'])->name('update');
Route::post('api/user/upload', [UserController::class, 'upload'])->name('upload')->middleware([ApiAuthMiddleware::class]);
Route::get('api/user/getImage/{filename}', [UserController::class, 'getImage'])->name('getImage');
Route::get('api/user/detail/{id}', [UserController::class, 'detail'])->name('detail');


// rutas de controller categorías

Route::resource('api/category/category', CategoryController::class);

// rutas de controller categorías

Route::resource('api/post/post', PostController::class);
Route::post('api/post/upload', [PostController::class, 'upload'])->name('upload');
Route::get('api/post/getImage/{filename}', [PostController::class, 'getImage'])->name('getImage');

Route::get('api/post/getPostByCategory/{id}', [PostController::class, 'getPostByCategory'])->name('getPostByCategory');
Route::get('api/post/getPostByUser/{id}', [PostController::class, 'getPostByUser'])->name('getPostByUser');
