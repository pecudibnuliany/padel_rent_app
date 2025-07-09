@extends('layouts.landing')

@section('title', 'Riwayat Booking | Padel')

@section('content')
@include('components.navbar')

<div class="flex flex-col p-6 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Riwayat Booking</h1>

    <!-- Container Card Riwayat Booking -->
    @if($bookings->isEmpty())
        <div class="text-center text-gray-600">
            <p>Belum ada riwayat booking yang tersedia.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bookings as $booking)
            <div class="bg-white rounded-lg shadow-md p-4">
                <!-- Header Card -->
                <div class="mb-2">
                    <h2 class="text-lg font-semibold text-gray-700">{{ $booking->field->name }}</h2>
                    <p class="text-sm text-gray-500">
                        Tanggal Jadwal : {{ \Carbon\Carbon::parse($booking->schedule->date)->translatedFormat('d F Y') }} <br>
                        Jam Mulai & Jam Selesai : ({{ \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') }} s/d {{ \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') }})
                    </p>
                </div>

                <!-- Detail Booking -->
                <div class="mb-4 space-y-2">
                    <p class="text-gray-600"><span class="font-medium">Atas Nama:</span> {{ $booking->booking_name }}</p>
                    <p class="text-gray-600"><span class="font-medium">No Telepon:</span> {{ $booking->phone_number }}</p>
                    <p class="text-gray-600"><span class="font-medium">Harga:</span> Rp{{ number_format($booking->field->price_per_hour, 0, ',', '.') }} / jam</p>
                    <p class="text-gray-600"><span class="font-medium">Total Harga:</span>
                        Rp{{ number_format((\Carbon\Carbon::parse($booking->schedule->end_time)->diffInHours(\Carbon\Carbon::parse($booking->schedule->start_time))) * $booking->field->price_per_hour, 0, ',', '.') }}
                    </p>
                    <p class="text-gray-600">
                        <span class="font-medium">Batas Pembayaran:</span>
                        <span id="countdown_{{ $booking->id }}">
                            {{ \Carbon\Carbon::parse($booking->expired_at)->diffForHumans() }}
                        </span>
                    </p>
                    <p class="text-gray-600"><span class="font-medium">Status:</span>
                        @if($booking->payment && $booking->payment->status == 'paid')
                            <span class="text-green-500 font-semibold">Sudah Dibayar</span>
                        @elseif($booking->payment && $booking->payment->status == 'failed')
                            <span class="text-red-500 font-semibold">Pembayaran Gagal</span>
                        @elseif($booking->payment && $booking->payment->status == 'checked')
                            <span class="text-blue-500 font-semibold">Mengecek Pembayaran</span>
                        @else
                            <span class="text-yellow-500 font-semibold">Menunggu Pembayaran</span>
                        @endif
                    </p>

                    <p class="text-gray-600">
                        <span class="font-medium">Bukti Pembayaran:</span>
                        @if($booking->payment && $booking->payment->payment_proof)
                            <a href="{{ asset('storage/' . $booking->payment->payment_proof) }}" target="_blank" class="text-blue-500">
                                Lihat Bukti Pembayaran
                            </a>
                        @else
                            <span class="text-red-500">Tidak Ada Bukti</span>
                        @endif
                    </p>
                </div>

                <!-- Tombol Aksi Pembayaran -->
                @if($booking->status == 'pending' && (!$booking->payment || ($booking->payment->status == 'pending')))
                <button class="w-full bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-lg" onclick="openPaymentModal({{ $booking->id }})">
                    Bayar Sekarang
                </button>
                @elseif($booking->payment && $booking->payment->status == 'checked')
                <button disabled class="w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-lg cursor-not-allowed">
                    Menunggu Konfirmasi
                </button>
                @elseif($booking->status == 'canceled')
                <button disabled class="w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-lg cursor-not-allowed">
                    Booking Dibatalkan
                </button>
                @elseif($booking->payment && $booking->payment->status == 'paid')
                <button disabled class="w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-lg cursor-not-allowed">
                    Sudah Dibayar
                </button>
                @endif

                <!-- Tombol Batalkan Pemesanan -->
                @if($booking->status == 'pending' && (!$booking->payment || $booking->payment->status == 'pending'))
                <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded-lg">
                        Batalkan Booking
                    </button>
                </form>
                @endif

            </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal Pilih Metode Pembayaran -->
