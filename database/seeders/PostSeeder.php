<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Why Customer Obsession Should Guide Every Email You Send',
                'category' => 'Industry Voices',
                'image' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f',
                'excerpt' => 'Building relationships with your email subscribers starts with empathy.',
                'content' => 'Full blog content here...',
            ],
            [
                'title' => 'Introducing Bouncee API — Verify Emails in Real Time',
                'category' => 'Press Release',
                'image' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d',
                'excerpt' => 'Developers can now integrate Bouncee directly into signups and CRMs.',
                'content' => 'Full blog content here...',
            ],
            [
                'title' => 'Getting Real ROI from Your Email List',
                'category' => 'Be a Better Marketer',
                'image' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2',
                'excerpt' => 'Stop spinning your wheels. With verified lists and content, ROI follows naturally.',
                'content' => 'Full blog content here...',
            ],
        ];

        foreach ($posts as $post) {
            Post::create([
                'title' => $post['title'],
                'category' => $post['category'],
                'image' => $post['image'],
                'slug' => Str::slug($post['title']),
                'excerpt' => $post['excerpt'],
                'content' => $post['content'],
            ]);
        }
    }
}
