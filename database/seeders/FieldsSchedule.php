<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class FieldsSchedule extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fieldId = 1;
        $startHour = 10; // Mulai jam 10:00
        $endHour = 22;   // Misalnya sampai jam 22:00
        $daysOfWeek = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        ];

        $today = Carbon::create(2025, 7, 7); // Contoh mulai Senin, 7 Juli 2025

        foreach ($daysOfWeek as $index => $dayName) {
            $date = $today->copy()->addDays($index);

            for ($hour = $startHour; $hour < $endHour; $hour++) {
                DB::table('schedules')->insert([
                    'field_id' => $fieldId,
                    'day' => $dayName,
                    'start_time' => sprintf('%02d:00:00', $hour),
                    'end_time' => sprintf('%02d:00:00', $hour + 1),
                    'is_available' => 1,
                    'is_recurring' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'date' => $date->format('Y-m-d'),
                ]);
            }
        }
    }
}
