<?php

use App\Http\Controllers\Admin\AdminInstansiController;
use App\Http\Controllers\Admin\AdminLevelController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Middleware\CekLevel;
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


    // Admin
    Route::group(['middleware' => [CekLevel::class . ':1']], function () {

        // Instansi
        Route::get('/admin-instansi/index', [AdminInstansiController::class, 'index'])->name('admin-instansi.index');
        Route::get('/admin-instansi/create', [AdminInstansiController::class, 'create'])->name('admin-instansi.create');
        Route::get('/admin-instansi/edit/{id}', [AdminInstansiController::class, 'edit'])->name('admin-instansi.edit');
        Route::post('/admin-instansi/store', [AdminInstansiController::class, 'store'])->name('admin-instansi.store');
        Route::post('/admin-instansi/update/{id}', [AdminInstansiController::class, 'update'])->name('admin-instansi.update');
        Route::post('/admin-instansi/destroy/{id}', [AdminInstansiController::class, 'destroy'])->name('admin-instansi.destroy');

        // Level
        Route::get('/admin-level/index', [AdminLevelController::class, 'index'])->name('admin-level.index');
        Route::get('/admin-level/create', [AdminLevelController::class, 'create'])->name('admin-level.create');
        Route::get('/admin-level/edit/{id}', [AdminLevelController::class, 'edit'])->name('admin-level.edit');
        Route::post('/admin-level/store', [AdminLevelController::class, 'store'])->name('admin-level.store');
        Route::post('/admin-level/update/{id}', [AdminLevelController::class, 'update'])->name('admin-level.update');
        Route::post('/admin-level/destroy/{id}', [AdminLevelController::class, 'destroy'])->name('admin-level.destroy');
    });
});
