<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Membersihkan Rumah',
            'Pekerjaan',
            'Hobi',
            'Belajar',
            'Lain-lain'
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
