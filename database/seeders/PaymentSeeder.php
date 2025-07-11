<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Booking;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $bookings = Booking::pluck('id')->toArray();
        $methods = ['cash', 'transfer'];
        $statuses = ['pending', 'paid', 'failed', 'checked'];

        for ($i = 0; $i < 100; $i++) {
            $bookingId = $bookings[array_rand($bookings)];
            $amount = rand(100000, 500000);

            Payment::create([
                'booking_id' => $bookingId,
                'amount' => $amount,
                'status' => $statuses[array_rand($statuses)],
                'payment_method' => $methods[array_rand($methods)],
                'payment_proof' => null, // Atur path file jika ingin testing upload
            ]);
        }
    }
}