<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Console\Command;

class CancelExpiredBookings extends Command
{
    protected $signature = 'bookings:cancel-expired';
    protected $description = 'Cancel bookings that have expired without payment';

    public function handle()
    {
        // Ambil semua booking yang kadaluarsa dengan status 'pending'
        $expiredBookings = Booking::where('expired_at', '<', Carbon::now())
            ->where('status', 'pending')
            ->get();

        foreach ($expiredBookings as $booking) {
            // Update status booking menjadi 'canceled' dan set expired_at ke null
            $booking->update(['status' => 'canceled', 'expired_at' => null]);

            // Mengembalikan jadwal menjadi tersedia dan menghapus tanggalnya
            $schedule = Schedule::find($booking->schedule_id);
            if ($schedule) {
                // Perbarui jadwal menjadi tersedia dan hapus tanggal booking
                $schedule->update([
                    'is_available' => true,
                    'date' => null,
                ]);
            }

            // Output informasi ke console
            $this->info('Booking ' . $booking->id . ' dibatalkan karena kadaluarsa.');
        }
    }
}
