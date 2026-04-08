<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed admin user
        DB::table('users')->insert([
            'name' => 'Admin Sekolah',
            'email' => 'admin@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed kategori
        $kategori = [
            ['nama_kategori' => 'Kebersihan'],
            ['nama_kategori' => 'Fasilitas'],
            ['nama_kategori' => 'Keamanan'],
        ];

        foreach ($kategori as $kat) {
            DB::table('kategori')->insert(array_merge($kat, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
