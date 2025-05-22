<?php

namespace App\Http\Controllers\Admin;

// Import kelas-kelas yang diperlukan
use App\Models\User; // Model untuk interaksi dengan jadual 'users'
use App\Models\StaffDetail; // Model untuk interaksi dengan jadual 'staff_details'
use App\Enums\UserRole; // Enum untuk pengurusan peranan pengguna yang konsisten
use App\Http\Controllers\Controller; // Kelas Controller asas Laravel
use Illuminate\Http\Request; // Untuk menguruskan HTTP request yang masuk
use Illuminate\Support\Facades\Hash; // Untuk hashing kata laluan
use Illuminate\Validation\Rule; // Untuk peraturan validasi yang lebih kompleks (cth: unique ignore)
use Illuminate\Support\Facades\Storage; // Untuk pengurusan fail (cth: gambar profil)
use Illuminate\Validation\Rules\Password; // Untuk peraturan validasi kata laluan standard Laravel

class StaffController extends Controller
{
    /**
     * Memaparkan senarai semua staf.
     * Method ini dipanggil oleh route GET /admin/manage-staff (atau /admin/staf jika diubahsuai).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Dapatkan semua pengguna yang mempunyai peranan 'staf'.
        //    Disusun mengikut nama (A-Z) dan dipaparkan 10 rekod setiap halaman (pagination).
        $stafUsers = User::where('role', UserRole::STAF)
                            ->orderBy('name', 'asc') 
                            ->paginate(10); 

        // 2. Hantar data staf ke view untuk paparan.
        //    View yang digunakan ialah 'admin.manage-staff'.
        return view('admin.manage-staff', compact('stafUsers'));
    }

    /**
     * Memaparkan borang untuk mencipta staf baru.
     * Method ini dipanggil oleh route GET /admin/staf/create.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Hanya paparkan view borang tambah staf.
        // View: resources/views/admin/staff/create.blade.php
        return view('admin.staff.create');
    }

    /**
     * Menyimpan rekod staf yang baru dicipta ke dalam pangkalan data.
     * Method ini dipanggil oleh route POST /admin/staf (dari borang tambah staf).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // 1. Sahkan (validate) data input dari borang.
        //    Jika validasi gagal, Laravel akan redirect pengguna kembali ke borang dengan ralat.
        $validatedData = $request->validate([
            // Peraturan validasi untuk data pengguna (jadual 'users')
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'], // Emel mesti unik
            'password' => ['required', 'string', Password::defaults(), 'confirmed'], // Guna peraturan password default & pastikan ada pengesahan

            // Peraturan validasi untuk data detail staf (jadual 'staff_details')
            'staff_id_number' => ['nullable', 'string', 'max:255', 'unique:staff_details,staff_id_number'], // ID Staf mesti unik jika diisi
            'department' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'date_joined' => ['nullable', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'], // Mesti nombor dan tidak kurang dari 0
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Gambar: jenis, saiz max 2MB
        ]);

        // 2. Cipta rekod pengguna (User) baru dalam jadual 'users'.
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Kata laluan di-hash sebelum disimpan
            'role' => UserRole::STAF, // Tetapkan peranan sebagai STAF secara automatik
        ]);

        // 3. Uruskan muat naik gambar profil jika ada.
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            // Simpan gambar dalam direktori 'storage/app/public/profile_images'
            // Nama fail akan dijana secara unik oleh Laravel.
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        // 4. Cipta rekod StaffDetail baru dan kaitkan dengan User yang baru dicipta.
        //    Gunakan method relationship `staffDetail()` yang telah didefinisikan dalam model User.
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
            'profile_image_path' => $profileImagePath, // Simpan laluan gambar
        ]);

        // 5. Redirect pengguna kembali ke halaman senarai staf (admin.manage)
        //    berserta mesej kejayaan (flash message).
        return redirect()->route('admin.manage')->with('success', 'Staf baru berjaya didaftarkan!');
    }

    /**
     * Memaparkan maklumat terperinci untuk seorang staf.
     * Method ini dipanggil oleh route GET /admin/staf/{user}.
     *
     * @param  \App\Models\User  $user // Objek User diambil secara automatik melalui Route Model Binding
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        // 1. Muatkan data berkaitan dari `staffDetail` untuk pengguna ini (eager loading).
        //    Walaupun boleh lazy load, ini lebih eksplisit.
        $user->load('staffDetail'); 

        // 2. Hantar data $user (yang kini mengandungi staffDetail) ke view.
        //    View: resources/views/admin/staff/show.blade.php
        return view('admin.staff.show', compact('user'));
    }

    /**
     * Memaparkan borang untuk mengemaskini maklumat staf sedia ada.
     * Method ini dipanggil oleh route GET /admin/staf/{user}/edit.
     *
     * @param  \App\Models\User  $user // Objek User diambil secara automatik melalui Route Model Binding
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        // 1. Muatkan data berkaitan dari `staffDetail` untuk pengguna ini.
        $user->load('staffDetail'); 

        // 2. Hantar data $user ke view borang edit.
        //    View: resources/views/admin/staff/edit.blade.php
        return view('admin.staff.edit', compact('user'));
    }

    /**
     * Mengemaskini maklumat staf sedia ada dalam pangkalan data.
     * Method ini dipanggil oleh route PUT/PATCH /admin/staf/{user} (dari borang edit).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user // Objek User diambil secara automatik melalui Route Model Binding
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // 1. Sahkan (validate) data input dari borang edit.
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], // Abaikan ID pengguna semasa untuk semakan unik emel
            'password' => ['nullable', 'string', Password::defaults(), 'confirmed'], // Kata laluan kini 'nullable' (tidak wajib diisi)

            'staff_id_number' => ['nullable', 'string', 'max:255', Rule::unique('staff_details')->ignore($user->staffDetail?->id)], // Abaikan rekod staffDetail semasa
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
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // 2. Kemaskini rekod dalam jadual 'users'.
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        // Hanya kemaskini kata laluan jika medan kata laluan diisi dalam borang.
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->save(); // Simpan perubahan pada rekod User

        // 3. Sediakan data untuk dikemaskini dalam jadual 'staff_details'.
        $staffDetailData = [
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
        ];

        // 4. Uruskan kemaskini gambar profil jika ada gambar baru dimuat naik.
        if ($request->hasFile('profile_image')) {
            // Padam gambar profil lama dari storage jika wujud.
            if ($user->staffDetail && $user->staffDetail->profile_image_path) {
                Storage::disk('public')->delete($user->staffDetail->profile_image_path);
            }
            // Simpan gambar baru dan dapatkan laluannya.
            $staffDetailData['profile_image_path'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        // 5. Kemaskini (atau cipta jika belum ada) rekod dalam 'staff_details'.
        //    `updateOrCreate` akan cari rekod dengan `user_id`, jika jumpa ia update, jika tidak ia create.
        $user->staffDetail()->updateOrCreate(
            ['user_id' => $user->id], // Syarat untuk mencari/mencipta
            $staffDetailData // Data untuk dikemaskini atau dicipta
        );

        // 6. Redirect pengguna kembali ke halaman senarai staf (admin.manage)
        //    berserta mesej kejayaan.
        return redirect()->route('admin.manage')->with('success', 'Maklumat staf berjaya dikemaskini!');
    }

    /**
     * Memadam rekod staf dari pangkalan data.
     * Method ini dipanggil oleh route DELETE /admin/staf/{user}.
     *
     * @param  \App\Models\User  $user // Objek User diambil secara automatik melalui Route Model Binding
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // 1. (Pilihan Keselamatan Tambahan) Pastikan hanya pengguna dengan peranan STAF yang dipadam.
        if ($user->role->value !== UserRole::STAF->value) {
            return redirect()->route('admin.manage')->with('error', 'Hanya pengguna dengan peranan staf boleh dipadam melalui fungsi ini.');
        }

        // 2. Padam gambar profil staf dari storage jika wujud.
        if ($user->staffDetail && $user->staffDetail->profile_image_path) {
            Storage::disk('public')->delete($user->staffDetail->profile_image_path);
        }

        // 3. Padam rekod User dari jadual 'users'.
        //    Disebabkan 'onDelete('cascade')' pada foreign key 'user_id' dalam
        //    migration 'create_staff_details_table', rekod StaffDetail yang berkaitan
        //    akan dipadam secara automatik oleh pangkalan data.
        $user->delete();

        // 4. Redirect pengguna kembali ke halaman senarai staf (admin.manage)
        //    berserta mesej kejayaan.
        return redirect()->route('admin.manage')->with('success', 'Maklumat staf telah berjaya dipadam.');
    }
}