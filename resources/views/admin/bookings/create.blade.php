@extends('layouts.admin')

@section('title', 'Tambah Booking | Padel')

@section('content')
<div class="flex">
    @include('components.sidebar') <!-- Sidebar -->

    <!-- Content Wrapper -->
    <div class="w-full flex-grow p-6">
        <h1 class="text-3xl text-black pb-6">Tambah Booking</h1>

        <form action="{{ route('admin.bookings.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="field_id" class="block text-sm font-medium text-gray-700">Pilih Lapangan</label>
                <select name="field_id" id="field_id" class="mt-1 block w-full">
                    @foreach($fields as $field)
                    <option value="{{ $field->id }}" data-price="{{ $field->price_per_hour }}">
                        {{ $field->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Input Tanggal -->
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Pilih Tanggal</label>
                <input type="date" name="date" id="date" class="mt-1 block w-full" required>
            </div>

            <!-- Pilihan Jadwal -->
            <div class="mb-4">
                <label for="schedule_id" class="block text-sm font-medium text-gray-700">Pilih Jadwal</label>
                <select name="schedule_id" id="schedule_id" class="mt-1 block w-full">
                    <option value="">Pilih Jadwal</option>
                </select>
            </div>

            <!-- Total Harga -->
            <div class="mb-4">
                <label for="booking_name" class="block text-sm font-medium text-gray-700">Total Harga</label>
                <span id="total_price" class="mt-1 block w-full">Rp 0</span>
            </div>

            <!-- Input Nama Pemesan -->
            <div class="mb-4">
                <label for="booking_name" class="block text-sm font-medium text-gray-700">Nama Pemesan</label>
                <input type="text" name="booking_name" id="booking_name" class="mt-1 block w-full" required>
            </div>

            <!-- Input Nomor Telepon -->
            <div class="mb-4">
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input type="text" name="phone_number" id="phone_number" class="mt-1 block w-full" required>
            </div>

            <button type="submit" class="btn btn-primary">Buat Booking</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('date').addEventListener('change', function() {
        let date = this.value;
        let field_id = document.getElementById('field_id').value;

        console.log(date, field_id); // Add log for debugging

        if (date && field_id) {
            fetch(`/user/bookings/getSchedules?date=${date}&field_id=${field_id}`)
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

    document.getElementById('schedule_id').addEventListener('change', function() {
        let scheduleId = this.value;

        let fieldSelect = document.getElementById('field_id');
        let pricePerHour = parseInt(fieldSelect.options[fieldSelect.selectedIndex].getAttribute('data-price'));

        if (scheduleId && pricePerHour) {
            fetch(`/user/bookings/scheduleDetails/${scheduleId}`)
                .then(response => response.json())
                .then(schedule => {
                    let startTime = schedule.start_time;
                    let endTime = schedule.end_time;

                    // Calculate duration (in hours)
                    let duration = calculateDuration(startTime, endTime);
                    let totalPrice = duration * pricePerHour;

                    // Display total price with proper formatting
                    document.getElementById('total_price').textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
                })
                .catch(error => console.error('Error fetching schedule details:', error));
        }
    });

    function calculateDuration(startTime, endTime) {
        let start = new Date('1970-01-01T' + startTime + 'Z');
        let end = new Date('1970-01-01T' + endTime + 'Z');
        let diff = (end - start) / 1000 / 60 / 60; // Convert milliseconds to hours
        return Math.ceil(diff); // Round up to the nearest hour
    }
</script>

@endsection
