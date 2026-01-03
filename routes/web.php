<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SettingController;
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

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');

// Dashboard
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan/updateprofil', [SettingController::class, 'updateprofil'])->name('pengaturan.updateprofil');
    Route::post('/pengaturan/updateemail', [SettingController::class, 'updateemail'])->name('pengaturan.updateemail');
    Route::post('/pengaturan/updatepassword', [SettingController::class, 'updatepassword'])->name('pengaturan.updatepassword');
    Route::post('/pengaturan/updategambar', [SettingController::class, 'updategambar'])->name('pengaturan.updategambar');
    Route::post('/pengaturan/hapusgambar', [SettingController::class, 'hapusgambar'])->name('pengaturan.hapusgambar');

});
