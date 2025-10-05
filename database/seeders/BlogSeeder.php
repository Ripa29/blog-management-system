<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = BlogCategory::all();
        $users = User::all();

        $blogs = [
            [
                'title' => 'The Future of Artificial Intelligence in Modern Business',
                'description' => '<p>Artificial Intelligence is transforming how businesses operate, from automating routine tasks to providing deep insights through data analysis...</p>',
                'status' => 1,
            ],
            [
                'title' => 'Sustainable Travel: Exploring the World Responsibly',
                'description' => '<p>As climate change concerns grow, sustainable travel has become more important than ever...</p>',
                'status' => 1,
            ],
            [
                'title' => 'The Art of Mindful Eating for Better Health',
                'description' => '<p>Mindful eating is about developing awareness of your eating habits and the sensations you experience while eating...</p>',
                'status' => 1,
            ],
            [
                'title' => 'Blockchain Technology: Beyond Cryptocurrency',
                'description' => '<p>While blockchain is often associated with cryptocurrencies like Bitcoin, its potential applications extend far beyond digital money...</p>',
                'status' => 0,
            ],
        ];

        foreach ($blogs as $index => $blogData) {
            Blog::create([
                'title' => $blogData['title'],
                'category_id' => $categories->random()->id,
                'author_id' => $users->random()->id,
                'description' => $blogData['description'],
                'thumbnail' => 'blog-' . ($index + 1) . '.jpg', // placeholder, you can use real files if needed
                'status' => $blogData['status'],
                'slug' => \Illuminate\Support\Str::slug($blogData['title']) . '-' . time(),

            ]);
        }
    }
}
