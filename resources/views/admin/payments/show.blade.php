{{-- filepath: d:\IT SUPPORT\Project\Salinan Farzana - Booking Futsal\resources\views\admin\payments\show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="flex">
    @include('components.sidebar')
    <div class="w-full flex-grow p-6">
        <h1 class="text-2xl font-bold mb-4">Detail Pembayaran</h1>
        <div class="bg-white shadow rounded-lg p-6 max-w-lg">   
            <table class="w-full">
                <tr>
                    <td class="font-semibold py-2">Nama Pemesan</td>
                    <td class="py-2">{{ $payment->booking->booking_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-semibold py-2">Nama User</td>
                    <td class="py-2">{{ $payment->booking->user->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-semibold py-2">Lapangan</td>
                    <td class="py-2">{{ $payment->booking->field->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="font-semibold py-2">Metode Pembayaran</td>
                    <td class="py-2">{{ ucfirst($payment->payment_method) }}</td>
                </tr>
                <tr>
                    <td class="font-semibold py-2">Jumlah</td>
                    <td class="py-2">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="font-semibold py-2">Status</td>
                    <td class="py-2">{{ ucfirst($payment->status) }}</td>
                </tr>
                <tr>
                    <td class="font-semibold py-2">Bukti Pembayaran</td>
                    <td class="py-2">
                        @if($payment->payment_proof)
                            <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank" class="text-blue-600 underline">Lihat Bukti</a>
                        @else
                            <span class="text-gray-500">Tidak ada</span>
                        @endif
                    </td>
                </tr>
            </table>
            <div class="mt-6">
                <a href="{{ route('admin.payments.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">Kembali</a>
            </div>
        </div>
    </div>
</div>