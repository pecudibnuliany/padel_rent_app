<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Field;
use App\Models\Booking;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        // Menampilkan semua booking milik user yang login
        $bookings = Booking::all();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        // Ambil semua data lapangan dan jadwal
        $users = User::all();
        $fields = Field::all(); // Ambil semua data lapangan
        $schedules = Schedule::where('is_available', true)->get(); // Ambil semua jadwal

        // Kirim data ke view create
        return view('admin.bookings.create', compact('users','fields', 'schedules'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'date' => 'required|date',
            'schedule_id' => 'required|exists:schedules,id', // pastikan memilih jadwal yang ada
        ]);

        // Mendapatkan jadwal yang dipilih
        $schedule = Schedule::find($request->schedule_id);
        if ($schedule->is_available == 0) {
            return redirect()->back()->with('error', 'Jadwal sudah dipesan.');
        }

        $expiredAt = Carbon::now()->addMinutes(2);

        // Menyimpan booking dengan menambahkan tanggal yang dipilih
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'field_id' => $request->field_id,
            'schedule_id' => $schedule->id,
            'booking_name' => $request->booking_name,
            'phone_number' => $request->phone_number,
            'status' => 'pending',
            'expired_at' => $expiredAt,
        ]);

        // Memperbarui jadwal untuk menyimpan tanggal booking
        $schedule->update([
            'date' => $request->date,  // Menyimpan tanggal booking ke dalam jadwal
            'is_available' => 0
        ]);

        // Jika pengguna adalah admin, arahkan ke halaman admin
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dibuat!');
        }

        // Jika pengguna adalah user biasa, arahkan ke halaman user
        return redirect()->route('user.administration.index')->with('success', 'Booking berhasil dibuat!');
    }

    public function edit(Booking $booking)
    {
        // Pastikan hanya user terkait yang dapat mengedit atau admin
        if (Auth::id() !== $booking->user_id && Auth::user()->role !== 'admin') {
            return redirect()->route('admin.bookings.index')->with('error', 'Anda tidak memiliki izin untuk mengedit booking ini.');
        }
        
        $booking = $booking->load('schedule'); 
        $fields = Field::all();
        $schedules = Schedule::all();
        return view('admin.bookings.edit', compact('booking', 'fields', 'schedules'));
    }

    public function update(Request $request, Booking $booking)
    {
        // Memastikan hanya admin atau pemilik booking yang bisa mengupdate
        if (Auth::id() !== $booking->user_id && Auth::user()->role !== 'admin') {
            return redirect()->route('admin.bookings.index')->with('error', 'Anda tidak memiliki izin untuk mengupdate booking ini.');
        }

        // Validasi input
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'schedule_id' => 'required|exists:schedules,id',
            'booking_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:13',
            'status' => 'required|in:pending,confirmed,completed,canceled',
        ]);

        // Jika jadwal berubah, kembalikan jadwal lama menjadi tersedia
        if ($booking->schedule_id !== $validated['schedule_id']) {
            $oldSchedule = Schedule::find($booking->schedule_id);
            if ($oldSchedule) {
                $oldSchedule->update(['is_available' => true]);
            }
        }

        // Update booking dengan data baru
        $booking->update($validated);

        // Ambil jadwal baru
        $schedule = Schedule::find($validated['schedule_id']);
        if ($schedule) {
            // Perbarui status ketersediaan jadwal berdasarkan status booking
            switch ($validated['status']) {
                case 'pending':
                case 'confirmed':
                    $booking->update([
                        'status' => 'confirmed',
                        'expired_at' => null, // Hilangkan waktu expired
                    ]);
                    $schedule->update(['is_available' => false]); // Jadwal tetap tidak tersedia
                    break;
                case 'completed':
                case 'canceled':
                    $schedule->update(['is_available' => true]); // Jadwal tersedia kembali
                    break;
            }
        }

        // Redirect dengan pesan sukses
        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil diperbarui');
    }



    public function destroy(Booking $booking)
    {
        // Memastikan hanya admin atau pemilik booking yang bisa menghapus
        if (Auth::id() !== $booking->user_id && Auth::user()->role !== 'admin') {
            return redirect()->route('admin.bookings.index')->with('error', 'Anda tidak memiliki izin untuk menghapus booking ini.');
        }

        // Mengembalikan status jadwal menjadi tersedia dan menghapus tanggalnya
        $schedule = Schedule::find($booking->schedule_id);
        if ($schedule) {
            $schedule->update([
                'is_available' => true,
                'date' => null, // Menghapus tanggal booking pada jadwal
            ]);
        }

        // Menghapus booking
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dihapus');
    }

    public function getSchedules(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'date' => 'required|date',
        ]);

        // Konversi tanggal yang dipilih menjadi nama hari
        $day = \Carbon\Carbon::parse($validated['date'])->locale('id')->isoFormat('dddd'); // "Senin", "Selasa", dst.
        
        // Ambil jadwal berdasarkan lapangan, hari, dan status tersedia
        $schedules = Schedule::where('field_id', $validated['field_id'])
                            ->where('day', ucfirst($day))  // Mencocokkan nama hari (case sensitive)
                            ->where('is_available', true) // Pastikan hanya jadwal yang tersedia yang diambil
                            ->get();

        return response()->json($schedules);
    }

    public function indexBookingsUser()
    {
        // Ambil semua booking yang hanya dimiliki oleh user yang sedang login
        $bookings = Booking::where('user_id', Auth::id())->get();

        // Proses data booking
        foreach ($bookings as $booking) {
            // Cek status pembayaran terlebih dahulu
            if ($booking->payment && $booking->payment->status == 'paid') {
                // Jika sudah dibayar, tidak perlu countdown
                $booking->expired_at_display = '-';
            } elseif ($booking->payment && $booking->payment->status == 'failed') {
                

                // Update jadwal menjadi tersedia kembali
                if ($booking->schedule) {
                    $booking->schedule->is_available = true; // Set jadwal menjadi tersedia
                    $booking->schedule->date = null;        // Set tanggal menjadi null
                    $booking->schedule->save();
                }

                // Ubah status booking menjadi 'canceled'
                $booking->status = 'canceled';
                $booking->save();

                // Pembayaran gagal
                $booking->expired_at_display = 'Pembayaran Gagal';
            
            } elseif ($booking->payment && $booking->payment->status == 'checked') {
                // Pembayaran sedang diperiksa
                $booking->expired_at_display = 'Mengecek Pembayaran';
            } else {
                // Jika belum ada pembayaran, cek status booking
                if ($booking->status === 'confirmed') {
                    $booking->expired_at_display = '-';
                } elseif (Carbon::parse($booking->expired_at) < now() && $booking->status === 'pending') {
                    // Jika sudah lewat waktu expired dan status pending, ubah jadi canceled
                    $booking->status = 'canceled';
                    $booking->save();
                    
                    // Update jadwal menjadi tersedia
                    if ($booking->schedule) {
                        $booking->schedule->is_available = true; // Asumsi ada kolom 'is_available'
                        $booking->schedule->save();
                    }
            
                    $booking->expired_at_display = 'Expired';
                } else {
                    // Tampilkan countdown jika belum expired
                    $booking->expired_at_display = Carbon::parse($booking->expired_at)->diffForHumans();
                }
            }
        }
        
        

        // Kirim data booking ke view
        return view('user.administration.index', compact('bookings'));
    }

    public function cancel($bookingId)
    {
        // Mencari booking berdasarkan ID
        $booking = Booking::find($bookingId);

        // Pastikan booking ditemukan dan user yang login adalah pemilik booking
        if (!$booking || $booking->user_id !== auth()->id()) {
            return redirect()->route('user.administration.index')->with('error', 'Booking tidak ditemukan atau Anda tidak memiliki izin untuk membatalkannya.');
        }

        // Mengubah status booking menjadi 'canceled'
        $booking->update(['status' => 'canceled']);

        $schedule = Schedule::find($booking->schedule_id);
        if ($schedule) {
            // Set jadwal menjadi tersedia kembali dan menghapus tanggalnya
            $schedule->update([
                'is_available' => true, // Menandakan jadwal tersedia
                'date' => null // Menghapus tanggal booking pada jadwal
            ]);
        }

        // Redirect ke halaman riwayat booking
        return redirect()->route('user.administration.index')->with('success', 'Booking berhasil dibatalkan.');
    }


    public function scheduleDetails($scheduleId)
    {
        $schedule = Schedule::find($scheduleId);
        if ($schedule) {
            return response()->json([
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ]);
        }
        return response()->json(['error' => 'Schedule not found'], 404);
    }

    public function cancelExpiredBooking($bookingId)
    {
        $booking = Booking::find($bookingId);
        if ($booking && $booking->status == 'pending') {
            // Update status booking menjadi 'canceled'
            $booking->update(['status' => 'canceled']);
            
            // Update jadwal menjadi tersedia dan set tanggal menjadi null
            $schedule = $booking->schedule;
            if ($schedule) {
                $schedule->update([
                    'is_available' => true,
                    'date' => null
                ]);
            }
        }
        return response()->json(['success' => true]);
    }



}

