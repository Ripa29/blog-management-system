<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use App\Models\BlogLike;
use App\Models\BlogComment;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;
use Database\Seeders\BlogCategorySeeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        if (BlogCategory::count() === 0) {
            $this->call(BlogCategorySeeder::class);
        }

        // Create authors
        $authors = User::factory()->count(8)->create(['status' => 1]);
        $categoryIds = BlogCategory::pluck('id')->toArray();

        // Demo Thumbnails 
        $thumbnail = [
            'thumbnails/blogs/tech.jpg',
            'thumbnails/blogs/tech1.jpg',
            'thumbnails/blogs/techai.jpg',
            'thumbnails/blogs/plant.jpg',
            'thumbnails/blogs/travel.jpg',
            'thumbnails/blogs/travel1.jpg',
            'thumbnails/blogs/food.jpg',
            'thumbnails/blogs/lifestyle.jpg',
            'thumbnails/blogs/health.jpg',
            'thumbnails/blogs/avocado.jpg',
            'thumbnails/blogs/photography.jpg'
        ];

        //  Demo Blogs
        $blogs = [
            [
                'title' => 'Mastering Laravel 12: Build Modern Web Apps with Ease',
                'description' => "Laravel 12 introduces new performance enhancements, queue management improvements, and developer-friendly syntax updates. In this article, we’ll explore how to build modern web apps faster than ever. From route caching to job batching, Laravel 12 gives developers all the tools to create scalable and secure applications. We'll also discuss integrating Vue or React for dynamic frontends and deploying with zero downtime.",
                'thumbnail' => 'thumbnails/blogs/tech.jpg',
                'category' => 'Technology',
                'status' => 1
            ],
            [
                'title' => 'Sustainable Travel: Exploring the World Responsibly',
                'description' => "Traveling sustainably is more important than ever. Whether you’re backpacking across Asia or taking a luxury cruise, you can reduce your footprint by choosing eco-friendly accommodations, supporting local communities, and minimizing plastic use. Here’s how responsible travel makes a lasting impact on both the planet and your travel experience.",
                'thumbnail' => 'thumbnails/blogs/travel.jpg',
                'category' => 'Travel',
                'status' => 1
            ],
            [
                'title' => '10 Superfoods to Boost Your Immune System Naturally',
                'description' => "A strong immune system is your body’s best defense. From citrus fruits to leafy greens and turmeric, these natural superfoods help strengthen immunity and reduce inflammation. Learn which foods to include in your diet to stay healthy year-round.",
                'thumbnail' => 'thumbnails/blogs/food.jpg',
                'category' => 'Health',
                'status' => 1
            ],
            [
                'title' => 'Healthy Meal Prep Ideas for Busy Professionals',
                'description' => "Between work meetings and deadlines, finding time to cook healthy meals can be a challenge. Meal prepping is a game-changer for anyone balancing a busy schedule. We’ll walk you through simple, nutritious meal ideas that save time and keep you energized throughout the week.",
                'thumbnail' => 'thumbnails/blogs/lifestyle.jpg',
                'category' => 'Lifestyle',
                'status' => 1
            ],
            [
                'title' => 'The Future of Remote Work: Digital Nomads and Global Freedom',
                'description' => "Remote work has redefined the traditional office setup. With reliable internet and collaboration tools, professionals are embracing the digital nomad lifestyle. We’ll explore the rise of remote work hubs and how companies are adapting to this global shift.",
                'thumbnail' => 'thumbnails/blogs/tech1.jpg',
                'category' => 'Technology',
                'status' => 0
            ],
            [
                'title' => 'Indoor Plants That Purify Air and Boost Your Mood',
                'description' => "Bringing a touch of green into your home doesn’t just make your space look better — it makes you feel better too. Indoor plants like the peace lily, snake plant, and spider plant are known for their natural air-purifying abilities. They remove toxins like formaldehyde and benzene, helping you breathe cleaner air. Beyond the health benefits, plants also reduce stress and boost productivity.",
                'thumbnail' => 'thumbnails/blogs/plant.jpg',
                'category' => 'Lifestyle',
                'status' => 1
            ],
            [
                'title' => 'Top 5 AI Tools Every Developer Should Try in 2025',
                'description' => "AI-powered tools are transforming the way developers work. From automated code reviews to intelligent bug detection, these tools boost productivity and improve code quality. Discover the top AI assistants that can supercharge your workflow this year.",
                'thumbnail' => 'thumbnails/blogs/techai.jpg',
                'category' => 'Technology',
                'status' => 1
            ],
            [
                'title' => '5 Delicious Avocado Recipes for a Healthy Lifestyle',
                'description' => "Avocados aren’t just trendy — they’re a nutritional powerhouse packed with healthy fats, fiber, and vitamins. From breakfast toast to creamy smoothies, this green fruit can turn any meal into something special. Try these five easy avocado recipes to make your meals healthier and tastier.",
                'thumbnail' => 'thumbnails/blogs/avocado.jpg',
                'category' => 'Food',
                'status' => 1
            ],
            [
                'title' => 'The Art of Storytelling in Marketing Campaigns',
                'description' => "Storytelling remains one of the most powerful marketing tools. A compelling narrative not only engages your audience but also builds brand trust. In this blog, we’ll uncover techniques to craft emotionally resonant stories that convert viewers into loyal customers.",
                'thumbnail' => 'thumbnails/blogs/photography.jpg',
                'category' => 'Lifestyle',
                'status' => 1
            ],
            [
                'title' => 'Essential Travel Tips for Solo Adventurers',
                'description' => "Solo travel is liberating, but it comes with its own set of challenges. From safety precautions to smart budgeting and destination planning, here’s everything you need to know to make your solo trip unforgettable and stress-free.",
                'thumbnail' => 'thumbnails/blogs/travel1.jpg',
                'category' => 'Travel',
                'status' => 1
            ],
        ];

        foreach ($blogs as $i => $data) {
            $category = BlogCategory::where('name', $data['category'])->first();

            $blog = Blog::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']) . '-' . time() . $i,
                'description' => trim($data['description']),
                'thumbnail' => $data['thumbnail'],
                'category_id' => $category ? $category->id : $categoryIds[array_rand($categoryIds)],
                'author_id' => $authors->random()->id,
                'status' => 1,
            ]);

            // Likes
            $likers = $authors->random(rand(3, 6));
            foreach ($likers as $user) {
                BlogLike::create([
                    'author_id' => $user->id,
                    'blog_id' => $blog->id,
                ]);
            }

            // Comments
            $commenters = $authors->random(rand(2, 5));
            foreach ($commenters as $user) {
                BlogComment::create([
                    'author_id' => $user->id,
                    'blog_id' => $blog->id,
                    'comment' => fake()->sentence(rand(10, 18)),
                ]);
            }
        }

        echo " BlogSeeder: demo blogs added successfully.\n";
    }
}
