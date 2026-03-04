<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientContactController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/clients');

Route::resource('clients', ClientController::class)->only([
    'index',
    'create',
    'store',
    'show',
]);

Route::resource('contacts', ContactController::class)->only([
    'index',
    'create',
    'store',
    'show',
]);

Route::post('/clients/{client}/contacts/{contact}', [ClientContactController::class, 'linkContactToClient'])
    ->name('clients.contacts.link');
Route::delete('/clients/{client}/contacts/{contact}', [ClientContactController::class, 'unlinkContactFromClient'])
    ->name('clients.contacts.unlink');
Route::post('/contacts/{contact}/clients/{client}', [ClientContactController::class, 'linkClientToContact'])
    ->name('contacts.clients.link');
Route::delete('/contacts/{contact}/clients/{client}', [ClientContactController::class, 'unlinkClientFromContact'])
    ->name('contacts.clients.unlink');
