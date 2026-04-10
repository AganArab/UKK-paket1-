<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipment = [
            // Elektronik
            [
                'name' => 'Laptop',
                'description' => 'Laptop untuk presentasi dan kerja',
                'category_id' => 1,
                'stock' => 5,
                'condition' => 'baik'
            ],
            [
                'name' => 'Proyektor',
                'description' => 'Proyektor untuk presentasi',
                'category_id' => 1,
                'stock' => 2,
                'condition' => 'baik'
            ],
            [
                'name' => 'Speaker',
                'description' => 'Speaker untuk acara',
                'category_id' => 1,
                'stock' => 3,
                'condition' => 'baik'
            ],

            // Olahraga
            [
                'name' => 'Bola Basket',
                'description' => 'Bola basket ukuran standar',
                'category_id' => 2,
                'stock' => 10,
                'condition' => 'baik'
            ],
            [
                'name' => 'Raket Badminton',
                'description' => 'Set raket badminton',
                'category_id' => 2,
                'stock' => 8,
                'condition' => 'baik'
            ],
            [
                'name' => 'Matras Yoga',
                'description' => 'Matras untuk yoga dan senam',
                'category_id' => 2,
                'stock' => 15,
                'condition' => 'baik'
            ],

            // Laboratorium
            [
                'name' => 'Mikroskop',
                'description' => 'Mikroskop untuk praktikum biologi',
                'category_id' => 3,
                'stock' => 4,
                'condition' => 'baik'
            ],
            [
                'name' => 'Tabung Reaksi',
                'description' => 'Set tabung reaksi untuk kimia',
                'category_id' => 3,
                'stock' => 50,
                'condition' => 'baik'
            ],

            // Kesenian
            [
                'name' => 'Gitar Akustik',
                'description' => 'Gitar akustik untuk latihan musik',
                'category_id' => 4,
                'stock' => 3,
                'condition' => 'baik'
            ],
            [
                'name' => 'Kanvas Lukis',
                'description' => 'Kanvas untuk melukis',
                'category_id' => 4,
                'stock' => 20,
                'condition' => 'baik'
            ],

            // Pertukangan
            [
                'name' => 'Obeng Set',
                'description' => 'Set obeng lengkap',
                'category_id' => 5,
                'stock' => 6,
                'condition' => 'baik'
            ],
            [
                'name' => 'Palu',
                'description' => 'Palu untuk pertukangan',
                'category_id' => 5,
                'stock' => 8,
                'condition' => 'baik'
            ]
        ];

        foreach ($equipment as $item) {
            Equipment::updateOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
