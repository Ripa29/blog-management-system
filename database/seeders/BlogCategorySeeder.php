<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology', 'status' => 1],
            ['name' => 'Travel', 'status' => 1],
            ['name' => 'Food', 'status' => 1],
            ['name' => 'Lifestyle', 'status' => 1],
            ['name' => 'Health', 'status' => 1],
        ];

        foreach ($categories as $category) {
            BlogCategory::create($category);
        }
    }
}
