<?php

use Illuminate\Support\Facades\Route;

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
    return response('Hello, Octane with FrankenPHP!')->header('Content-Type', 'text/plain');
});

Route::get('/test', function () {
    return response('This is a test route for Octane with FrankenPHP!')->header('Content-Type', 'text/plain');
});