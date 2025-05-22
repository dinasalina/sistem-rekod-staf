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
                                            <a href="{{ route('admin.staf.edit', ['user' => $staf->id]) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">Edit</a>
                                            {{-- <a href="#" class="ml-2 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">Padam</a> --}}

                                            {{-- AWAL BORANG UNTUK PADAM --}}
                                            <form method="POST" action="{{ route('admin.staf.destroy', ['user' => $staf->id]) }}" class="inline ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                        onclick="return confirm('Anda pasti mahu padam staf ini? Maklumat yang dipadam tidak boleh dikembalikan.')">
                                                    Padam
                                                </button>
                                            </form>
                                            {{-- AKHIR BORANG UNTUK PADAM --}}
                                            
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