<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Field;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Payment;

class AdminController extends Controller
{
    public function index()
    {
        $fields = Field::count();
        $bookings = Booking::count(); // Menghitung jumlah total booking
        $users = User::where('role', 'user')->count(); 
        $schedules = Schedule::count();
        $totalPayments = Payment::sum('amount'); // Total seluruh pembayaran
        $payments = Payment::selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status'); // Statistik pembayaran berdasarkan status

        return view('admin.dashboard', compact('fields', 'bookings', 'users', 'schedules', 'totalPayments', 'payments'));
    }
}
