<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;

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

Route::controller(AddressController::class)->group(function () {
    Route::prefix('upload')->name('upload.')->group(function () {
        Route::get('/', 'uploadForm')->name('form');
        Route::post('/', 'uploadAddresses')->name('submit');
    });

    Route::get('/compare', 'compare')->name('compare');
    Route::get('/export-csv', 'exportCSV')->name('export.csv');
});