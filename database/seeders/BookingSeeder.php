<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Field;
use App\Models\Schedule;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $users = User::pluck('id')->toArray();
        $fields = Field::pluck('id')->toArray();
        $schedules = Schedule::pluck('id')->toArray();

        for ($i = 0; $i < 100; $i++) {
            $userId = $users[array_rand($users)];
            $fieldId = $fields[array_rand($fields)];
            $scheduleId = $schedules[array_rand($schedules)];
            $date = Carbon::now()->addDays(rand(-10, 10))->format('Y-m-d');
            $statuses = ['pending', 'confirmed', 'completed', 'canceled'];
            $status = $statuses[array_rand($statuses)];

            Booking::create([
                'user_id' => $userId,
                'field_id' => $fieldId,
                'schedule_id' => $scheduleId,
                'booking_name' => 'Booking ' . ($i + 1),
                'phone_number' => '08' . rand(1000000000, 9999999999),
                'status' => $status,
                'expired_at' => Carbon::now()->addMinutes(rand(1, 120)),
            ]);
        }
    }
}