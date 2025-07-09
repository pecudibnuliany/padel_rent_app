@extends('layouts.admin')

@section('title', 'Tambah Jadwal Lapangan | Padel')

@section('content')
    <div class="flex">
        @include('components.sidebar') <!-- Sidebar -->

        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Tambah Jadwal Lapangan</h1>

            <form action="{{ route('admin.schedules.store') }}" method="POST">
                @csrf
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="mb-4">
                        <label for="field_id" class="block text-gray-700 text-sm font-bold mb-2">Pilih Lapangan:</label>
                        <select name="field_id" id="field_id" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">-- Pilih Lapangan --</option>
                            @foreach($fields as $field)
                                <option value="{{ $field->id }}">{{ $field->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="day" class="block text-gray-700 text-sm font-bold mb-2">Hari:</label>
                        <select name="day" id="day" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">-- Pilih Hari --</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="start_time" class="block text-gray-700 text-sm font-bold mb-2">Jam Mulai:</label>
                        <input type="time" name="start_time" id="start_time" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label for="end_time" class="block text-gray-700 text-sm font-bold mb-2">Jam Selesai:</label>
                        <input type="time" name="end_time" id="end_time" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label for="is_recurring">Jadwal Berulang:</label>
                        <input type="checkbox" name="is_recurring" id="is_recurring" value="1">
                    </div>

                    <div class="mb-4">
                        <label for="is_available" class="block text-gray-700 text-sm font-bold mb-2">Ketersediaan:</label>
                        <select name="is_available" id="is_available" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="1">Tersedia</option>
                            <option value="0">Tidak Tersedia</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
