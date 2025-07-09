<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FieldSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fields')->insert([
            [
                'name' => 'Lapangan A',
                'price_per_hour' => 100000,
                'description' => 'Lapangan utama untuk pertandingan',
                'location' => 'jakarta', // Atur ke null jika kolom dapat NULL
                'photo'=>'fields/wZtTPW2WXaL0aO76KPKwqkZvLqf7BGGQMOetzYuA.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambahkan data lainnya sesuai kebutuhan
        ]);

        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'admin@mail.com',
                'email_verified_at' => '',
                'password' => Hash::make('admin12345'), // Atur ke null jika kolom dapat NULL
                'role'=>'admin',
            ],
            [
                'name' => 'user',
                'email' => 'user@mail.com',
                'email_verified_at' => '',
                'password' => Hash::make('user12345'), // Atur ke null jika kolom dapat NULL
                'role'=>'user',
            ]
            // Tambahkan data lainnya sesuai kebutuhan
        ]);
    }
}
