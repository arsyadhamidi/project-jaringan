<?php

use App\Http\Controllers\Admin\AdminInstansiController;
use App\Http\Controllers\Admin\AdminJaringanController;
use App\Http\Controllers\Admin\AdminLaporanGangguanController;
use App\Http\Controllers\Admin\AdminLevelController;
use App\Http\Controllers\Admin\AdminStatusLaporanController;
use App\Http\Controllers\Admin\AdminTindakLanjutController;
use App\Http\Controllers\Admin\AdminUserController;
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

        // Tindak Lanjut
        Route::get('/admin-tindaklanjut/index', [AdminTindakLanjutController::class, 'index'])->name('admin-tindaklanjut.index');
        Route::get('/admin-tindaklanjut/tindaklanjut/{id}', [AdminTindakLanjutController::class, 'tindaklanjut'])->name('admin-tindaklanjut.tindaklanjut');

        // Laporan Gangguan
        Route::get('/admin-laporangangguan/index', [AdminLaporanGangguanController::class, 'index'])->name('admin-laporangangguan.index');
        Route::get('/admin-laporangangguan/create', [AdminLaporanGangguanController::class, 'create'])->name('admin-laporangangguan.create');
        Route::get('/admin-laporangangguan/edit/{id}', [AdminLaporanGangguanController::class, 'edit'])->name('admin-laporangangguan.edit');
        Route::post('/admin-laporangangguan/store', [AdminLaporanGangguanController::class, 'store'])->name('admin-laporangangguan.store');
        Route::post('/admin-laporangangguan/update/{id}', [AdminLaporanGangguanController::class, 'update'])->name('admin-laporangangguan.update');
        Route::post('/admin-laporangangguan/destroy/{id}', [AdminLaporanGangguanController::class, 'destroy'])->name('admin-laporangangguan.destroy');

        // Jaringan
        Route::get('/admin-jaringan/index', [AdminJaringanController::class, 'index'])->name('admin-jaringan.index');
        Route::get('/admin-jaringan/create', [AdminJaringanController::class, 'create'])->name('admin-jaringan.create');
        Route::get('/admin-jaringan/edit/{id}', [AdminJaringanController::class, 'edit'])->name('admin-jaringan.edit');
        Route::post('/admin-jaringan/store', [AdminJaringanController::class, 'store'])->name('admin-jaringan.store');
        Route::post('/admin-jaringan/update/{id}', [AdminJaringanController::class, 'update'])->name('admin-jaringan.update');
        Route::post('/admin-jaringan/destroy/{id}', [AdminJaringanController::class, 'destroy'])->name('admin-jaringan.destroy');

        // Status Laporan
        Route::get('/admin-status/index', [AdminStatusLaporanController::class, 'index'])->name('admin-status.index');
        Route::get('/admin-status/create', [AdminStatusLaporanController::class, 'create'])->name('admin-status.create');
        Route::get('/admin-status/edit/{id}', [AdminStatusLaporanController::class, 'edit'])->name('admin-status.edit');
        Route::post('/admin-status/store', [AdminStatusLaporanController::class, 'store'])->name('admin-status.store');
        Route::post('/admin-status/update/{id}', [AdminStatusLaporanController::class, 'update'])->name('admin-status.update');
        Route::post('/admin-status/destroy/{id}', [AdminStatusLaporanController::class, 'destroy'])->name('admin-status.destroy');

        // Users
        Route::get('/admin-users/index', [AdminUserController::class, 'index'])->name('admin-users.index');
        Route::get('/admin-users/create', [AdminUserController::class, 'create'])->name('admin-users.create');
        Route::get('/admin-users/edit/{id}', [AdminUserController::class, 'edit'])->name('admin-users.edit');
        Route::post('/admin-users/store', [AdminUserController::class, 'store'])->name('admin-users.store');
        Route::post('/admin-users/update/{id}', [AdminUserController::class, 'update'])->name('admin-users.update');
        Route::post('/admin-users/destroy/{id}', [AdminUserController::class, 'destroy'])->name('admin-users.destroy');

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
