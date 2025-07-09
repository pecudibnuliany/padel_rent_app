@extends('layouts.admin')

@section('title', 'Pembayaran - Detail Booking')

@section('content')
    <div class="flex">
        @include('components.sidebar')

        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Pembayaran - Detail Booking</h1>

            <div class="bg-white p-6 rounded shadow-md">
                <h2 class="text-2xl font-bold">Detail Booking</h2>
                <p><strong>Lapangan:</strong> {{ $booking->field->name }}</p>
                <p><strong>Jadwal:</strong> {{ \Carbon\Carbon::parse($booking->schedule->date)->format('d M Y') }}
                    ({{ \Carbon\Carbon::parse ($booking->schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') }})</p>
                <p><strong>Total Harga:</strong> Rp {{ number_format($totalPrice, 2, ',', '.') }}</p>

                <form action="{{ route('admin.payments.store', $booking->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mt-4">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" onchange="toggleBankDetails()">
                            <option value="cash">Cash (Bayar Langsung)</option>
                            <option value="transfer">Transfer ke Bank</option>
                        </select>
                    </div>

                    <!-- Bagian untuk Menampilkan Transfer Bank -->
                    <div id="bank_details_section" class="mt-4" style="display: none;">
                        <h2 class="font-bold text-blue-600">Transfer Bank BNI</h2>
                        <img src="/assets/img/bank.png" alt="Logo BNI" class="h-10">
                        <p>Nomor Rekening: 123-456-789</p>

                        <div class="mt-4">
                            <label for="payment_proof" class="block text-gray-700">Upload Bukti Pembayaran</label>
                            <input type="file" name="payment_proof" id="payment_proof" accept="image/*" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-opacity-50">
                        </div>
                    </div>

                    <button type="submit" class="mt-6 inline-flex items-center bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-save"></i> Simpan Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk menampilkan atau menyembunyikan informasi rekening bank
        function toggleBankDetails() {
            const paymentMethod = document.getElementById('payment_method').value;
            const bankDetailsSection = document.getElementById('bank_details_section');

            if (paymentMethod === 'transfer') {
                bankDetailsSection.style.display = 'block'; // Menampilkan bagian bank
            } else {
                bankDetailsSection.style.display = 'none'; // Menyembunyikan bagian bank
            }
        }
    </script>
@endsection
