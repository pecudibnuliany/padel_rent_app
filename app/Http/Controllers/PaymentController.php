<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Menampilkan daftar pembayaran
     */
    public function index()
    {
        // Gunakan eager loading dan pagination
        if (Auth::user()->role === 'admin') {
            $payments = Payment::with(['booking.user', 'booking.field'])
            ->orderBy('booking_id')
            ->paginate(10);
        } else {
            $payments = Payment::with(['booking.user', 'booking.field'])
                ->whereHas('booking', function ($q) {
                    $q->where('user_id', Auth::id());
                })
                ->orderBy('booking_id')
                ->paginate(10);
        }

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Menampilkan form untuk membuat pembayaran baru
     */
    public function create($bookingId)
    {
        $booking = Booking::with('field')->findOrFail($bookingId);
        $totalPrice = $booking->field->price_per_hour;

        return view('admin.payments.create', compact('booking', 'totalPrice'));
    }

    /**
     * Menyimpan data pembayaran baru
     */
    public function store(Request $request, $bookingId)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,transfer',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $booking = Booking::with('field')->findOrFail($bookingId);
        $totalPrice = $booking->field->price_per_hour;

        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        Payment::create([
            'booking_id' => $booking->id,
            'amount' => $totalPrice,
            'status' => 'checked',
            'payment_method' => $request->payment_method,
            'payment_proof' => $paymentProofPath,
        ]);

        $booking->update(['status' => 'pending']);

        $route = Auth::user()->role === 'admin' ? 'admin.payments.index' : 'user.administration.index';
        return redirect()->route($route)->with('success', 'Pembayaran berhasil dibuat!');
    }

    /**
     * Menampilkan detail pembayaran
     */
    public function show(Payment $payment)
    {
        $payment->load(['booking.user', 'booking.field']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Menampilkan form untuk mengedit pembayaran
     */
    public function edit(Payment $payment)
    {
        $this->authorizePayment($payment);
        $payment->load(['booking.user', 'booking.field']);
        return view('admin.payments.edit', compact('payment'));
    }

    /**
     * Memperbarui status pembayaran
     */
    public function update(Request $request, Payment $payment)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.payments.index')->with('error', 'Anda tidak memiliki izin untuk memperbarui status pembayaran.');
        }

        $request->validate([
            'status' => 'required|in:pending,paid,failed,checked',
        ]);

        $payment->update(['status' => $request->status]);

        $booking = $payment->booking;
        if ($request->status === 'paid') {
            $booking->update(['status' => 'confirmed']);
        } elseif ($request->status === 'failed') {
            $booking->update(['status' => 'canceled']);
        }

        return redirect()->route('admin.payments.index')->with('success', 'Status pembayaran berhasil diperbarui!');
    }

    /**
     * Menghapus pembayaran
     */
    public function destroy(Payment $payment)
    {
        $this->authorizePayment($payment);

        if ($payment->payment_proof) {
            Storage::disk('public')->delete($payment->payment_proof);
        }

        $payment->delete();

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil dihapus!');
    }

    /**
     * Helper: Otorisasi akses pembayaran
     */
    protected function authorizePayment(Payment $payment)
    {
        if (Auth::user()->role !== 'admin' && Auth::id() !== $payment->booking->user_id) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses pembayaran ini.');
        }
    }
}
