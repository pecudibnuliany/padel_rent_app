@extends('layouts.admin')

@section('title', 'Data Jadwal | Padel')

@section('content')
    <div class="flex">
        @include('components.sidebar') <!-- Sidebar -->

        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Data Jadwal</h1>

            <div class="mb-4">
                <!-- Tombol Tambah Jadwal -->
                <a href="{{ route('admin.schedules.create') }}" class="inline-flex items-center bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i> Tambah Jadwal
                </a>
            </div>

            <div class="w-full mt-6">
                <div class="bg-white overflow-auto mx-auto rounded-lg" style="max-width: 1200px;">
                    <!-- Tabel Jadwal -->
                    <table class="min-w-full bg-white mx-auto text-center">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Lapangan</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Hari</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Tanggal Pemesanan</th> <!-- Menambahkan kolom Tanggal -->
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Jam Mulai</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Jam Selesai</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Berulang</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Ketersediaan</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach($schedules as $schedule)
                                <tr class="border-b">
                                <td class="py-3 px-4">{{ $schedule->field->name }}</td>
                                <td class="py-3 px-4">{{ $schedule->day }}</td> <!-- Menampilkan hari -->
                                <td class="py-3 px-4">
                                    @if($schedule->date)
                                        {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }} <!-- Menampilkan tanggal -->
                                    @else
                                        <span class="text-red-500">Belum Ditetapkan</span>
                                    @endif
                                </td> <!-- Menampilkan Tanggal -->
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                <td class="py-3 px-4">
                                    @if($schedule->is_recurring)
                                        <span class="text-green-500 font-semibold">Ya</span>
                                    @else
                                        <span class="text-red-500 font-semibold">Tidak</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($schedule->is_available)
                                        <span class="text-green-500 font-semibold">Tersedia</span>
                                    @else
                                        <span class="text-red-500 font-semibold">Tidak Tersedia</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 flex justify-center space-x-2">
                                    <!-- Tombol View -->
                                    <a href="{{ route('admin.schedules.show', $schedule->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-eye mr-2"></i> View
                                    </a>

                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin.schedules.edit', $schedule->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit mr-2"></i> Edit
                                    </a>

                                    <!-- Tombol Hapus dengan konfirmasi -->
                                    <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash-alt mr-2"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Pagination --}}
                <div class="mt-4 flex justify-center">
                    {{ $schedules->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
