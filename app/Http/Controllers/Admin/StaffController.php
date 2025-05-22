<?php // WAJIB MULA DENGAN INI

namespace App\Http\Controllers\Admin; // Kemudian namespace

// Kemudian semua 'use' statements
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StaffDetail;
use App\Enums\UserRole;
use App\Http\Controllers\Controller; // 'use' untuk base Controller
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

// AKHIR SEKALI BARU CLASS DEFINITION
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
     public function show(User $user) // Laravel akan secara automatik cari User berdasarkan {user} dalam URL
    {
        // Pastikan kita ada data staffDetail untuk user ini juga.
        // Eloquent akan lazy load jika kita akses $user->staffDetail,
        // tapi untuk kepastian atau jika banyak data nak dipaparkan, lebih baik muatkan secara eksplisit.
        $user->load('staffDetail'); 

        // Hantar data $user (yang kini sepatutnya ada staffDetail) ke view paparan detail
        // Kita akan cipta view 'admin.staff.show' pada langkah seterusnya (14.D)
        return view('admin.staff.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) // Laravel akan secara automatik cari User berdasarkan {user} dalam URL
    {
        // Pastikan kita ada data staffDetail untuk user ini
        // Eloquent akan lazy load jika kita akses $user->staffDetail,
        // tapi kalau nak pastikan ia dimuatkan (eager load), boleh guna:
        $user->load('staffDetail'); 

        // Hantar data $user (yang kini sepatutnya ada staffDetail) ke view borang edit
        // Kita akan cipta view 'admin.staff.edit' pada langkah seterusnya
        return view('admin.staff.edit', compact('user'));
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user) // $user akan jadi objek User yang nak dikemaskini
    {
        // 1. Validasi Data Input
        // Peraturan validasi hampir sama dengan store(), tapi untuk 'unique' kita perlu abaikan rekod semasa
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', Password::defaults(), 'confirmed'], // Password kini nullable, hanya validate jika diisi

            // Dari jadual 'staff_details'
            // Untuk staff_id_number, kita perlu pastikan ia unik KECUALI untuk staff_detail milik user ini sendiri
            'staff_id_number' => ['nullable', 'string', 'max:255', Rule::unique('staff_details')->ignore($user->staffDetail?->id)],
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

        // 2. Kemaskini Rekod Pengguna (User)
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        // Hanya kemaskini password jika diisi
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        $user->save(); // Simpan perubahan pada User

        // 3. Kemaskini atau Cipta Rekod StaffDetail
        // Kita guna updateOrCreate untuk handle kes jika staffDetail belum wujud (walaupun sepatutnya dah wujud)
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

        // 4. Uruskan Kemaskini Gambar Profil (jika ada gambar baru dimuat naik)
        if ($request->hasFile('profile_image')) {
            // Padam gambar lama jika ada
            if ($user->staffDetail && $user->staffDetail->profile_image_path) {
                Storage::disk('public')->delete($user->staffDetail->profile_image_path);
            }
            // Simpan gambar baru dan dapatkan laluannya
            $staffDetailData['profile_image_path'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        // Kemaskini atau cipta StaffDetail
        // Jika $user->staffDetail belum ada, ia akan cipta baru
        // Jika dah ada, ia akan kemaskini
        $user->staffDetail()->updateOrCreate(
            ['user_id' => $user->id], // Syarat untuk cari rekod sedia ada
            $staffDetailData // Data untuk dikemaskini atau dicipta
        );

        // 5. Redirect Admin ke Halaman Senarai Staf dengan Mesej Kejayaan
        return redirect()->route('admin.manage')->with('success', 'Maklumat staf berjaya dikemaskini!');
    }


    /**
     * Remove the specified resource from storage.
     */
     public function destroy(User $user) // $user adalah objek User yang akan dipadam
    {
        // 1. Semak jika pengguna ini adalah STAF (langkah keselamatan tambahan, walaupun route dah dilindungi)
        if ($user->role->value !== UserRole::STAF->value) {
            // Mungkin redirect dengan ralat jika bukan staf, atau biarkan sahaja jika route dah cukup selamat
            return redirect()->route('admin.manage')->with('error', 'Hanya pengguna dengan peranan staf boleh dipadam melalui fungsi ini.');
        }

        // 2. Padam gambar profil dari storage jika ada
        if ($user->staffDetail && $user->staffDetail->profile_image_path) {
            Storage::disk('public')->delete($user->staffDetail->profile_image_path);
        }

        // 3. Padam rekod User.
        // Oleh sebab kita dah set 'onDelete('cascade')' pada foreign key 'user_id' 
        // dalam migration 'create_staff_details_table',
        // rekod dalam 'staff_details' yang berkaitan akan dipadam secara automatik.
        // Jika tiada cascade, kita perlu padam staffDetail dulu:
        // if ($user->staffDetail) {
        //     $user->staffDetail->delete();
        // }
        $user->delete();

        // 4. Redirect Admin ke Halaman Senarai Staf dengan Mesej Kejayaan
        return redirect()->route('admin.manage')->with('success', 'Maklumat staf telah berjaya dipadam.');
    }
}