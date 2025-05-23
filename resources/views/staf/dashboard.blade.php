{{-- resources/views/staf/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Staff') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Mesej selamat datang --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold">Selamat Datang, {{ $user->name }}!</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Ini adalah halaman utama anda. Anda boleh lihat ringkasan maklumat dan akses fungsi berkaitan profil anda di sini.
                    </p>
                </div>
            </div>

            {{-- Kad untuk Ringkasan Profil & Tindakan --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h4 class="text-md font-semibold mb-3 text-gray-700 dark:text-gray-300">Ringkasan Profil Anda</h4>
                    <div class="space-y-2 text-sm mb-4">
                        <p><span class="font-medium text-gray-500 dark:text-gray-400">ID Staf:</span> {{ $user->staffDetail?->staff_id_number ?? 'N/A' }}</p>
                        <p><span class="font-medium text-gray-500 dark:text-gray-400">Emel:</span> {{ $user->email }}</p>
                        <p><span class="font-medium text-gray-500 dark:text-gray-400">Jabatan:</span> {{ $user->staffDetail?->department ?? 'N/A' }}</p>
                        <p><span class="font-medium text-gray-500 dark:text-gray-400">Jawatan:</span> {{ $user->staffDetail?->position ?? 'N/A' }}</p>
                    </div>
                    <div class="mt-4 flex space-x-3">
                        <a href="{{ route('staf.profil.show') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 dark:bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 dark:hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Lihat Profil Penuh') }}
                        </a>
                        <a href="{{ route('staf.profil.edit') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-400 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Kemaskini Profil Saya') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Placeholder untuk Markah Prestasi --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h4 class="text-md font-semibold mb-3 text-gray-700 dark:text-gray-300">Markah Prestasi</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Bahagian ini akan memaparkan markah prestasi anda apabila ia tersedia.
                    </p>
                    {{-- Di sini nanti kita akan letak paparan markah prestasi --}}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>