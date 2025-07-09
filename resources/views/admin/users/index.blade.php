@extends('layouts.admin') <!-- Menyambung ke layout admin -->

@section('title', 'Data User | Admin Dashboard') <!-- Menentukan title halaman -->

@section('content')
    <div class="flex">
        @include('components.sidebar') <!-- Memanggil Sidebar -->

        <!-- Konten utama -->
        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Data User</h1>

            <!-- Tabel Data User -->
            <div class="bg-white shadow-md rounded-lg overflow-auto">
                <table class="min-w-full table-auto text-center"> <!-- Menambahkan text-center -->
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="w-1/3 py-3 px-4 uppercase font-semibold text-sm">Nama</th>
                            <th class="w-1/3 py-3 px-4 uppercase font-semibold text-sm">Email</th>
                            <th class="w-1/3 py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach($users as $user)
                        <tr class="border-b">
                            <td class="w-1/3 py-3 px-4">{{ $user->name }}</td>
                            <td class="w-1/3 py-3 px-4">{{ $user->email }}</td>
                            <td class="w-1/3 py-3 px-4">
                                <div class="flex justify-center space-x-4"> <!-- Menambahkan justify-center -->
                                    <!-- Tombol View -->
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                        <i class="fas fa-eye mr-2"></i> View
                                    </a>
                                    
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                        <i class="fas fa-edit mr-2"></i> Edit
                                    </a>
                                    
                                    <!-- Tombol Hapus dengan konfirmasi -->
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center">
                                            <i class="fas fa-trash-alt mr-2"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection