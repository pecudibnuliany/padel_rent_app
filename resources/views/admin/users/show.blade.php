@extends('layouts.admin')

@section('title', 'Detail User | Padel')

@section('content')
    <div class="flex">
        @include('components.sidebar') <!-- Memanggil Sidebar -->

        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Detail User</h1>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <!-- Nama User -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama:</label>
                    <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $user->name }}</p>
                </div>

                <!-- Email User -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                    <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $user->email }}</p>
                </div>

                <!-- Tanggal Bergabung (Opsional) -->
                <div class="mb-4">
                    <label for="created_at" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Bergabung:</label>
                    <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $user->created_at->format('d-m-Y') }}</p>
                </div>

                <!-- Tombol Kembali -->
                <div class="flex justify-end">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-md">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
