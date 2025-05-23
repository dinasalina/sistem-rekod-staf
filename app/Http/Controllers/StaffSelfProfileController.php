<?php

namespace App\Http\Controllers; // Namespace dah betul

// Senarai 'use' statements yang diperlukan
use App\Models\User;
use App\Models\StaffDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
// use App\Http\Controllers\Controller; // Tidak perlu jika namespace semasa adalah App\Http\Controllers

class StaffSelfProfileController extends Controller // Mewarisi Controller asas Laravel
{
    /**
     * Memaparkan profil staf yang sedang log masuk.
     * Dipanggil oleh route GET /staf/profil-saya.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        /** @var \App\Models\User $user */ // PHPDoc untuk bantu IDE kenal jenis $user
        $user = Auth::user(); // Dapatkan pengguna yang sedang log masuk
        $user->load('staffDetail'); // Muatkan maklumat StaffDetail yang berkaitan (eager load)

        // Hantar data pengguna ke view 'staf.profil.show'
        // View ini akan kita cipta pada Langkah 15.D
        return view('staf.profil.show', compact('user'));
    }

    /**
     * Memaparkan borang untuk staf mengemaskini profil sendiri.
     * Dipanggil oleh route GET /staf/profil-saya/edit.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        /** @var \App\Models\User $user */ // PHPDoc untuk bantu IDE
        $user = Auth::user(); // Dapatkan pengguna yang sedang log masuk
        $user->load('staffDetail'); // Muatkan maklumat StaffDetail

        // Hantar data pengguna ke view 'staf.profil.edit'
        // View ini akan kita cipta pada Langkah 15.D
        return view('staf.profil.edit', compact('user'));
    }

    /**
     * Mengemaskini profil staf yang sedang log masuk dalam pangkalan data.
     * Dipanggil oleh route PUT /staf/profil-saya/update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */ // PHPDoc untuk bantu IDE
        $user = Auth::user(); // Dapatkan pengguna yang sedang log masuk

        // 1. Sahkan (validate) data input dari borang edit staf sendiri.
        //    Medan yang dibenarkan untuk dikemaskini oleh staf mungkin lebih terhad.
        $validatedData = $request->validate([
            // Jika staf dibenarkan tukar nama atau emel, peraturan validasi perlu ditambah di sini.
            // Contoh:
            // 'name' => ['sometimes', 'required', 'string', 'max:255'], // 'sometimes' bermakna hanya validate jika ada dalam request
            // 'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            
            // Untuk kemaskini kata laluan (jika dibenarkan)
            'current_password' => ['nullable', 'string', 'current_password'], // Sahkan kata laluan semasa jika kata laluan baru diisi
            'password' => ['nullable', 'string', Password::defaults(), 'confirmed', 'required_with:current_password'], // Kata laluan baru, wajib jika current_password diisi
            
            // Medan dari StaffDetail yang boleh dikemaskini oleh staf
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Validasi untuk gambar profil
        ]);

        // 2. Kemaskini rekod dalam jadual 'users' (jika ada medan yang berkaitan).
        //    Contoh jika nama dan emel dibenarkan untuk ditukar:
        //    if ($request->filled('name')) { $user->name = $validatedData['name']; }
        //    if ($request->filled('email')) { $user->email = $validatedData['email']; }

        // Kemaskini kata laluan HANYA jika medan 'password' baru diisi DAN 'current_password' sah (telah divalidasi).
        if (!empty($validatedData['password']) && $request->filled('current_password')) { // Pastikan current_password juga diisi untuk trigger validasi
            $user->password = Hash::make($validatedData['password']);
        }
        $user->save(); // Simpan perubahan pada User (terutamanya untuk password atau jika ada medan User lain yang diubah)

        // 3. Sediakan data untuk dikemaskini dalam jadual 'staff_details'.
        $staffDetailData = [];
        $staffDetailFields = [ // Senarai medan yang mungkin ada dalam validatedData untuk staffDetail
            'phone_number', 'address', 'bank_name', 'bank_account_number', 
            'emergency_contact_name', 'emergency_contact_phone'
        ];

        foreach ($staffDetailFields as $field) {
            if (isset($validatedData[$field])) {
                $staffDetailData[$field] = $validatedData[$field];
            }
        }

        // 4. Uruskan kemaskini gambar profil jika ada gambar baru dimuat naik.
        if ($request->hasFile('profile_image')) {
            // Padam gambar lama dari storage jika wujud.
            if ($user->staffDetail && $user->staffDetail->profile_image_path) {
                Storage::disk('public')->delete($user->staffDetail->profile_image_path);
            }
            // Simpan gambar baru dan masukkan laluannya ke dalam array data.
            $staffDetailData['profile_image_path'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        // 5. Kemaskini (atau cipta jika belum ada) rekod StaffDetail.
        //    Hanya lakukan operasi jika ada data untuk dikemaskini atau gambar baru.
        if (!empty($staffDetailData)) {
            $user->staffDetail()->updateOrCreate(
                ['user_id' => $user->id], // Syarat untuk mencari/mencipta
                $staffDetailData         // Data untuk dikemaskini atau dicipta
            );
        } elseif ($user->staffDetail === null && empty($staffDetailData) && $request->hasFile('profile_image')) {
            // Kes khas: staffDetail tiada, tiada data lain, tapi ada gambar baru. Perlu cipta.
            // (Sebenarnya, jika ada gambar baru, $staffDetailData['profile_image_path'] akan ada, jadi if di atas akan cover)
            // Untuk lebih selamat jika $staffDetailData mungkin kosong tapi gambar ada:
            // $user->staffDetail()->create(['profile_image_path' => $staffDetailData['profile_image_path']]);
            // Tapi updateOrCreate dengan $staffDetailData yang ada profile_image_path sepatutnya dah cukup.
        }
        
        // 6. Redirect staf kembali ke halaman profil mereka dengan mesej kejayaan.
        return redirect()->route('staf.profil.show')->with('success', 'Maklumat profil anda berjaya dikemaskini!');
    }
}