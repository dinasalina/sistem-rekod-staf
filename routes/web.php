<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StaffController;
use App\Enums\UserRole; // Import Enum jika guna nilai Enum secara terus

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Laluan untuk Super Admin SAHAJA
Route::middleware(['auth', 'role:'.UserRole::SUPER_ADMIN->value])->group(function () {
    
     
    Route::get('/superadmin/settings', function () {
        return 'Halaman Tetapan Super Admin';
    })->name('superadmin.settings');
    // Tambah laluan super admin lain di sini
});

// Laluan untuk Admin SAHAJA
Route::middleware(['auth', 'role:'.UserRole::ADMIN->value])->group(function () {
    
    // LALUAN BARU UNTUK PAPARKAN BORANG TAMBAH STAF
    Route::get('/admin/staf/create', [StaffController::class, 'create'])->name('admin.staf.create');

     // LALUAN BARU UNTUK SIMPAN DATA STAF BARU (dari borang)
    Route::post('/admin/staf', [StaffController::class, 'store'])->name('admin.staf.store');
    //     ^^^^ Method POST
    //          ^^^^ URLnya boleh sama dengan senarai (jika guna method berbeza) atau lain. '/admin/staf' adalah konvensyen.

     // LALUAN BARU UNTUK PAPARKAN BORANG EDIT STAF
    Route::get('/admin/staf/{user}/edit', [StaffController::class, 'edit'])->name('admin.staf.edit');
    //           ^^^^^^^^^^^^^^^^^^^ URL dengan parameter {user}
    //                              ^^^^^ Method 'edit' dalam StaffController
    //                                          ^^^^^^^^^^^^^^^^^ Nama route
    Route::put('/admin/staf/{user}', [StaffController::class, 'update'])->name('admin.staf.update');

    // LALUAN UNTUK PROSES PADAM DATA STAF
    Route::delete('/admin/staf/{user}', [StaffController::class, 'destroy'])->name('admin.staf.destroy');
    //       ^^^^^^^ Method DELETE
    //               ^^^^^^^^^^^^^^^ URL dengan parameter {user}
    //                                 ^^^^^^^ Method 'destroy' dalam StaffController
    //                                               ^^^^^^^^^^^^^^^^^^^ Nama route

    // UBAH LALUAN INI:
    Route::get('/admin/manage-staff', [StaffController::class, 'index'])->name('admin.manage');
    // 'StaffController::class' merujuk kepada controller yang kita import
    // 'index' adalah nama method dalam StaffController yang akan dipanggil

    // Tambah laluan admin lain di sini
});

require __DIR__.'/auth.php';
