{{-- resources/views/admin/manage-staff.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengurusan Staf (Admin)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __("Senarai Staf") }}
                        </h3>
                       <a href="{{ route('admin.staf.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-300 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-indigo-400 dark:hover:bg-indigo-400 active:bg-indigo-500 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('+ Tambah Staf Baru') }}
                        </a>

                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Bil
                                    </th>
                                    {{-- AWAL TAMBAHAN: KOLUM GAMBAR PROFIL --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Gambar
                                    </th>
                                    {{-- AKHIR TAMBAHAN: KOLUM GAMBAR PROFIL --}}
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Emel
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Peranan
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tindakan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($stafUsers as $key => $staf)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $stafUsers->firstItem() + $key }}
                                        </td>
                                        {{-- AWAL TAMBAHAN: SEL DATA GAMBAR PROFIL --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @if ($staf->staffDetail && $staf->staffDetail->profile_image_path)
                                                <img src="{{ asset('storage/' . $staf->staffDetail->profile_image_path) }}" alt="{{ $staf->name }}" class="h-6.1 w-6.1 rounded-full object-cover">
                                            @else
                                                {{-- Papar placeholder atau tiada gambar --}}
                                                <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs text-gray-500 dark:text-gray-400">
                                                    Tiada
                                                </div>
                                            @endif
                                        </td>
                                        {{-- AKHIR TAMBAHAN: SEL DATA GAMBAR PROFIL --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $staf->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $staf->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $staf->role->value }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
    <div class="flex items-center space-x-3"> {{-- Menggunakan flex untuk susun ikon --}}
        {{-- IKON VIEW --}}
        <a href="{{ route('admin.staf.show', ['user' => $staf->id]) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="Lihat Detail">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
            </svg>
        </a>

        {{-- IKON EDIT --}}
        <a href="{{ route('admin.staf.edit', ['user' => $staf->id]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="Edit Staf">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
            </svg>
        </a>

        {{-- IKON PADAM DALAM BORANG --}}
        <form method="POST" action="{{ route('admin.staf.destroy', ['user' => $staf->id]) }}" class="inline" title="Padam Staf">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                    onclick="return confirm('Anda pasti mahu padam staf ini? Maklumat yang dipadam tidak boleh dikembalikan.')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </form>
    </div>
</td>
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- Ubah colspan kepada 6 sebab kita tambah satu kolum --}}
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-300">
                                            Tiada rekod staf ditemui.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $stafUsers->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>