<div id="paymentModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg p-6 w-1/3">
        <h2 class="text-xl font-semibold mb-4">Pilih Metode Pembayaran</h2>
        <form action="{{ route('user.payments.store', ':id') }}" method="POST" enctype="multipart/form-data" id="paymentForm">
            @csrf
            <input type="hidden" name="booking_id" id="booking_id">
            <div class="mb-4">
                <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none">
                    <option value="cash">Cash (Bayar Langsung)</option>
                    <option value="transfer">Transfer ke Bank</option>
                </select>
            </div>

            <div id="bank_details_section" class="mb-4 hidden">
                <h2 class="font-bold text-blue-600">Transfer Bank BNI</h2>
                <p>Nomor Rekening: 123-456-789</p>
                <div class="mt-4">
                    <label for="payment_proof" class="block text-gray-700">Upload Bukti Pembayaran</label>
                    <input type="file" name="payment_proof" id="payment_proof" accept="image/*" class="mt-1 block w-full">
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-lg">Simpan Pembayaran</button>
                <button type="button" onclick="closePaymentModal()" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded-lg">Batal</button>
            </div>
        </form>
    </div>
</div>


<script>
    document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function (event) {
        // Memastikan form adalah untuk membatalkan pesanan
        if (form.querySelector('button').innerText === "Batalkan Booking") {
            event.preventDefault();
            Swal.fire({
                title: 'Batalkan Pesanan',
                text: 'Apakah Anda yakin ingin membatalkan pemesanan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan Pesanan Sekarang!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded',
                    cancelButton: 'bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded'
                }
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();

                    // Menampilkan SweetAlert jika pembatalan berhasil
                    Swal.fire({
                        title: 'Pesanan Dibatalkan',
                        text: 'Pesanan Anda telah berhasil dibatalkan.',
                        icon: 'success',
                        showConfirmButton: false,  // Menghilangkan tombol konfirmasi
                        timer: 3000, // Durasi tampilkan alert
                        didOpen: () => {
                            Swal.getPopup().classList.add('animate__animated', 'animate__fadeIn');
                        }
                    });
                }
            });
        }
        // Memastikan form adalah untuk pembayaran
        else if (form.querySelector('button').innerText === "Simpan Pembayaran") {
            event.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: 'Apakah Anda yakin ingin melakukan pembayaran?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan Pembayaran',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded',
                    cancelButton: 'bg-gray-300 hover:bg-gray-400 text-black font-bold py-2 px-4 rounded'
                }
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();

                    // Menampilkan SweetAlert jika pembayaran berhasil
                    Swal.fire({
                        title: 'Pembayaran Berhasil',
                        text: 'Pembayaran Anda telah berhasil diproses.' ,
                        icon: 'success',
                        showConfirmButton: false,  // Menghilangkan tombol konfirmasi
                        timer: 3000, // Durasi tampilkan alert
                        didOpen: () => {
                            Swal.getPopup().classList.add('animate__animated', 'animate__fadeIn');
                        }
                    });
                }
            });
        }
    });
});



    // Fungsi untuk membuka modal
    function openPaymentModal(bookingId) {
    const form = document.getElementById('paymentForm');
    const action = form.getAttribute('action').replace(':id', bookingId);
    form.setAttribute('action', action);
    document.getElementById('booking_id').value = bookingId;
    document.getElementById('paymentModal').classList.remove('hidden');
}


    // Fungsi untuk menutup modal
    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    // Fungsi untuk toggle bagian detail bank
    document.querySelectorAll('select[name="payment_method"]').forEach(function (element) {
        element.addEventListener('change', function () {
            const bankDetailsSection = document.getElementById('bank_details_section');
            if (this.value === 'transfer') {
                bankDetailsSection.classList.remove('hidden');
            } else {
                bankDetailsSection.classList.add('hidden');
            }
        });
    });
</script>

<script>
    // Fungsi untuk update countdown
    function updateCountdown(bookingId, expiredAt, paymentStatus, bookingStatus) {
        const countdownElement = document.getElementById('countdown_' + bookingId);
        if (bookingStatus === 'canceled') {
        countdownElement.textContent = "Dibatalkan";
        return;
        }

        // Jika status adalah 'paid' atau 'checked', set countdown ke "-"
        if (paymentStatus === 'paid') {
            countdownElement.textContent = "-";
            return;
        } else if (paymentStatus === 'checked') {
            countdownElement.textContent = "-";
            return;
        }

        const expiredAtTime = new Date(expiredAt).getTime();

        const interval = setInterval(function () {
            const now = new Date().getTime();
            const distance = expiredAtTime - now;

            // Jika waktu habis, tampilkan "Expired" dan hentikan interval
            if (distance <= 0) {
                clearInterval(interval);
                countdownElement.textContent = "Expired";
                // Opsional: Panggil fungsi untuk mengubah status booking
                changeBookingStatusToCanceled(bookingId);
            } else {
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                countdownElement.textContent = `${hours}h ${minutes}m ${seconds}s`;
            }
        }, 1000);
    }

    // Inisialisasi countdown untuk setiap booking
    @foreach ($bookings as $booking)
        updateCountdown(
            {{ $booking->id }},
            '{{ \Carbon\Carbon::parse($booking->expired_at)->toIso8601String() }}',
            '{{ $booking->payment ? $booking->payment->status : 'pending' }}',
            '{{ $booking->status }}'
        );
    @endforeach

</script>

@include('components.footer')

@endsection
