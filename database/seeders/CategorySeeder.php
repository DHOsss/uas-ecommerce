<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Baju',   'description' => 'Kaos, tank top, dan atasan casual.'],
            ['name' => 'Kemeja', 'description' => 'Kemeja formal, flannel, dan denim.'],
            ['name' => 'Celana', 'description' => 'Celana jogger, chino, cargo, dan jeans.'],
            ['name' => 'Jaket',  'description' => 'Jaket bomber, hoodie, dan windbreaker.'],
            ['name' => 'Topi',   'description' => 'Snapback, bucket hat, dan beanie.'],
            ['name' => 'Sepatu', 'description' => 'Sneakers, canvas, dan high top.'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
