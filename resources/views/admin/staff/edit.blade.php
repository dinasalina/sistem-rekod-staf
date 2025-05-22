{{-- resources/views/admin/staff/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Maklumat Staf:') }} {{ $user->name}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('admin.staf.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- BAHAGIAN MAKLUMAT AKAUN PENGGUNA --}}
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Maklumat Akaun Pengguna</h3>

                        <div class="mt-4">
                            <x-input-label for="name" :value="__('Nama Penuh Staf')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Alamat Emel')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Kata Laluan (Biarkan kosong jika tidak mahu tukar)')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Sahkan Kata Laluan Baru')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        {{-- BAHAGIAN MAKLUMAT PERIBADI STAF --}}
                        <hr class="my-6 border-gray-300 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Maklumat Peribadi & Pekerjaan</h3>

                        <div class="mt-4">
                            <x-input-label for="staff_id_number" :value="__('ID Staf (Contoh: STF001)')" />
                            <x-text-input id="staff_id_number" class="block mt-1 w-full" type="text" name="staff_id_number" :value="old('staff_id_number', $user->staffDetail?->staff_id_number)" />
                            <x-input-error :messages="$errors->get('staff_id_number')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="department" :value="__('Jabatan')" />
                            <x-text-input id="department" class="block mt-1 w-full" type="text" name="department" :value="old('department', $user->staffDetail?->department)" />
                            <x-input-error :messages="$errors->get('department')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="position" :value="__('Jawatan')" />
                            <x-text-input id="position" class="block mt-1 w-full" type="text" name="position" :value="old('position', $user->staffDetail?->position)" />
                            <x-input-error :messages="$errors->get('position')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="phone_number" :value="__('No. Telefon Staf')" />
                            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number', $user->staffDetail?->phone_number)" /> {{-- DIBETULKAN --}}
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Alamat Rumah')" />
                            <textarea id="address" name="address" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address', $user->staffDetail?->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="date_joined" :value="__('Tarikh Mula Kerja')" />
                            <x-text-input id="date_joined" class="block mt-1 w-full" type="date" name="date_joined" :value="old('date_joined', $user->staffDetail?->date_joined)" />
                            <x-input-error :messages="$errors->get('date_joined')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="salary" :value="__('Gaji Bulanan (RM)')" />
                            <x-text-input id="salary" class="block mt-1 w-full" type="number" step="0.01" name="salary" :value="old('salary', $user->staffDetail?->salary)" />
                            <x-input-error :messages="$errors->get('salary')" class="mt-2" />
                        </div>

                        {{-- BAHAGIAN MAKLUMAT BANK & KECEMASAN --}}
                        <hr class="my-6 border-gray-300 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Maklumat Bank & Kecemasan</h3>

                        <div class="mt-4">
                            <x-input-label for="bank_name" :value="__('Nama Bank')" />
                            <x-text-input id="bank_name" class="block mt-1 w-full" type="text" name="bank_name" :value="old('bank_name', $user->staffDetail?->bank_name)" />
                            <x-input-error :messages="$errors->get('bank_name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="bank_account_number" :value="__('No. Akaun Bank')" />
                            <x-text-input id="bank_account_number" class="block mt-1 w-full" type="text" name="bank_account_number" :value="old('bank_account_number', $user->staffDetail?->bank_account_number)" />
                            <x-input-error :messages="$errors->get('bank_account_number')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="emergency_contact_name" :value="__('Nama Waris (Kecemasan)')" />
                            <x-text-input id="emergency_contact_name" class="block mt-1 w-full" type="text" name="emergency_contact_name" :value="old('emergency_contact_name', $user->staffDetail?->emergency_contact_name)" />
                            <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="emergency_contact_phone" :value="__('No. Telefon Waris (Kecemasan)')" />
                            <x-text-input id="emergency_contact_phone" class="block mt-1 w-full" type="text" name="emergency_contact_phone" :value="old('emergency_contact_phone', $user->staffDetail?->emergency_contact_phone)" /> {{-- KOMA DITAMBAH --}}
                            <x-input-error :messages="$errors->get('emergency_contact_phone')" class="mt-2" />
                        </div>

                        {{-- GAMBAR PROFIL --}}
                        <hr class="my-6 border-gray-300 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Gambar Profil Staf</h3>

                        <div class="mt-4">
                            <x-input-label for="profile_image" :value="__('Tukar Gambar Profil (Pilihan)')" />
                            @if ($user->staffDetail && $user->staffDetail->profile_image_path)
                                <div class="mt-2 mb-2">
                                    <img src="{{ asset('storage/' . $user->staffDetail->profile_image_path) }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-md object-cover">
                                    <p class="text-xs text-gray-500 mt-1">Gambar semasa.</p>
                                </div>
                            @endif
                            {{-- Saya betulkan sedikit kelas input fail untuk konsistensi dengan borang create --}}
                            <input id="profile_image" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file" name="profile_image">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">PNG, JPG atau JPEG (MAX. 2MB). Biarkan kosong jika tidak mahu tukar gambar.</p>
                            <x-input-error :messages="$errors->get('profile_image')" class="mt-2" />
                        </div>


                        <div class="flex items-center justify-end mt-8">
                            <x-primary-button>
                                {{ __('Kemaskini Maklumat Staf') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>