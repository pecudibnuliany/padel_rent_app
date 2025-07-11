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
        // Gunakan eager loading dan pagination
        $bookings = Booking::with(['user', 'field', 'schedule'])
        ->orderBy('id')
        ->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $users = User::all();
        $fields = Field::all();
        $schedules = Schedule::where('is_available', true)->get();

        return view('admin.bookings.create', compact('users', 'fields', 'schedules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'date' => 'required|date',
            'schedule_id' => 'required|exists:schedules,id',
            'booking_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:13',
        ]);

        $schedule = Schedule::findOrFail($validated['schedule_id']);
        if (!$schedule->is_available) {
            return back()->with('error', 'Jadwal sudah dipesan.');
        }

        $expiredAt = Carbon::now()->addMinutes(2);

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'field_id' => $validated['field_id'],
            'schedule_id' => $schedule->id,
            'booking_name' => $validated['booking_name'],
            'phone_number' => $validated['phone_number'],
            'status' => 'pending',
            'expired_at' => $expiredAt,
        ]);

        $schedule->update([
            'date' => $validated['date'],
            'is_available' => false,
        ]);

        $route = Auth::user()->role === 'admin' ? 'admin.bookings.index' : 'user.administration.index';
        return redirect()->route($route)->with('success', 'Booking berhasil dibuat!');
    }

    public function edit(Booking $booking)
    {
        $this->authorizeAction($booking);

        $booking->load('schedule');
        $fields = Field::all();
        $schedules = Schedule::all();
        return view('admin.bookings.edit', compact('booking', 'fields', 'schedules'));
    }

    public function update(Request $request, Booking $booking)
    {
        $this->authorizeAction($booking);

        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'schedule_id' => 'required|exists:schedules,id',
            'booking_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:13',
            'status' => 'required|in:pending,confirmed,completed,canceled',
        ]);

        if ($booking->schedule_id !== (int)$validated['schedule_id']) {
            $oldSchedule = Schedule::find($booking->schedule_id);
            if ($oldSchedule) {
                $oldSchedule->update(['is_available' => true]);
            }
        }

        $booking->update($validated);

        $schedule = Schedule::find($validated['schedule_id']);
        if ($schedule) {
            if (in_array($validated['status'], ['pending', 'confirmed'])) {
                $booking->update([
                    'status' => 'confirmed',
                    'expired_at' => null,
                ]);
                $schedule->update(['is_available' => false]);
            } else {
                $schedule->update(['is_available' => true]);
            }
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil diperbarui');
    }

    public function destroy(Booking $booking)
    {
        $this->authorizeAction($booking);

        $schedule = Schedule::find($booking->schedule_id);
        if ($schedule) {
            $schedule->update([
                'is_available' => true,
                'date' => null,
            ]);
        }

        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil dihapus');
    }

    public function getSchedules(Request $request)
    {
        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'date' => 'required|date',
        ]);

        $day = Carbon::parse($validated['date'])->locale('id')->isoFormat('dddd');
        $schedules = Schedule::where('field_id', $validated['field_id'])
            ->where('day', ucfirst($day))
            ->where('is_available', true)
            ->get();

        return response()->json($schedules);
    }

    public function indexBookingsUser()
    {
        $bookings = Booking::with(['payment', 'schedule'])->where('user_id', Auth::id())->get();

        foreach ($bookings as $booking) {
            $booking->expired_at_display = $this->getExpiredAtDisplay($booking);
        }

        return view('user.administration.index', compact('bookings'));
    }

    public function cancel($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            return redirect()->route('user.administration.index')->with('error', 'Booking tidak ditemukan atau Anda tidak memiliki izin untuk membatalkannya.');
        }

        $booking->update(['status' => 'canceled']);

        $schedule = Schedule::find($booking->schedule_id);
        if ($schedule) {
            $schedule->update([
                'is_available' => true,
                'date' => null,
            ]);
        }

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
            $booking->update(['status' => 'canceled']);
            $schedule = $booking->schedule;
            if ($schedule) {
                $schedule->update([
                    'is_available' => true,
                    'date' => null,
                ]);
            }
        }
        return response()->json(['success' => true]);
    }

    // Helper: Authorization
    protected function authorizeAction(Booking $booking)
    {
        if (Auth::id() !== $booking->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk melakukan aksi ini.');
        }
    }

    // Helper: Expired At Display
    protected function getExpiredAtDisplay($booking)
    {
        if ($booking->payment && $booking->payment->status == 'paid') {
            return '-';
        } elseif ($booking->payment && $booking->payment->status == 'failed') {
            if ($booking->schedule) {
                $booking->schedule->update([
                    'is_available' => true,
                    'date' => null,
                ]);
            }
            $booking->update(['status' => 'canceled']);
            return 'Pembayaran Gagal';
        } elseif ($booking->payment && $booking->payment->status == 'checked') {
            return 'Mengecek Pembayaran';
        } else {
            if ($booking->status === 'confirmed') {
                return '-';
            } elseif (Carbon::parse($booking->expired_at) < now() && $booking->status === 'pending') {
                $booking->update(['status' => 'canceled']);
                if ($booking->schedule) {
                    $booking->schedule->update(['is_available' => true]);
                }
                return 'Expired';
            } else {
                return Carbon::parse($booking->expired_at)->diffForHumans();
            }
        }
    }
}

