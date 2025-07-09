@extends('layouts.landing')

@section('title', 'Landing Page')

@section('content')
    @include('components.navbar')
    <!-- Hero Section -->
    <div class="relative bg-cover bg-center h-screen" style="background-image: url('/assets/img/lapanganfutsal.jpg');" id="beranda">
        <!-- Weather Bar -->
        <div id="weather" class="absolute top-4 left-4 bg-black bg-opacity-50 text-white p-4 rounded-lg flex items-center space-x-4 z-10">
            @if(isset($weatherDescription) && isset($temperature))
                <div class="flex items-center">
                    <img src="https://openweathermap.org/img/wn/{{ $weatherIcon }}@2x.png" alt="Weather Icon" class="w-16 h-16 filter hue-rotate-180 saturate-200">
                    <div>
                        <p id="weather-description" class="text-xl">{{ $weatherDescription }}</p>
                        <p id="weather-temp" class="text-2xl font-bold">{{ $temperature }}°C</p>
                        <p id="weather-city" class="text-sm mt-2">Jakarta</p>
                    </div>
                </div>
            @elseif(isset($error))
                <p class="text-xl text-red-500">{{ $error }}</p>
            @else
                <p class="text-xl">Memuat data cuaca...</p>
            @endif
        </div>

        <div class="flex items-center justify-center h-full bg-black bg-opacity-50">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">Reservasi Lapangan Padel</h1>
                <p class="text-lg md:text-xl mb-6">Pesan lapangan padel dengan mudah, cepat, dan aman.</p>
                <a href="#fields" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full text-lg">Lihat Lapangan</a>
            </div>
        </div>
    </div>

    <!-- about us -->
    <section class="bg-gray-100" id="aboutus">
        <div class="container mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-8">
                <div class="max-w-lg">
                    <h2 class="text-3xl font-extrabold text-gray-800 mb-8 text-center">Tentang Kami</h2>
                    <p class="mt-4 text-gray-600 text-lg">
                        Sistem Reservasi Lapangan Padel kami hadir untuk memberikan pengalaman bermain Padel terbaik bagi semua penggemar olahraga.
                        Kami menawarkan berbagai lapangan Padel dengan fasilitas modern dan lokasi strategis. Tujuan kami adalah memberikan kemudahan bagi
                        Anda dalam melakukan reservasi lapangan secara cepat dan efisien. Kami berkomitmen untuk menyediakan layanan pelanggan yang luar biasa,
                        dengan tim yang siap membantu menjawab pertanyaan dan memberikan informasi yang Anda butuhkan. Selain harga yang kompetitif, kami memastikan
                        transparansi dalam setiap transaksi, sehingga Anda bisa merasa aman saat melakukan reservasi. Dengan dukungan terhadap komunitas Padel lokal
                        melalui turnamen dan acara, kami percaya bahwa olahraga bisa mempererat hubungan antar pemain. Jika Anda mencari layanan reservasi lapangan
                        Padel yang terpercaya dan berkualitas, Anda telah berada di tempat yang tepat. Kami menantikan kesempatan untuk melayani Anda!
                    </p>
                </div>
                <div class="mt-12 md:mt-0">
                    <img src="https://images.unsplash.com/photo-1531973576160-7125cd663d86" alt="About Us Image" class="object-cover rounded-lg shadow-md">
                </div>
            </div>
        </div>
    </section>

    <!-- Lapangan Tersedia -->
    <div class="container mx-auto py-12">
        <h2 class="text-3xl font-extrabold text-center mb-12" id="fields">Lapangan Tersedia</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if(isset($fields) && $fields->isNotEmpty())
                @foreach($fields as $field)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $field->photo) }}" alt="{{ $field->name }}" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-xl font-bold">{{ $field->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ $field->location }}</p>
                            <p class="text-gray-600">{{ $field->description }}</p>
                            <p class="text-blue-600 font-bold mt-4">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }} / jam</p>
                            {{-- {{ route('reservasi.create', $field->id) }} --}}
                            @auth
                            <button
                                onclick="openModal({{ $field->id }}, '{{ $field->name }}', '{{ $field->price_per_hour }}')"
                                class="mt-4 block bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded">
                                Pesan Sekarang
                            </button>
                            @else
                                <a href="{{ route('login') }}" class="mt-4 block bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded">
                                    Pesan Sekarang
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            @else
                <p>Tidak ada lapangan yang tersedia.</p>
            @endif
        </div>
    </div>

    <!-- Modal Pop-Up -->
    <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 overflow-y-auto max-h-screen">
            <h2 class="text-2xl font-bold mb-6 text-center">Pesan Lapangan</h2>
            <form action="{{ route('user.bookings.store') }}" method="POST">
                @csrf
                <!-- Field ID (Hidden) -->
                <input type="hidden" id="field_id" name="field_id">

                <!-- Pilih Lapangan -->
                <div class="mb-4">
                    <label for="field_name" class="block text-sm font-medium text-gray-700">Lapangan</label>
                    <input type="text" id="field_name" name="field_name" readonly
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100">
                </div>

                <!-- Pilih Tanggal -->
                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700">Pilih Tanggal</label>
                    <input type="date" id="date" name="date" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <!-- Harga -->
                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700">Harga per Jam</label>
                    <input type="text" id="price" name="price" readonly
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100">
                </div>

                <!-- Pilih Jadwal -->
                <div class="mb-4">
                    <label for="schedule_id" class="block text-sm font-medium text-gray-700">Pilih Jadwal</label>
                    <select name="schedule_id" id="schedule_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Pilih Jadwal</option>
                    </select>
                </div>

                <!-- Total Harga -->
                <div class="mb-4">
                    <label for="booking_name" class="block text-sm font-medium text-gray-700">Total Harga</label>
                    <span id="total_price" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2">Rp 0</span>
                </div>

                <!-- Atas Nama -->
                <div class="mb-4">
                    <label for="booking_name" class="block text-sm font-medium text-gray-700">Atas Nama</label>
                    <input type="text" name="booking_name" id="booking_name" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <!-- Nomor Telepon -->
                <div class="mb-4">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" name="phone_number" id="phone_number" required
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>

                <!-- Konfirmasi -->
                <button id="konfirmasiPesananBtn" type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                    Konfirmasi Pesanan
                </button>
            </form>

            <!-- Batal -->
            <button onclick="closeModal()" class="mt-4 w-full text-red-500 font-medium py-2 px-4 rounded-lg">
                Batal
            </button>
        </div>
    </div>



    <!-- Informasi Layanan -->
    <div class="bg-gray-100 py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-extrabold mb-8" id="layanan">Kenapa Pilih Kami?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Mudah & Cepat -->
                <div class="p-10 bg-white shadow-lg rounded-lg transition-transform transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto mb-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16l-4-4m0 0l4-4m-4 4h16M4 12h16m-6 4h6M10 8h6" />
                    </svg>
                    <h3 class="text-2xl font-bold mb-2">Mudah & Cepat</h3>
                    <p class="text-lg text-gray-600">Proses reservasi lapangan Padel kami mudah dan cepat, hanya dengan beberapa klik.</p>
                </div>

                <!-- Jaminan Lapangan -->
                <div class="p-10 bg-white shadow-lg rounded-lg transition-transform transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto mb-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16V8a4 4 0 011.38-3.03m13.17-.59A4 4 0 0116 8v8a4 4 0 01-4 4m4-12H8m6 0h2M6 8v8a4 4 0 004 4m6-12v8" />
                    </svg>
                    <h3 class="text-2xl font-bold mb-2">Jaminan Lapangan</h3>
                    <p class="text-lg text-gray-600">Kami menjamin lapangan yang dipesan tersedia sesuai dengan waktu yang dipilih.</p>
                </div>

                <!-- Pembayaran Aman -->
                <div class="p-10 bg-white shadow-lg rounded-lg transition-transform transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto mb-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2-2m0 0l2 2m-2-2v6m0-6a9 9 0 11-6.707 14.364M15 9a9 9 0 110-18 9 9 0 016.707 2.636" />
                    </svg>
                    <h3 class="text-2xl font-bold mb-2">Pembayaran Aman</h3>
                    <p class="text-lg text-gray-600">Kami menyediakan metode pembayaran yang aman dan dapat dipercaya.</p>
                </div>
            </div>
        </div>
    </div>



    <!-- Visit us section -->
    <section class="bg-white">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:py-20 lg:px-8">
            <div class="max-w-2xl lg:max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-extrabold text-gray-900" id="contactUs">Lokasi Kami</h2>
                {{-- <p class="mt-3 text-lg text-gray-500">Let us serve you the best</p> --}}
            </div>
            <div class="mt-8 lg:mt-20">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <div class="max-w-full mx-auto rounded-lg overflow-hidden">
                            <div class="border-t border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-bold text-gray-900">Kontak</h3>
                                <p class="mt-1 font-bold text-gray-600"><a href="tel:+123">Phone: +62
                                        21 2948 3757</a></p>
                                <a class="flex m-1" href="tel:+622129483757">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="flex items-center justify-between h-10 w-30 rounded-md bg-blue-500 hover:bg-blue-700 text-white p-2">
                                            <!-- Heroicon name: phone -->
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                            </svg>
                                            Hubungi Sekarang
                                        </div>
                                    </div>

                                </a>
                            </div>
                            <div class="border-t border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">Alamat Kami</h3>
                                <p class="mt-1 text-gray-600">Jl. Raya Kalimalang No.18, RT.18/RW.3, Pd. Bambu, Kec. Duren Sawit, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13430</p>
                            </div>
                            <div class="border-t border-gray-200 px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">Jam Operasional</h3>
                                <p class="mt-1 text-gray-600">Senin - Minggu : 10.00 Pagi - 21.00 Malam</p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg overflow-hidden order-none sm:order-first">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.1847483323163!2d106.89177317499058!3d-6.239364593748916!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f33ecaedb61d%3A0x2cb12991b7dda936!2sMall%20Cipinang%20Indah!5e0!3m2!1sid!2sid!4v1752055522150!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                    </div>

                </div>
            </div>
        </div>
    </section>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const dateInput = document.getElementById('date');
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today); // Menetapkan batas minimum tanggal
    });


              document.querySelector('#konfirmasiPesananBtn').addEventListener('click', function(event) {
    event.preventDefault(); // Mencegah form submit langsung
    Swal.fire({
        title: 'Konfirmasi Pesanan',
        text: 'Apakah Anda yakin ingin melakukan pemesanan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Pesan Sekarang!',
        cancelButtonText: 'Batal',
        customClass: {
            confirmButton: 'bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded',
            cancelButton: 'bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded'
        }
    }).then(result => {
        if (result.isConfirmed) {
            // Submit form jika user mengkonfirmasi
            event.target.closest('form').submit();

            Swal.fire({
                title: 'Pesanan Berhasil!',
                text: 'Pesanan Anda telah berhasil diproses.',
                icon: 'success',
                showConfirmButton: false, // Menghilangkan tombol konfirmasi
                timer: 3000, // Durasi tampilan dalam milidetik
                didOpen: () => {
                    // Menambahkan animasi setelah alert muncul
                    Swal.getPopup().classList.add('animate__animated', 'animate__fadeIn');
                }
            });
        }
    });
});

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
                        // Format the time to be in "09:00 - 10:00" format
                        let formattedTime = schedule.start_time.substring(0, 5) + ' - ' + schedule.end_time.substring(0, 5);

                        // Create option element with formatted schedule
                        let option = document.createElement('option');
                        option.value = schedule.id;
                        option.textContent = `${schedule.day} : ${formattedTime}`;
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
            let pricePerHour = parseInt(document.getElementById('price').value.replace('Rp ', '').replace('.', '').replace(',','')); // Remove any formatting

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


        function openModal(fieldId, fieldName, fieldPrice) {
            document.getElementById('field_id').value = fieldId;
            document.getElementById('field_name').value = fieldName;

            // Menghilangkan .00 pada harga dengan membulatkan harga jika perlu
            let formattedPrice = parseFloat(fieldPrice).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

            document.getElementById('price').value = `Rp ${formattedPrice}`;
            document.getElementById('bookingModal').classList.remove('hidden');
        }


        // Function untuk menutup modal
        function closeModal() {
            document.getElementById('bookingModal').classList.add('hidden');
        }

        // Ganti dengan API key kamu
        const apiKey = 'e4eef249a39532aff45411e08ed49442'; // Ganti dengan API key yang kamu dapatkan
        const city = 'Jakarta'; // Ganti dengan kota yang diinginkan
        const units = 'metric'; // Untuk mendapatkan suhu dalam Celsius

        // Mengambil data cuaca dari OpenWeatherMap API
        fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&units=${units}&appid=${apiKey}`)
            .then(response => response.json())
            .then(data => {
                const weatherDescription = data.weather[0].description;
                const temperature = data.main.temp;

                // Menampilkan data cuaca di halaman
                document.getElementById('weather-description').textContent = `Cuaca: ${weatherDescription}`;
                document.getElementById('weather-temp').textContent = `${temperature}°C`;
            })
            .catch(error => {
                console.error('Error fetching weather data:', error);
            });
    </script>

    <!-- Footer -->
    @include('components.footer')

@endsection
