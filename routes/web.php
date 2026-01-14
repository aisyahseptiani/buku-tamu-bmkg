<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\QrPublicController;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QrController;

/*
|--------------------------------------------------------------------------
| PUBLIK (QR & PENGUNJUNG)
|--------------------------------------------------------------------------
*/

// hasil scan QR
Route::get('/qrs/{token}', [QrPublicController::class, 'show'])
    ->name('qr.public');

// form pengunjung (HANYA BISA DIAKSES SETELAH SCAN QR)
Route::get('/pengunjung', [PengunjungController::class, 'step1'])
    ->name('pengunjung.step1');

Route::post('/pengunjung', [PengunjungController::class, 'postStep1'])
    ->name('pengunjung.step1.post');

// survei
Route::get('/survei', [PengunjungController::class, 'survei'])
    ->name('pengunjung.survei');

Route::post('/survei', [PengunjungController::class, 'storeSurvei'])
    ->name('pengunjung.survei.store');


/*
|--------------------------------------------------------------------------
| AUTH ADMIN
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AuthController::class, 'login'])
    ->name('admin.login');

Route::post('/admin/login', [AuthController::class, 'authenticate'])
    ->name('admin.login.post');


/*
|--------------------------------------------------------------------------
| AREA ADMIN (LOGIN WAJIB)
|--------------------------------------------------------------------------
*/

Route::middleware('auth.admin')->prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/dashboard/pdf', [DashboardController::class, 'exportPdf'])
        ->name('admin.dashboard.exportPdf');

    Route::get('/qr', [QrController::class, 'index'])
        ->name('admin.qr.index');

    Route::post('/qr/generate', [QrController::class, 'generate'])
        ->name('admin.qr.generate');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('admin.logout');
});


/*
|--------------------------------------------------------------------------
| TEST PDF (OPSIONAL)
|--------------------------------------------------------------------------
*/

Route::get('/test-pdf', function () {
    return Pdf::loadHTML('<h1>PDF BERHASIL</h1>')
        ->download('test.pdf');
});

//testing preview
Route::get('/preview-step1', function () {
    session(['qr_token' => 'TEST']);
    return redirect()->route('pengunjung.step1');
});

Route::get('/preview-survei', function () {
    session(['pengunjung' => ['nama' => 'Test']]);
    return redirect()->route('pengunjung.survei');
});
