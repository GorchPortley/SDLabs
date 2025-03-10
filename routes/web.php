<?php

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

use App\Models\Design;
use App\Models\Driver;
use Illuminate\Support\Facades\Route;
use Wave\Facades\Wave;

// Wave routes
Wave::routes();

Route::get('/design-snapshots/{snapshot}/download', [Design::class, 'download'])
    ->name('design-snapshots.download');

Route::get('/driver-snapshots/{snapshot}/download', [Driver::class, 'download'])
    ->name('driver-snapshots.download');
