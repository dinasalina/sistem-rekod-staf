{{-- resources/views/superadmin/settings.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tetapan Super Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Selamat datang ke halaman tetapan Super Admin!") }}
                    <p class="mt-4">
                        {{-- Di sini nanti kita boleh letak fungsi-fungsi khas untuk Super Admin --}}
                         Senarai Pengurus (Admin), Tetapan Sistem, dll.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>