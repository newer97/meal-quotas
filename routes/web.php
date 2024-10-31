<?php

use App\Livewire\Login;
use App\Livewire\Serve;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', Serve::class)->middleware('auth');

Route::get('/login', Login::class)->name('login');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');
