<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StaffController; 
use App\Enums\UserRole; // Import Enum jika guna nilai Enum secara terus
use App\Http\Controllers\StaffSelfProfileController; 

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

    // LALUAN UNTUK PAPARKAN DETAIL STAF (SHOW PAGE)
    Route::get('/admin/staf/{user}', [StaffController::class, 'show'])->name('admin.staf.show');
    //           ^^^^^^^^^^^^^^^^^ URL dengan parameter {user} (tanpa /show)
    //                              ^^^^^ Method 'show' dalam StaffController
    //                                          ^^^^^^^^^^^^^^^^ Nama route

    // UBAH LALUAN INI:
    Route::get('/admin/manage-staff', [StaffController::class, 'index'])->name('admin.manage');
    // 'StaffController::class' merujuk kepada controller yang kita import
    // 'index' adalah nama method dalam StaffController yang akan dipanggil

});

    // LALUAN  UNTUK STAF (PROFIL SENDIRI)
    // Semua laluan di sini memerlukan pengesahan (auth) dan peranan 'staf'
    Route::middleware(['auth', 'role:' . \App\Enums\UserRole::STAF->value])->group(function () {

        // Laluan untuk staf lihat profil sendiri
        // URL: /staf/profil-saya
        // Nama Route akan jadi: staf.profil.show (jika kita tambah ->name('staf.profil.') pada group)
        // Buat masa sekarang kita namakan secara penuh dulu
        Route::get('/staf/profil-saya', [StaffSelfProfileController::class, 'show'])->name('staf.profil.show');

        // Laluan untuk staf paparkan borang edit profil sendiri
        // URL: /staf/profil-saya/edit
        // Nama Route: staf.profil.edit
        Route::get('/staf/profil-saya/edit', [\App\Http\Controllers\StaffSelfProfileController::class, 'edit'])->name('staf.profil.edit');

        // Laluan untuk staf kemaskini (update) profil sendiri
        // URL: /staf/profil-saya/update (Method: PUT) - Saya ubah sikit URL untuk lebih jelas
        // Nama Route: staf.profil.update
        Route::put('/staf/profil-saya/update', [\App\Http\Controllers\StaffSelfProfileController::class, 'update'])->name('staf.profil.update');

    });
    // AKHIR TAMBAHAN: LALUAN KHAS UNTUK STAF

require __DIR__.'/auth.php';
