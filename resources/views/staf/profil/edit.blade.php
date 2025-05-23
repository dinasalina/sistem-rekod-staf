{{-- resources/views/staf/profil/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kemaskini Profile Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    {{-- Paparkan ralat validasi umum jika ada --}}
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('staf.profil.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- MAKLUMAT PERIBADI BOLEH DIKEMASKINI --}}
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Maklumat Peribadi</h3>

                        <div class="mt-4">
                            <x-input-label for="phone_number" :value="__('No. Telefon')" />
                            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number', $user->staffDetail?->phone_number)" autocomplete="tel" />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="address" :value="__('Alamat Rumah')" />
                            <textarea id="address" name="address" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address', $user->staffDetail?->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        {{-- MAKLUMAT BANK & KECEMASAN BOLEH DIKEMASKINI --}}
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
                            <x-text-input id="emergency_contact_phone" class="block mt-1 w-full" type="text" name="emergency_contact_phone" :value="old('emergency_contact_phone', $user->staffDetail?->emergency_contact_phone)" />
                            <x-input-error :messages="$errors->get('emergency_contact_phone')" class="mt-2" />
                        </div>

                        {{-- KEMASKINI KATA LALUAN (PILIHAN) --}}
                        <hr class="my-6 border-gray-300 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tukar Kata Laluan (Pilihan)</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Biarkan kosong jika tidak mahu menukar kata laluan.</p>

                        <div class="mt-4">
                            <x-input-label for="current_password" :value="__('Kata Laluan Semasa')" />
                            <x-text-input id="current_password" class="block mt-1 w-full" type="password" name="current_password" autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Kata Laluan Baru')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Sahkan Kata Laluan Baru')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        {{-- GAMBAR PROFIL (PILIHAN) --}}
                        <hr class="my-6 border-gray-300 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tukar Gambar Profil (Pilihan)</h3>

                        <div class="mt-4">
                            @if ($user->staffDetail && $user->staffDetail->profile_image_path)
                                <div class="mb-2">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Gambar Semasa:</p>
                                    <img src="{{ asset('storage/' . $user->staffDetail->profile_image_path) }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-md object-cover">
                                </div>
                            @endif
                            <x-input-label for="profile_image" :value="__('Pilih Gambar Baru (Biarkan kosong jika tidak mahu tukar)')" />
                            <input id="profile_image" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file" name="profile_image">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">Format: PNG, JPG, JPEG (MAX. 2MB).</p>
                            <x-input-error :messages="$errors->get('profile_image')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>