<?php

use App\Http\Controllers\DesaController;
use App\Http\Controllers\GuruPaudController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LembagaPaudController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/account', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/kecamatan', [KecamatanController::class, 'index'])->name('kecamatan.index');
    Route::post('/kecamatan', [KecamatanController::class, 'store'])->name('kecamatan.store');
    Route::put('/kecamatan/{kecamatan}', [KecamatanController::class, 'update'])->name('kecamatan.update');
    Route::delete('/kecamatan/{kecamatan}', [KecamatanController::class, 'destroy'])->name('kecamatan.destroy');
    Route::get('/desa', [DesaController::class, 'index'])->name('desa.index');
    Route::post('/desa', [DesaController::class, 'store'])->name('desa.store');
    Route::put('/desa/{desa}', [DesaController::class, 'update'])->name('desa.update');
    Route::delete('/desa/{desa}', [DesaController::class, 'destroy'])->name('desa.destroy');
    Route::get('/lembaga', [LembagaPaudController::class, 'index'])->name('lembaga.index');
    Route::post('/lembaga', [LembagaPaudController::class, 'store'])->name('lembaga.store');
    Route::put('/lembaga/{lembaga}', [LembagaPaudController::class, 'update'])->name('lembaga.update');
    Route::delete('/lembaga/{lembaga}', [LembagaPaudController::class, 'destroy'])->name('lembaga.destroy');
    Route::get('/guru-paud', [GuruPaudController::class, 'index'])->name('guru.index');
    Route::post('/guru-paud', [GuruPaudController::class, 'store'])->name('guru.store');
    Route::put('/guru-paud/{guru}', [GuruPaudController::class, 'update'])->name('guru.update');
    Route::delete('/guru-paud/{guru}', [GuruPaudController::class, 'destroy'])->name('guru.destroy');
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    Route::get('/siswa/export', [SiswaController::class, 'export'])->name('siswa.export');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
    Route::put('/laporan/{id}', [LaporanController::class, 'update'])->name('laporan.update');
    Route::delete('/laporan/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
    Route::post('/laporan/verifikasi/{id}', [LaporanController::class, 'verifikasi'])->name('laporan.verifikasi');




});

Route::middleware('auth', 'super_admin')->group(function () {
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
