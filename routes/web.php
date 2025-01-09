<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Pdf Download
Route::middleware('auth')->group(function () {
    Route::get('karyawan-pdf-download', [PdfController::class, 'karyawan'])->name('pdf.karyawan.download');

    Route::get('absensi-thisMonth-pdf-download', [PdfController::class, 'absensiThisMonth'])
        ->name('pdf.absensi.thisMonth.download');

    Route::get('absensi-thisDay-pdf-download', [PdfController::class, 'absensiThisDay'])
        ->name('pdf.absensi.thisDay.download');

    // Karyawan
    Route::get('/{user}/karyawan-absensi-pdf-download', [PdfController::class, 'karyawanAbsensi'])
        ->name('pdf.karyawan.absensi.download');

    // Division
    Route::get('/{division}/division-karyawan-pdf-download', [PdfController::class, 'divisionKaryawan'])
        ->name('pdf.division.karyawan.download');

    Route::get('gaji-pdf-download', [PdfController::class, 'gaji'])
        ->name('pdf.gaji.download');
});

// Absensi
Route::middleware('auth')->group(function () {
    Route::get('absen-datang', [AttendanceController::class, 'absenDatang'])
        ->name('karyawan.absen-harian.absenDatang');

    Route::get('absen-pulang', [AttendanceController::class, 'absenPulang'])
        ->name('karyawan.absen-harian.absenPulang');
});
