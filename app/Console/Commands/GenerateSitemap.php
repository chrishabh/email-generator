<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate XML sitemap with dynamic blog URLs';

    public function handle()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Static pages
        $staticPages = [
            '/',
            '/blog',
            '/plans',
            '/why-us',
            '/faq',
            '/about-us',
            '/privacy',
            '/term'
        ];
        
        foreach ($staticPages as $page) {
            $sitemap .= '  <url>' . "\n";
            $sitemap .= '    <loc>' . config('app.url') . $page . '</loc>' . "\n";
            $sitemap .= '    <changefreq>weekly</changefreq>' . "\n";
            $sitemap .= '    <priority>0.8</priority>' . "\n";
            $sitemap .= '  </url>' . "\n";
        }
        
        // Dynamic blog pages
        $posts = Post::all();
        foreach ($posts as $post) {
            $sitemap .= '  <url>' . "\n";
            $sitemap .= '    <loc>' . config('app.url') . '/blog/' . $post->slug . '</loc>' . "\n";
            $sitemap .= '    <lastmod>' . $post->updated_at->format('Y-m-d') . '</lastmod>' . "\n";
            $sitemap .= '    <changefreq>monthly</changefreq>' . "\n";
            $sitemap .= '    <priority>0.7</priority>' . "\n";
            $sitemap .= '  </url>' . "\n";
        }
        
        $sitemap .= '</urlset>';
        
        file_put_contents(public_path('sitemap.xml'), $sitemap);
        
        $this->info('Sitemap generated successfully with ' . count($posts) . ' blog posts!');
        
        return 0;
    }
}