@extends('layouts.admin')

@section('title', 'Tambah Lapangan | Padel')

@section('content')
    <div class="flex">
        @include('components.sidebar') <!-- Sidebar -->

        <!-- Content Wrapper -->
        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Tambah Lapangan</h1>

            <form action="{{ route('admin.fields.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Lapangan:</label>
                        <input type="text" name="name" id="name" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Lokasi:</label>
                        <input type="text" name="location" id="location" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi:</label>
                        <textarea name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="photo" class="block text-gray-700 text-sm font-bold mb-2">Foto Lapangan:</label>
                        <input type="file" name="photo" id="photo" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label for="price_per_hour" class="block text-gray-700 text-sm font-bold mb-2">Harga per Jam:</label>
                        <input type="number" name="price_per_hour" id="price_per_hour" step="0.01" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
