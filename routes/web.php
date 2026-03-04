<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/clients');

Route::resource('clients', ClientController::class)->only([
    'index',
    'create',
    'store',
    'show',
]);
