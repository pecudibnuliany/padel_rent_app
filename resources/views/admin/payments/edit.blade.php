@extends('layouts.admin')

@section('title', 'Edit Status Pembayaran | Padel')

@section('content')
    <div class="flex">
        @include('components.sidebar') <!-- Sidebar -->

        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Edit Status Pembayaran</h1>

            <div class="w-full mt-6 max-w-lg mx-auto bg-white rounded-lg shadow-lg p-6">
                <!-- Form Edit Status -->
                <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- ID Pemesanan -->
                    <div class="mb-4">
                        <label for="booking_id" class="block text-gray-700 font-bold mb-2">ID Pemesanan:</label>
                        <input type="text" id="booking_id" name="booking_id" value="{{ $payment->booking_id }}"
                               class="w-full px-3 py-2 border rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                               disabled>
                    </div>

                    <!-- Jumlah -->
                    <div class="mb-4">
                        <label for="amount" class="block text-gray-700 font-bold mb-2">Jumlah:</label>
                        <input type="text" id="amount" name="amount" value="{{ number_format($payment->amount, 2, ',', '.') }}"
                               class="w-full px-3 py-2 border rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                               disabled>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="mb-4">
                        <label for="payment_method" class="block text-gray-700 font-bold mb-2">Metode Pembayaran:</label>
                        <input type="text" id="payment_method" name="payment_method" value="{{ $payment->payment_method }}"
                               class="w-full px-3 py-2 border rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                               disabled>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="block text-gray-700 font-bold mb-2">Status Pembayaran:</label>
                        <select id="status" name="status" class="w-full px-3 py-2 border rounded-lg">
                            <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="paid" {{ $payment->status == 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Gagal</option>
                            <option value="checked" {{ $payment->status == 'checked' ? 'selected' : '' }}>Mengecek Pembayaran</option>
                        </select>
                    </div>

                    <!-- Bukti Pembayaran -->
                    <div class="mb-4">
                        <label for="payment_proof" class="block text-gray-700 font-bold mb-2">Bukti Pembayaran:</label>
                        @if($payment->payment_proof)
                            <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank" class="text-blue-500">
                                Lihat Bukti Pembayaran
                            </a>
                        @else
                            <span class="text-red-500">Tidak Ada Bukti</span>
                        @endif
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
