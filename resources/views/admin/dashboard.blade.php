@extends('layouts.admin')

@section('title', 'Dashboard Admin | Padel')

@section('content')
    <div class="flex">
        @include('components.sidebar') <!-- Memanggil Sidebar -->
        <div class="w-full flex-grow p-6">
            <h1 class="text-3xl text-black pb-6">Dashboard Admin</h1>

            <!-- Konten dashboard utama -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Pengguna -->
                <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-blue-600">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-4 rounded-full">
                            <i class="fas fa-users text-blue-600 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">Total Pengguna</h3>
                            <p class="text-3xl font-bold text-gray-800">{{ $users }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Lapangan -->
                <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-green-600">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-4 rounded-full">
                            <i class="fas fa-futbol text-green-600 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">Total Lapangan</h3>
                            <p class="text-3xl font-bold text-gray-800">{{ $fields }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Jadwal -->
                <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-yellow-600">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-4 rounded-full">
                            <i class="fas fa-calendar-alt text-yellow-600 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">Total Jadwal</h3>
                            <p class="text-3xl font-bold text-gray-800">{{ $schedules }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Booking -->
                <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-purple-600">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-4 rounded-full">
                            <i class="fas fa-clipboard-list text-purple-600 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">Total Booking</h3>
                            <p class="text-3xl font-bold text-gray-800">{{ is_array($bookings) ? array_sum($bookings) : $bookings }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Payment -->
                <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-red-600">
                    <div class="flex items-center">
                        <div class="bg-red-100 p-4 rounded-full">
                            <i class="fas fa-money-check-alt text-red-600 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-700">Total Payment</h3>
                            <p class="text-3xl font-bold text-gray-800">Rp{{ number_format($totalPayments, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Section -->
            <div class="mt-12">
                <h2 class="text-2xl font-semibold text-gray-700 mb-6">Statistik Payment</h2>

                <!-- Grafik Payment -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Statistik Payment</h3>
                    <canvas id="paymentChart" class="w-full"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Grafik Payment
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        new Chart(paymentCtx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Paid', 'Failed'],
                datasets: [{
                    label: 'Payments',
                    data: [
                        {{ $payments['pending'] ?? 0 }},
                        {{ $payments['paid'] ?? 0 }},
                        {{ $payments['failed'] ?? 0 }}
                    ],
                    backgroundColor: ['#FFD700', '#32CD32', '#FF4500'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false // Menonaktifkan legenda
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Payments', // Teks vertikal
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#4A5568'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Status',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#4A5568'
                        }
                    }
                }
            }
        });
    </script>
@endsection
