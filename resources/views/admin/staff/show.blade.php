{{-- resources/views/admin/staff/show.blade.php --}}
<x-app-layout> {{-- Pastikan ini layout yang betul awak guna --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Staf: ') }} {{ $user->name }}
            </h2>
            <div>
                <a href="{{ route('admin.manage') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 dark:hover:bg-gray-700 active:bg-gray-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Kembali ke Senarai') }}
                </a>
                <a href="{{ route('admin.staf.edit', $user->id) }}" class="ml-2 inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-400 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Edit Staf Ini') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100 space-y-6">

                    {{-- Bahagian Gambar Profil & Maklumat Asas Pengguna --}}
                    <div class="flex flex-col sm:flex-row items-center sm:items-center space-y-4 sm:space-y-0 sm:space-x-8">
                        @if ($user->staffDetail && $user->staffDetail->profile_image_path)
                            <img src="{{ asset('storage/' . $user->staffDetail->profile_image_path) }}" alt="{{ $user->name }}" class="h-32 w-32 rounded-lg object-cover border dark:border-gray-700">
                        @else
                            <div class="h-32 w-32 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 border dark:border-gray-600">
                                Tiada Gambar
                            </div>
                        @endif
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="text-2xl font-semibold">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 capitalize">Peranan: {{ $user->role->value }}</p>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200 dark:border-gray-700">

                    {{-- Maklumat Pekerjaan --}}
                    <div>
                        <h4 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">Maklumat Pekerjaan</h4>
                        
                        <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-baseline mb-2">
                            <div class="mr-6 mb-1 sm:mb-0 min-w-[180px]"> {{-- min-w untuk penjajaran jika perlu --}}
                                <span class="font-medium text-gray-500 dark:text-gray-400">ID Staf :</span>
                                <span class="text-gray-700 dark:text-gray-200 ml-1">{{ $user->staffDetail?->staff_id_number ?? 'N/A' }}</span>
                            </div>
                            <div class="mr-6 mb-1 sm:mb-0 min-w-[180px]">
                                <span class="font-medium text-gray-500 dark:text-gray-400">Jabatan :</span>
                                <span class="text-gray-700 dark:text-gray-200 ml-1">{{ $user->staffDetail?->department ?? 'N/A' }}</span>
                            </div>
                            <div class="min-w-[180px]">
                                <span class="font-medium text-gray-500 dark:text-gray-400">Jawatan :</span>
                                <span class="text-gray-700 dark:text-gray-200 ml-1">{{ $user->staffDetail?->position ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-baseline">
                            <div class="mr-6 mb-1 sm:mb-0 min-w-[180px]">
                                <span class="font-medium text-gray-500 dark:text-gray-400">Tarikh Mula Kerja :</span>
                                <span class="text-gray-700 dark:text-gray-200 ml-1">{{ $user->staffDetail?->date_joined ? \Carbon\Carbon::parse($user->staffDetail->date_joined)->format('d M Y') : 'N/A' }}</span>
                            </div>
                            <div class="min-w-[180px]">
                                <span class="font-medium text-gray-500 dark:text-gray-400">Gaji (RM) :</span>
                                <span class="text-gray-700 dark:text-gray-200 ml-1">{{ $user->staffDetail?->salary ? number_format($user->staffDetail->salary, 2) : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4 border-gray-200 dark:border-gray-700">

                    {{-- Maklumat Peribadi --}}
                    <div>
                        <h4 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">Maklumat Peribadi</h4>
                        <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-baseline mb-2">
                            <div class="mr-6 mb-1 sm:mb-0 min-w-[180px]">
                                <span class="font-medium text-gray-500 dark:text-gray-400">No. Telefon :</span>
                                <span class="text-gray-700 dark:text-gray-200 ml-1">{{ $user->staffDetail?->phone_number ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-baseline">
                            <div class="w-full"> {{-- Alamat ambil ruang penuh --}}
                                <span class="font-medium text-gray-500 dark:text-gray-400">Alamat Rumah :</span>
                                <p class="text-gray-700 dark:text-gray-200 ml-1 mt-1">{{ $user->staffDetail?->address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200 dark:border-gray-700">

                    {{-- Maklumat Bank & Kecemasan --}}
                    <div>
                        <h4 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-300">Maklumat Bank & Kecemasan</h4>
                        <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-baseline mb-2">
                            <div class="mr-6 mb-1 sm:mb-0 min-w-[180px]">
                                <span class="font-medium text-gray-500 dark:text-gray-400">Nama Bank :</span>
                                <span class="text-gray-700 dark:text-gray-200 ml-1">{{ $user->staffDetail?->bank_name ?? 'N/A' }}</span>
                            </div>
                            <div class="min-w-[180px]">
                                <span class="font-medium text-gray-500 dark:text-gray-400">No. Akaun Bank :</span>
                                <span class="text-gray-700 dark:text-gray-200 ml-1">{{ $user->staffDetail?->bank_account_number ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-baseline">
                            <div class="mr-6 mb-1 sm:mb-0 min-w-[180px]">
                                <span class="font-medium text-gray-500 dark:text-gray-400">Nama Waris :</span>
                                <span class="text-gray-700 dark:text-gray-200 ml-1">{{ $user->staffDetail?->emergency_contact_name ?? 'N/A' }}</span>
                            </div>
                            <div class="min-w-[180px]">
                                <span class="font-medium text-gray-500 dark:text-gray-400">No. Telefon Waris :</span>
                                <span class="text-gray-700 dark:text-gray-200 ml-1">{{ $user->staffDetail?->emergency_contact_phone ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>