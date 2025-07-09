@extends('layouts.admin')

@section('title', 'Data Lapangan | Padel')

@section('content')
    <div class="flex">
        @include('components.sidebar') <!-- Sidebar -->

        <!-- Content Wrapper -->
        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Data Lapangan</h1>

            <div class="mb-4">
                <a href="{{ route('admin.fields.create') }}" class="inline-flex items-center bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i> Tambah Lapangan
                </a>
            </div>

            <div class="w-full mt-6">
                <div class="bg-white overflow-auto rounded-lg shadow">
                    <table class="min-w-full bg-white text-center">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm">Nama</th>
                                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm">Lokasi</th>
                                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm">Deskripsi</th>
                                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm">Foto</th>
                                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm">Harga</th>
                                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach($fields as $field)
                            <tr class="border-b">
                                <td class="py-3 px-4 text-center align-middle">{{ $field->name }}</td>
                                <td class="py-3 px-4 text-center align-middle">{{ $field->location }}</td>
                                <td class="py-3 px-4 text-center align-middle">{{ $field->description }}</td>
                                <td class="py-3 px-4 text-center align-middle">
                                    <img src="{{ asset('storage/' . $field->photo) }}" alt="{{ $field->name }}" class="w-20 h-20 object-cover mx-auto">
                                </td>
                                <td class="py-3 px-4 text-center align-middle">{{ number_format($field->price_per_hour, 2) }}</td>
                                <td class="py-3 px-4 text-center align-middle">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('admin.fields.show', $field->id) }}" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                            <i class="fas fa-eye mr-2"></i> View
                                        </a>
                                        <a href="{{ route('admin.fields.edit', $field->id) }}" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center">
                                            <i class="fas fa-edit mr-2"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lapangan ini?');" class="inline">
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
    </div>
@endsection
