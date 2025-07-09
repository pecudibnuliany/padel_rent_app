<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_name')->after('schedule_id'); // Kolom nama pemesan
            $table->string('phone_number', 13)->after('booking_name'); // Kolom nomor telepon, max 13 digit
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('booking_name');
            $table->dropColumn('phone_number');
        });
    }
};
