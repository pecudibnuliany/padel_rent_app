@extends('layouts.admin')

@section('title', 'Detail Lapangan | Padel')

@section('content')
    <div class="flex">
        @include('components.sidebar') <!-- Sidebar -->

        <!-- Content Wrapper -->
        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Detail Lapangan</h1>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Lapangan:</label>
                    <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $field->name }}</p>
                </div>

                <div class="mb-4">
                    <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Lokasi:</label>
                    <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $field->location }}</p>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi:</label>
                    <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $field->description }}</p>
                </div>

                <div class="mb-4">
                    <label for="photo" class="block text-gray-700 text-sm font-bold mb-2">Foto Lapangan:</label>
                    @if($field->photo)
                        <img src="{{ asset('storage/' . $field->photo) }}" alt="Foto Lapangan" class="rounded w-full h-auto">
                    @else
                        <p class="text-gray-500">Tidak ada foto</p>
                    @endif
                </div>

                <div class="mb-4">
                    <label for="price_per_hour" class="block text-gray-700 text-sm font-bold mb-2">Harga per Jam:</label>
                    <p class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $field->price_per_hour }}</p>
                </div>

                <a href="{{ route('admin.fields.index') }}" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline block text-center mt-4">Kembali</a>
            </div>
        </div>
    </div>
@endsection
