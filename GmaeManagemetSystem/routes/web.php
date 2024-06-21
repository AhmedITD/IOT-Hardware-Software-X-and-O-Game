<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authmanger;
use App\Http\Controllers\gameController;
Route::get('/', [Authmanger::class, 'login'])->name(name: 'login');
Route::post('/', [Authmanger::class, 'loginPost'])->name(name: 'login.post');

Route::get('game/logout', [Authmanger::class, 'logout'])->name(name: 'logout');


Route::group(['middleware' => 'auth'],function()
{
    Route::get('/register', [Authmanger::class, 'register'])->name(name: 'register');
    Route::post('/register', [Authmanger::class, 'registerPost'])->name(name: 'register.post');
    
    Route::get('/game', [gameController::class, 'index'])->name(name: 'game');
    
    Route::get('/game/paly', [gameController::class, 'play'])->name(name: 'play');
    Route::post('/game/paly', [gameController::class, 'playPost'])->name(name: 'play.post');
});