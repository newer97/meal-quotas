<?php

use App\Livewire\Serve;
use Illuminate\Support\Facades\Route;

Route::get('/', Serve::class)->middleware('auth');
