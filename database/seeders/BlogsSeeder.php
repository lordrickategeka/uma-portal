<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            Blog::create([
                'title' => "Sample News $i",
                'slug' => Str::slug("Sample News $i"),
                'content' => "This is the content for Sample News $i.",
                'image' => null,
                'category_id' => 1,
                'author_id' => 1,
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(10, 100),
                'tags' => json_encode(['news', 'updates']),
            ]);
        }
    }
}
