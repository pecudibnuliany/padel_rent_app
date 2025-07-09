@extends('layouts.admin')

@section('title', 'Edit Booking | Padel')

@section('content')
    <div class="flex">
        @include('components.sidebar') <!-- Sidebar -->

        <!-- Content Wrapper -->
        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Edit Booking</h1>

            <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Pilih Lapangan -->
                <div class="mb-4">
                    <label for="field_id" class="block text-sm font-medium text-gray-700">Pilih Lapangan</label>
                    <select name="field_id" id="field_id" class="mt-1 block w-full">
                        @foreach($fields as $field)
                            <option value="{{ $field->id }}" {{ $field->id == $booking->field_id ? 'selected' : '' }}>
                                {{ $field->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Tanggal -->
                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700">Pilih Tanggal</label>
                    <input
                        type="date"
                        name="date"
                        id="date"
                        class="mt-1 block w-full"
                        value="{{ old('date', $booking->schedule ? \Carbon\Carbon::parse($booking->schedule->date)->format('Y-m-d') : '') }}"
                        required
                    >
                </div>



                <!-- Pilihan Jadwal -->
                <div class="mb-4">
                    <label for="schedule_id" class="block text-sm font-medium text-gray-700">Pilih Jadwal</label>
                    <select name="schedule_id" id="schedule_id" class="mt-1 block w-full">
                        <option value="">Pilih Jadwal</option>
                        @foreach($schedules as $schedule)
                            <option value="{{ $schedule->id }}" {{ $schedule->id == $booking->schedule_id ? 'selected' : '' }}>
                                {{ $schedule->day }} - {{ $schedule->start_time }} - {{ $schedule->end_time }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Nama Pemesan -->
                <div class="mb-4">
                    <label for="booking_name" class="block text-sm font-medium text-gray-700">Nama Pemesan</label>
                    <input type="text" name="booking_name" id="booking_name" class="mt-1 block w-full" value="{{ $booking->booking_name }}" required>
                </div>

                <!-- Input Nomor Telepon -->
                <div class="mb-4">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" name="phone_number" id="phone_number" class="mt-1 block w-full" value="{{ $booking->phone_number }}" required>
                </div>

                <!-- Pilihan Status -->
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status Pemesanan</label>
                    <a href="{{ route('admin.payments.edit', $booking->id) }}" class="text-blue-500 hover:text-blue-700">
                        Cek Pembayaran
                    </a>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Update Booking
                </button>
            </form>
        </div>
    </div>

    <script>
        // Ketika tanggal dipilih, kirimkan tanggal ke server untuk mencari jadwal
        document.getElementById('date').addEventListener('change', function() {
            let date = this.value;
            let field_id = document.getElementById('field_id').value;

            console.log(date, field_id); // Debugging log

            if (date && field_id) {
                fetch(`/admin/bookings/getSchedules?date=${date}&field_id=${field_id}`)
                    .then(response => response.json())
                    .then(data => {
                        let scheduleSelect = document.getElementById('schedule_id');
                        scheduleSelect.innerHTML = '<option value="">Pilih Jadwal</option>';

                        if (data.length > 0) {
                            data.forEach(schedule => {
                                let option = document.createElement('option');
                                option.value = schedule.id;
                                option.textContent = `${schedule.day} - ${schedule.start_time} - ${schedule.end_time}`;
                                scheduleSelect.appendChild(option);
                            });
                        } else {
                            let option = document.createElement('option');
                            option.textContent = 'Tidak ada jadwal tersedia';
                            scheduleSelect.appendChild(option);
                        }
                    })
                    .catch(error => console.error('Error fetching schedules:', error));
            }
        });
    </script>
@endsection
