<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\LapanganController;

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
    if (session('admin_id')) {
        return redirect('/dashboard');
    }
    $lapangan = \App\Models\Lapangan::all();
    return view('beranda', compact('lapangan'));
});

Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login']);
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

Route::get('/beranda', function () {
    if (session('admin_id')) {
        return redirect('/dashboard');
    }
    $lapangan = \App\Models\Lapangan::all();
    return view('beranda', compact('lapangan'));
});

Route::get('/dashboard', [LapanganController::class, 'index']);
Route::get('/dashboard/input', [LapanganController::class, 'input'])->name('dashboard.input');
Route::get('/dashboard/edit', [LapanganController::class, 'edit'])->name('dashboard.edit');
Route::get('/dashboard/delete', [LapanganController::class, 'delete'])->name('dashboard.delete');
Route::get('/dashboard/nota', [LapanganController::class, 'nota'])->name('dashboard.nota');

Route::post('/lapangan/store', [LapanganController::class, 'store'])->name('lapangan.store');
Route::delete('/lapangan/{id}', [LapanganController::class, 'destroy'])->name('lapangan.delete');
Route::post('/lapangan/update/{id}', [LapanganController::class, 'update'])->name('lapangan.update');

// Route untuk menampilkan detail lapangan
Route::get('/lapangan/{id}', [LapanganController::class, 'showDetail'])->name('lapangan.detail');

Route::post('/nota/store', [LapanganController::class, 'storeNota'])->name('nota.store');
Route::patch('/nota/{id}', [LapanganController::class, 'updateNota'])->name('nota.update');
Route::delete('/nota/{id}', [LapanganController::class, 'destroyNota'])->name('nota.destroy');

Route::get('/nota', function() {
    return redirect()->route('dashboard.nota');
});
