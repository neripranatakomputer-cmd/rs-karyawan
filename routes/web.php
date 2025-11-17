<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\CustomFieldController;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::resource('karyawan', KaryawanController::class);
Route::resource('custom-fields', CustomFieldController::class);

// Import & Export Routes
Route::get('karyawan-import', [KaryawanController::class, 'importForm'])->name('karyawan.import.form');
Route::post('karyawan-import', [KaryawanController::class, 'import'])->name('karyawan.import');
Route::get('karyawan-export', [KaryawanController::class, 'export'])->name('karyawan.export');
Route::get('karyawan-template', [KaryawanController::class, 'downloadTemplate'])->name('karyawan.template');