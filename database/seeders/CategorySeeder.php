<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'description' => 'Alat-alat elektronik seperti laptop, proyektor, dll.'
            ],
            [
                'name' => 'Olahraga',
                'description' => 'Peralatan olahraga seperti bola, raket, dll.'
            ],
            [
                'name' => 'Laboratorium',
                'description' => 'Alat-alat untuk kegiatan laboratorium'
            ],
            [
                'name' => 'Kesenian',
                'description' => 'Alat-alat kesenian seperti musik, lukis, dll.'
            ],
            [
                'name' => 'Pertukangan',
                'description' => 'Alat-alat pertukangan dan perbaikan'
            ]
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
