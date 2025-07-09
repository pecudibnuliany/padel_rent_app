<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use Carbon\Carbon;

class GenerateRecurringSchedules extends Command
{
    protected $signature = 'schedules:generate';
    protected $description = 'Generate recurring schedules for the next week';

    public function handle()
    {
        $today = Carbon::today();

        // Ambil jadwal yang recurring
        $recurringSchedules = Schedule::where('is_recurring', true)->get();

        foreach ($recurringSchedules as $schedule) {
            $newDate = Carbon::parse($schedule->date)->addWeek(); // Tambahkan 1 minggu

            // Cek apakah jadwal sudah ada di minggu depan
            $exists = Schedule::where('field_id', $schedule->field_id)
                ->where('date', $newDate->format('Y-m-d'))
                ->where('start_time', $schedule->start_time)
                ->exists();

            if (!$exists) {
                Schedule::create([
                    'field_id' => $schedule->field_id,
                    'date' => $newDate->format('Y-m-d'),
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'is_available' => $schedule->is_available,
                    'is_recurring' => true,
                ]);
            }
        }

        $this->info('Recurring schedules generated successfully.');
    }
}
