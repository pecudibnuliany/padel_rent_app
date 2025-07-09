@extends('layouts.admin')

@section('title', 'Edit Jadwal | Padel')

@section('content')
    <div class="flex">
        @include('components.sidebar') <!-- Sidebar -->

        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Edit Jadwal</h1>

            <div class="w-full mt-6">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="field_id" class="block text-sm font-medium text-gray-700">Lapangan</label>
                            <select name="field_id" id="field_id" class="form-select mt-1 block w-full rounded border-gray-300">
                                @foreach($fields as $field)
                                    <option value="{{ $field->id }}" {{ $schedule->field_id == $field->id ? 'selected' : '' }}>
                                        {{ $field->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="date" id="date" value="{{ old('date', $schedule->date) }}" class="form-input mt-1 block w-full rounded border-gray-300">
                        </div>

                        <div class="mb-4">
                            <label for="start_time" class="block text-sm font-medium text-gray-700">Jam Mulai</label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}" class="form-input mt-1 block w-full rounded border-gray-300">
                        </div>

                        <div class="mb-4">
                            <label for="end_time" class="block text-sm font-medium text-gray-700">Jam Selesai</label>
                            <input type="time" name="end_time" id="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}" class="form-input mt-1 block w-full rounded border-gray-300">
                        </div>

                        <div class="mb-4">
                            <label for="is_available" class="block text-sm font-medium text-gray-700">Ketersediaan</label>
                            <select name="is_available" id="is_available" class="form-select mt-1 block w-full rounded border-gray-300">
                                <option value="1" {{ $schedule->is_available ? 'selected' : '' }}>Tersedia</option>
                                <option value="0" {{ !$schedule->is_available ? 'selected' : '' }}>Tidak Tersedia</option>
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
