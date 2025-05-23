{{-- resources/views/staf/profil/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-2 sm:mb-0">
                {{ __('Profil Saya') }}
            </h2>
            <div>
                <a href="{{ route('staf.profil.edit') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 dark:hover:bg-gray-700 active:bg-gray-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Kemaskini Profile') }}
                </a>
            </div>
        </div>
    </x-slot>

    {{-- SEMUA KANDUNGAN UTAMA HALAMAN BERMULA DI SINI (DALAM x-app-layout) --}}
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Paparkan mesej success jika ada (selepas kemaskini profil) --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100 space-y-6">

                    {{-- Bahagian Gambar Profil & Maklumat Asas Pengguna --}}
                    <div class="flex flex-col sm:flex-row items-center sm:items-center space-y-4 sm:space-y-0 sm:space-x-8">
                        @if ($user->staffDetail && $user->staffDetail->profile_image_path)
                            <img src="{{ asset('storage/' . $user->staffDetail->profile_image_path) }}" alt="Gambar Profil {{ $user->name }}" class="h-32 w-32 rounded-lg object-cover border dark:border-gray-700">
                        @else
                            <div class="h-32 w-32 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 border dark:border-gray-600">
                                Tiada Gambar
                            </div>
                        @endif
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="text-2xl font-semibold">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 capitalize">Peranan: {{ $user->role->value }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">ID Staf: {{ $user->staffDetail?->staff_id_number ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Jabatan: {{ $user->staffDetail?->department ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Jawatan: {{ $user->staffDetail?->position ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200 dark:border-gray-700">

                    {{-- Maklumat Peribadi (yang boleh diedit oleh staf) --}}
                    <div>
                        <h4 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">Maklumat Peribadi Boleh Dikemaskini</h4>
                        <div class="space-y-3">
                            <div class="flex flex-col sm:flex-row">
                                <span class="font-medium text-gray-500 dark:text-gray-400 w-full sm:w-1/3">No. Telefon :</span>
                                <span class="text-gray-700 dark:text-gray-200 sm:w-2/3">{{ $user->staffDetail?->phone_number ?? 'N/A' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row">
                                <span class="font-medium text-gray-500 dark:text-gray-400 w-full sm:w-1/3">Alamat Rumah :</span>
                                <p class="text-gray-700 dark:text-gray-200 sm:w-2/3 whitespace-pre-line">{{ $user->staffDetail?->address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4 border-gray-200 dark:border-gray-700">

                    {{-- Maklumat Bank & Kecemasan (yang boleh diedit oleh staf) --}}
                    <div>
                        <h4 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">Maklumat Bank & Kecemasan Boleh Dikemaskini</h4>
                        <div class="space-y-3">
                            <div class="flex flex-col sm:flex-row">
                                <span class="font-medium text-gray-500 dark:text-gray-400 w-full sm:w-1/3">Nama Bank :</span>
                                <span class="text-gray-700 dark:text-gray-200 sm:w-2/3">{{ $user->staffDetail?->bank_name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row">
                                <span class="font-medium text-gray-500 dark:text-gray-400 w-full sm:w-1/3">No. Akaun Bank :</span>
                                <span class="text-gray-700 dark:text-gray-200 sm:w-2/3">{{ $user->staffDetail?->bank_account_number ?? 'N/A' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row">
                                <span class="font-medium text-gray-500 dark:text-gray-400 w-full sm:w-1/3">Nama Waris :</span>
                                <span class="text-gray-700 dark:text-gray-200 sm:w-2/3">{{ $user->staffDetail?->emergency_contact_name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row">
                                <span class="font-medium text-gray-500 dark:text-gray-400 w-full sm:w-1/3">No. Telefon Waris :</span>
                                <span class="text-gray-700 dark:text-gray-200 sm:w-2/3">{{ $user->staffDetail?->emergency_contact_phone ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tempat untuk Maklumat Tetap (Contoh: Tarikh Mula Kerja, Gaji - jika perlu dipaparkan tapi tak boleh edit) --}}
                    <hr class="my-4 border-gray-200 dark:border-gray-700">
                    <div>
                        <h4 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">Maklumat Pekerjaan (Tetap)</h4>
                            <div class="space-y-3">
                            <div class="flex flex-col sm:flex-row">
                                <span class="font-medium text-gray-500 dark:text-gray-400 w-full sm:w-1/3">Tarikh Mula Kerja :</span>
                                <span class="text-gray-700 dark:text-gray-200 sm:w-2/3">{{ $user->staffDetail?->date_joined ? \Carbon\Carbon::parse($user->staffDetail->date_joined)->format('d M Y') : 'N/A' }}</span>
                            </div>
                            {{-- Gaji mungkin sensitif untuk dipaparkan di sini, terpulang pada keperluan --}}
                            {{-- <div class="flex flex-col sm:flex-row">
                                <span class="font-medium text-gray-500 dark:text-gray-400 w-full sm:w-1/3">Gaji (RM) :</span>
                                <span class="text-gray-700 dark:text-gray-200 sm:w-2/3">{{ $user->staffDetail?->salary ? number_format($user->staffDetail->salary, 2) : 'N/A' }}</span>
                            </div> --}}
                        </div>
                    </div>

                </div> {{-- Tutup div p-6 --}}
            </div> {{-- Tutup div bg-white --}}
        </div> {{-- Tutup div max-w-4xl --}}
    </div> {{-- Tutup div py-12 --}}

</x-app-layout> {{-- TAG PENUTUP SEPATUTNYA DI SINI, DI HUJUNG SEKALI --}}