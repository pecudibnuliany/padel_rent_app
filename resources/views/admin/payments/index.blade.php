@extends('layouts.admin')

@section('title', 'Data Pembayaran | Padel')

@section('content')
    <div class="flex">
        @include('components.sidebar') <!-- Sidebar -->

        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Data Pembayaran</h1>

            <div class="w-full mt-6">
                <div class="bg-white overflow-auto mx-auto rounded-lg" style="max-width: 1200px;">
                    <!-- Tabel Pembayaran -->
                    <table class="min-w-full bg-white mx-auto text-center">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">ID Pemesanan</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Jumlah</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Metode Pembayaran</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Bukti Pembayaran</th> <!-- Kolom Bukti Pembayaran -->
                                <th class="py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach($payments as $payment)
                            <tr class="border-b">
                                <td class="py-3 px-4">{{ $payment->booking_id }}</td>
                                <td class="py-3 px-4">{{ number_format($payment->amount, 2, ',', '.') }}</td>
                                <td class="py-3 px-4">{{ $payment->payment_method }}</td>
                                <td class="py-3 px-4">
                                    @if($payment->status == 'paid')
                                        <span class="text-green-500 font-semibold">Paid</span>
                                    @elseif($payment->status == 'pending')
                                        <span class="text-yellow-500 font-semibold">Pending</span>
                                    @elseif($payment->status == 'checked')
                                        <span class="text-blue-500 font-semibold">Check</span>
                                    @else
                                        <span class="text-red-500 font-semibold">Failed</span>
                                    @endif
                                </td>

                                <!-- Menampilkan Bukti Pembayaran -->
                                <td class="py-3 px-4">
                                    @if($payment->payment_proof)
                                        <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank" class="text-blue-500">
                                           Lihat Bukti Pembayaran
                                        </a>
                                    @else
                                        <span class="text-red-500">Tidak Ada Bukti</span>
                                    @endif
                                </td>

                                <td class="py-3 px-4 flex justify-center space-x-2">
                                    <!-- Tombol View -->
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-eye mr-2"></i> View
                                    </a>

                                    <a href="{{ route('admin.payments.edit', $payment->id) }}" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-edit mr-2"></i> Edit
                                    </a>

                                    <!-- Tombol Hapus dengan konfirmasi -->
                                    <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pembayaran ini?');">
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
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
