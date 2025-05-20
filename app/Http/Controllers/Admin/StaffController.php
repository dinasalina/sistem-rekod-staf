<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        //
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
