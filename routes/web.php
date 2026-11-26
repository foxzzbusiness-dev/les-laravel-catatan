
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\HaloController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\CatatanController;

// Halaman publik
Route::get('/', [BerandaController::class, 'index']);
Route::get('/halo', [HaloController::class, 'index']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/kontak', [KontakController::class, 'index']);

// Halaman terlindungi
Route::middleware(['auth'])->group(function () {

    Route::get('/catatan', [CatatanController::class, 'index'])->name('catatan');

    Route::get('/catatan/tambah', [CatatanController::class, 'create']);
    Route::post('/catatan', [CatatanController::class, 'store']);

    Route::get('/catatan/export', [CatatanController::class, 'export'])->name('catatan.export');
    Route::get('/catatan/import', [CatatanController::class, 'importForm']);
    Route::post('/catatan/import', [CatatanController::class, 'import']);

    // Route arsip harus sebelum route {id}
    Route::get('/catatan/arsip', [CatatanController::class, 'arsip'])
        ->name('catatan.arsip');

    Route::post('/catatan/{id}/archive', [CatatanController::class, 'archive']);
    Route::post('/catatan/{id}/unarchive', [CatatanController::class, 'unarchive']);

    Route::get('/catatan/{id}/edit', [CatatanController::class, 'edit']);
    Route::put('/catatan/{id}', [CatatanController::class, 'update']);
    Route::delete('/catatan/{id}', [CatatanController::class, 'destroy']);
    Route::get('/catatan/{id}', [CatatanController::class, 'show']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
