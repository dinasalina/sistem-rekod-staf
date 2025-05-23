<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StaffController; 
use App\Enums\UserRole;
use App\Http\Controllers\StaffSelfProfileController; 
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Laluan untuk Dashboard - kini hanya ada satu definisi yang menguruskan peranan
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    if ($user && $user->role->value === UserRole::STAF->value) {
        return view('staf.dashboard', ['user' => $user->load('staffDetail')]); 
    } elseif ($user && $user->role->value === UserRole::ADMIN->value) {
        return redirect()->route('admin.manage'); // Pastikan nama route ini betul
    } elseif ($user && $user->role->value === UserRole::SUPER_ADMIN->value) {
        // Kita akan redirect ke 'superadmin.settings' yang baru
        return redirect()->route('superadmin.settings'); 
    }
    return view('dashboard'); // Fallback
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->name('profile.')->group(function () { // Ditambah name prefix untuk konsistensi
    Route::get('/profile', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('destroy');
});

// Laluan untuk Super Admin SAHAJA
Route::middleware(['auth', 'role:'.UserRole::SUPER_ADMIN->value])
    ->prefix('superadmin') // Semua URL akan mula dengan /superadmin
    ->name('superadmin.') // Semua nama route akan mula dengan superadmin.
    ->group(function () {
    
    Route::get('/settings', function () { // URL akan jadi /superadmin/settings
        // TUKAR INI untuk paparkan view
        return view('superadmin.settings'); 
    })->name('settings'); // Nama route akan jadi superadmin.settings
    
    // Tambah laluan super admin lain di sini nanti
});

// Laluan untuk Admin SAHAJA
Route::middleware(['auth', 'role:'.UserRole::ADMIN->value])
    ->prefix('admin') // Menetapkan prefix URL /admin untuk group ini
    ->name('admin.') // Menetapkan prefix nama route admin. untuk group ini
    ->group(function () {
    
    // Kumpulan laluan khusus untuk pengurusan Staf
    Route::prefix('staf')->name('staf.')->group(function() {
        // Nota: Route untuk senarai staf masih 'admin.manage' di sini seperti kod asal awak.
        // Jika nak ikut cadangan refactor penuh, ini akan jadi Route::get('/', ...)->name('index');
        // dan nama route jadi 'admin.staf.index'

        Route::get('/create', [StaffController::class, 'create'])->name('create'); // admin.staf.create
        Route::post('/', [StaffController::class, 'store'])->name('store'); // admin.staf.store
        Route::get('/{user}/edit', [StaffController::class, 'edit'])->name('edit'); // admin.staf.edit
        Route::put('/{user}', [StaffController::class, 'update'])->name('update'); // admin.staf.update
        Route::delete('/{user}', [StaffController::class, 'destroy'])->name('destroy'); // admin.staf.destroy
        Route::get('/{user}', [StaffController::class, 'show'])->name('show'); // admin.staf.show
    });
    
    // Laluan untuk senarai staf (menggunakan nama asal 'admin.manage')
    Route::get('/manage-staff', [StaffController::class, 'index'])->name('manage'); // Nama route: admin.manage
});

// LALUAN UNTUK STAF (PROFIL SENDIRI)
Route::middleware(['auth', 'role:' . UserRole::STAF->value])
    ->prefix('staf/profil-saya') // Prefix URL
    ->name('staf.profil.') // Prefix Nama Route
    ->group(function () {
    
    Route::get('/', [StaffSelfProfileController::class, 'show'])->name('show'); // staf.profil.show
    Route::get('/edit', [StaffSelfProfileController::class, 'edit'])->name('edit'); // staf.profil.edit
    Route::put('/update', [StaffSelfProfileController::class, 'update'])->name('update'); // staf.profil.update
    // Saya kekalkan /update di hujung URL untuk PUT di sini seperti permintaan awak sebelum ini
});

require __DIR__.'/auth.php';