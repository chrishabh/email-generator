@extends('layout.main')

@php
    $headerData = ['whichPageRequest' => 'blog'];
@endphp

@section('main-section')
@push('title')
<title>Blog | Bouncee</title>
@endpush

<style>
    .blog-page {
        background: #fff;
        padding: 60px 0;
    }
    .blog-container {
        max-width: 1200px;
        margin: auto;
        margin-top: 2rem;
    }
    .featured-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
        margin-bottom: 70px;
    }
    .featured-left {
        background: #f7f3ff;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 3px 15px rgba(0,0,0,0.05);
    }
    .featured-left img {
        width: 100%;
        height: auto;
        max-height: 380px;
        object-fit: cover;
    }
    .featured-content {
        padding: 25px 30px;
    }
    .featured-content h3 {
        font-size: 1.6rem;
        color: #3d2b7a;
        margin-bottom: 5px;
        font-weight: 700;
    }
    .featured-content p.meta {
        color: #666;
        font-size: 13px;
        margin-bottom: 15px;
    }
    .featured-content .btn {
        background: #5e2ced;
        color: #fff;
        border-radius: 30px;
        padding: 10px 25px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.3s ease;
    }
    .featured-content .btn:hover {
        background: #4a22c4;
    }

    /* Sidebar */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }
    .sidebar-article {
        display: flex;
        align-items: center;
        gap: 15px;
        background: #faf8ff;
        padding: 15px;
        border-radius: 10px;
        transition: background 0.3s ease;
    }
    .sidebar-article:hover {
        background: #f0eaff;
    }
    .sidebar-article img {
        width: 90px;
        height: 70px;
        border-radius: 8px;
        object-fit: cover;
    }
    .sidebar-article small.meta {
        font-size: 12px;
        color: #888;
        display: block;
        margin-top: 2px;
    }

    /* New Posts Section */
    .new-posts {
        margin-top: 50px;
    }
    .new-posts h2 {
        font-size: 1.9rem;
        color: #5e2ced;
        font-weight: 700;
        text-align: left;
        margin-bottom: 25px;
    }
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(310px, 1fr));
        gap: 30px;
    }
    .blog-card {
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 3px 15px rgba(0,0,0,0.07);
        transition: transform 0.3s ease;
    }
    .blog-card:hover {
        transform: translateY(-5px);
    }
    .blog-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }
    .blog-card-content {
        padding: 20px;
    }
    .blog-card-content small.meta {
        font-size: 12px;
        color: #888;
        display: block;
        margin-top: 3px;
    }
    .read-more {
        color: #5e2ced;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        margin-top: 10px;
        transition: color 0.3s ease;
    }
    .read-more:hover {
        color: #4a22c4;
        text-decoration: underline;
    }

    /* Responsive */
    @media(max-width: 992px) {
        .featured-section {
            grid-template-columns: 1fr;
        }
        .featured-left img {
            max-height: 300px;
        }
    }
</style>

<div class="blog-page">
    <div class="blog-container">

        <!-- Featured Section -->
<div class="featured-section">

{{-- If featured post exists --}}
@if($featured)

    <div class="featured-left">
        <img src="{{ asset($featured->image ?? 'images/default-placeholder.jpg') }}"
             alt="{{ $featured->title ?? 'Featured Post' }}">

        <div class="featured-content">
            <small>{{ $featured->category ?? 'Uncategorized' }}</small>

            <h3>{{ $featured->title ?? 'No Featured Title' }}</h3>

            <p class="meta">
                By <strong>{{ $featured->author ?? 'Admin' }}</strong>
                Updated on {{ optional($featured->updated_at)->format('d M, Y') ?? '' }}
            </p>

            <div class="post-body">
                {!! $featured->excerpt ?? '<i>No summary available.</i>' !!}
            </div>

            <a href="{{ route('blog.show', $featured->slug) }}" class="btn">Read Story →</a>
        </div>
    </div>

@else  
    {{-- NO FEATURED POST FALLBACK UI --}}

    <div class="featured-left" style="padding:40px;text-align:center;">
        <h3 style="color:#888;">No Featured Post Available</h3>
        <p style="color:#666;">Please add a post and mark it as featured.</p>
    </div>

@endif

<!-- Sidebar (unchanged but safe) -->
<div class="sidebar">
    @forelse($sidebarPosts as $post)
        <div class="sidebar-article">
            <img src="{{ asset($post->image ?? 'images/default-small.jpg') }}" alt="">
            <div>
                <small>{{ $post->category ?? 'Uncategorized' }}</small>
                <small class="meta">
                    {{ $post->author ?? 'Admin' }}
                    {{ optional($post->updated_at)->format('d M') }}
                </small>

                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title ?? 'Untitled Post' }}</a>
            </div>
        </div>
    @empty
        <p>No sidebar posts available.</p>
    @endforelse
</div>

</div>

        <!-- Recent Posts Section -->
        <div class="new-posts">
            <h2>Recent Posts</h2>
            <div class="blog-grid">
                @forelse($recentPosts as $post)
                    <div class="blog-card">
                        <img src="{{ asset($post->image ?? 'images/default-placeholder.jpg') }}" alt="{{ $post->title }}">
                        <div class="blog-card-content">
                            <small>{{ $post->category ?? 'Uncategorized' }}</small>
                            <small class="meta">
                                {{ $post->author ?? 'Admin' }}
                                {{ optional($post->updated_at)->format('d M') }}
                            </small>
                            <h4>{{ $post->title ?? 'Untitled Post' }}</h4>
                            <p>{{ $post->excerpt ?? 'No excerpt available.' }}</p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="read-more">Read Story →</a>
                        </div>
                    </div>
                @empty
                    <p>No recent posts available.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
