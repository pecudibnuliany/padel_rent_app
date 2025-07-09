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
        // Mengambil semua pembayaran yang terkait dengan user yang login (jika admin, semua pembayaran)
        $payments = Auth::user()->role === 'admin' ? Payment::all() : Payment::where('booking_id', Auth::id())->get();

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Menampilkan form untuk membuat pembayaran baru
     */
    public function create($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        // Harga per jam sudah tetap, langsung ambil harga per jam dari field terkait
        $totalPrice = $booking->field->price_per_hour;

        return view('admin.payments.create', compact('booking', 'totalPrice'));
    }

    /**
     * Menyimpan data pembayaran baru
     */
    public function store(Request $request, $bookingId)
    {
        // Validasi input
        $request->validate([
            'payment_method' => 'required|in:cash,transfer',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Validasi file bukti pembayaran
        ]);

        // Mengambil data booking terkait
        $booking = Booking::findOrFail($bookingId);

        // Mengambil harga tetap dari field terkait
        $totalPrice = $booking->field->price_per_hour;

        // Menyimpan bukti pembayaran jika ada
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        // Membuat pembayaran baru dengan status 'pending'
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $totalPrice,
            'status' => 'checked', // Status awal adalah pending
            'payment_method' => $request->payment_method,
            'payment_proof' => $paymentProofPath,
        ]);

        // Update status booking menjadi pending atau sesuai kebutuhan
        $booking->update(['status' => 'pending']);

        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil dibuat!');
        } else {
            return redirect()->route('user.administration.index')->with('success', 'Pembayaran berhasil dibuat!');
        }
    }


    /**
     * Menampilkan detail pembayaran
     */
    public function show(Payment $payment)
    {
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Menampilkan form untuk mengedit pembayaran
     */
    public function edit(Payment $payment)
    {
        // Pastikan hanya admin atau pemilik booking yang dapat mengedit pembayaran
        if (Auth::user()->role !== 'admin' && Auth::id() !== $payment->booking->user_id) {
            return redirect()->route('admin.payments.index')->with('error', 'Anda tidak memiliki izin untuk mengedit pembayaran ini.');
        }

        return view('admin.payments.edit', compact('payment'));
    }

    /**
     * Memperbarui status pembayaran
     */
    public function update(Request $request, Payment $payment)
    {
        // Pastikan hanya admin yang dapat mengupdate status pembayaran
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.payments.index')->with('error', 'Anda tidak memiliki izin untuk memperbarui status pembayaran.');
        }

        // Validasi status pembayaran
        $request->validate([
            'status' => 'required|in:pending,paid,failed,checked',
        ]);

        // Update status pembayaran
        $payment->update([
            'status' => $request->status,
        ]);

        // Ambil booking terkait
        $booking = $payment->booking;

        // Update status booking berdasarkan status pembayaran
        if ($request->status === 'paid') {
            $booking->update(['status' => 'confirmed']); // Jika pembayaran berhasil, status booking menjadi 'confirmed'
        } elseif ($request->status === 'failed') {
            $booking->update(['status' => 'canceled']); // Jika pembayaran gagal, status booking menjadi 'failed'
        }

        return redirect()->route('admin.payments.index')->with('success', 'Status pembayaran berhasil diperbarui!');
    }

    /**
     * Menghapus pembayaran
     */
    public function destroy(Payment $payment)
    {
        // Pastikan hanya admin atau pemilik booking yang dapat menghapus pembayaran
        if (Auth::user()->role !== 'admin' && Auth::id() !== $payment->booking->user_id) {
            return redirect()->route('admin.payments.index')->with('error', 'Anda tidak memiliki izin untuk menghapus pembayaran ini.');
        }

        // Hapus bukti pembayaran dari storage jika ada
        if ($payment->payment_proof) {
            Storage::disk('public')->delete($payment->payment_proof);
        }

        // Hapus pembayaran
        $payment->delete();

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil dihapus!');
    }

}
