<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentProofToPaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('payment_method'); // Menambahkan kolom payment_proof
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_proof'); // Menghapus kolom payment_proof jika migrasi dibatalkan
        });
    }
}
