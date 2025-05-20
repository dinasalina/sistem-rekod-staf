<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dapatkan semua pengguna yang mempunyai peranan 'staf'
        // Kita akan guna pagination untuk paparan yang lebih kemas jika data banyak
        $stafUsers = User::where('role', UserRole::STAF)
                         ->orderBy('name', 'asc') // Susun ikut nama secara menaik (A-Z)
                         ->paginate(10); // Paparkan 10 rekod setiap halaman

        // Hantar data staf ke view 'admin.manage-staff'
        // Pastikan view ini wujud: resources/views/admin/manage-staff.blade.php
        return view('admin.manage-staff', compact('stafUsers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Kita akan cipta view ini pada langkah seterusnya
        return view('admin.staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Data Input dari Borang
        $validatedData = $request->validate([
            // Dari jadual 'users'
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],

            // Dari jadual 'staff_details'
            'staff_id_number' => ['nullable', 'string', 'max:255', 'unique:staff_details,staff_id_number'],
            'department' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'date_joined' => ['nullable', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Validasi untuk gambar profil
        ]);

        // 2. Cipta Rekod Pengguna (User) Baru
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => UserRole::STAF,
        ]);

        // 3. Uruskan Muat Naik Gambar Profil (jika ada)
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        // 4. Cipta Rekod StaffDetail Baru dan Sambungkan dengan User
        $user->staffDetail()->create([
            'staff_id_number' => $validatedData['staff_id_number'] ?? null,
            'department' => $validatedData['department'] ?? null,
            'position' => $validatedData['position'] ?? null,
            'phone_number' => $validatedData['phone_number'] ?? null,
            'address' => $validatedData['address'] ?? null,
            'date_joined' => $validatedData['date_joined'] ?? null,
            'salary' => $validatedData['salary'] ?? null,
            'bank_name' => $validatedData['bank_name'] ?? null,
            'bank_account_number' => $validatedData['bank_account_number'] ?? null,
            'emergency_contact_name' => $validatedData['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $validatedData['emergency_contact_phone'] ?? null,
            'profile_image_path' => $profileImagePath,
        ]);

        // 5. Redirect Admin ke Halaman Senarai Staf dengan Mesej Kejayaan
        return redirect()->route('admin.manage')->with('success', 'Staf baru berjaya didaftarkan!');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
