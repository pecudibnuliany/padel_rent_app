@extends('layouts.admin')

@section('title', 'Detail Jadwal | Padel')

@section('content')
    <div class="flex">
        @include('components.sidebar') <!-- Sidebar -->

        <!-- Content Wrapper -->
        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Detail Jadwal</h1>

            <div class="bg-white shadow-md rounded-lg p-4">
                <h2 class="text-2xl font-semibold mb-4">Lapangan: {{ $schedule->field->name }}</h2>

                <div class="mb-4">
                    <span class="text-gray-700 font-bold">Tanggal: </span>
                    <span class="text-gray-900">{{ $schedule->date }}</span>
                </div>

                <div class="mb-4">
                    <span class="text-gray-700 font-bold">Waktu Mulai: </span>
                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</span>
                </div>

                <div class="mb-4">
                    <span class="text-gray-700 font-bold">Waktu Selesai: </span>
                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                </div>

                <div class="mb-4">
                    <span class="text-gray-700 font-bold">Ketersediaan: </span>
                    <span class="text-gray-900">
                        @if ($schedule->is_available)
                            <span class="text-green-500">Tersedia</span>
                        @else
                            <span class="text-red-500">Tidak Tersedia</span>
                        @endif
                    </span>
                </div>

                <div class="flex space-x-4">
                    <a href="{{ route('admin.schedules.index') }}" class="inline-flex items-center bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
