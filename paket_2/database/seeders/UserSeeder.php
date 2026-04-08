<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // User Admin - hanya insert jika belum ada
        if (!DB::table('users')->where('email', 'admin@parkir.test')->exists()) {
            DB::table('users')->insert([
                'name' => 'Administrator',
                'email' => 'admin@parkir.test',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status_aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // User Petugas - hanya insert jika belum ada
        if (!DB::table('users')->where('email', 'petugas@parkir.test')->exists()) {
            DB::table('users')->insert([
                'name' => 'Petugas Parkir',
                'email' => 'petugas@parkir.test',
                'password' => Hash::make('password123'),
                'role' => 'petugas',
                'status_aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // User Owner - hanya insert jika belum ada
        if (!DB::table('users')->where('email', 'owner@parkir.test')->exists()) {
            DB::table('users')->insert([
                'name' => 'Pemilik',
                'email' => 'owner@parkir.test',
                'password' => Hash::make('password123'),
                'role' => 'owner',
                'status_aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
