@extends('layouts.admin')

@section('title', 'Edit User | Padel')

@section('content')
    <div class="flex">
        @include('components.sidebar') <!-- Memanggil Sidebar -->

        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Edit User</h1>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <!-- Form Edit User -->
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengupdate data pengguna ini?');">
                    @csrf
                    @method('PUT')

                    <!-- Input Nama -->
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama:</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Update -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
