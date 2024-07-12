<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/home', [AuthController::class, 'home'])->name('home')->middleware('auth');
Route::get('/user', [AuthController::class, 'showUser'])->name('user')->middleware('auth');
Route::get('/album', [AuthController::class, 'showAlbum'])->name('album')->middleware('auth');
// Route::get('/album/{id}', [AuthController::class, 'showAlbum'])->name('album')->middleware('auth')->middleware('auth');
Route::get('/gallery', [AuthController::class, 'showGallery'])->name('gallery')->middleware('auth');
Route::post('/album', [AuthController::class, 'storeAlbum'])->name('album.store')->middleware('auth');
Route::get('/album/manage/{id}', [AuthController::class, 'manageAlbum'])->name('album.manage')->middleware('auth');
// Route::post('/album/show/{id}', [AuthController::class, 'showAlbum'])->name('album.show')->middleware('auth');
// Route::get('/album/createoreAlbum'])->name('album.show')->middleware('auth');
Route::get('/album/edit/{id}', [AuthController::class, 'editAlbum'])->name('album.edit')->middleware('auth');
Route::put('/album/{id}', [AuthController::class, 'updateAlbum'])->name('album.update')->middleware('auth');
Route::delete('/album/{id}', [AuthController::class, 'deleteAlbum'])->name('album.delete')->middleware('auth');
