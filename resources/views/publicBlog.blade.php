@extends('layout.main')

@php
    $headerData = array();
    $headerData['whichPageRequest'] = 'blog';
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
        height: 320px;
        object-fit: cover;
    }
    .featured-content {
        padding: 25px 30px;
    }
    .featured-content h3 {
        font-size: 1.6rem;
        color: #3d2b7a;
        margin-bottom: 10px;
        font-weight: 700;
    }
    .featured-content p {
        color: #444;
        margin-bottom: 20px;
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
    .sidebar-article a {
        color: #3d2b7a;
        font-weight: 600;
        text-decoration: none;
    }
    .sidebar-article small {
        color: #777;
        display: block;
        margin-bottom: 3px;
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
        height: 200px;
        object-fit: cover;
    }
    .blog-card-content {
        padding: 20px;
    }
    .blog-card-content small {
        color: #777;
        font-weight: 500;
    }
    .blog-card-content h4 {
        color: #3d2b7a;
        margin-top: 10px;
        margin-bottom: 8px;
        font-size: 1.15rem;
        font-weight: 600;
    }
    .blog-card-content p {
        color: #555;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    .read-more {
        color: #5e2ced;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        margin-top: 10px;
    }

    /* Load More */
    .load-more {
        text-align: center;
        margin-top: 50px;
    }
    .load-more button {
        background: #5e2ced;
        color: #fff;
        border: none;
        padding: 12px 35px;
        border-radius: 30px;
        font-weight: 600;
        transition: background 0.3s ease;
    }
    .load-more button:hover {
        background: #4a22c4;
    }

    /* Categories */
    .categories {
        margin-top: 70px;
        text-align: center;
    }
    .categories h3 {
        color: #3d2b7a;
        font-weight: 700;
        margin-bottom: 20px;
    }
    .categories .tags {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
    }
    .categories .tags a {
        background: #f5ecff;
        color: #5e2ced;
        padding: 8px 18px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.3s ease;
    }
    .categories .tags a:hover {
        background: #5e2ced;
        color: #fff;
    }

    @media(max-width: 992px) {
        .featured-section {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="blog-page">
    <div class="blog-container">

        <!-- Featured Section -->
        <div class="featured-section">
            <div class="featured-left">
                <img src="{{ $featured->image }}" alt="{{ $featured->title }}">
                <div class="featured-content">
                    <small>{{ $featured->category }}</small>
                    <h3>{{ $featured->title }}</h3>
                    <p>{{ $featured->excerpt }}</p>
                    <form action="{{ route('blog.show') }}" method="POST">
                        @csrf
                        <input type="hidden" name="slug" value="{{ $featured->slug }}">
                        <button type="submit" class="read-more">Read Story →</button>
                    </form>
                </div>
            </div>

            <div class="sidebar">
                @foreach($sidebarPosts as $post)
                    <div class="sidebar-article">
                        <img src="{{ asset($post->image) }}" alt="">
                        <div>
                            <small>{{ $post->category }}</small>
                             <!-- POST form for sidebar link -->
                            <form action="{{ route('blog.show') }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="slug" value="{{ $post->slug }}">
                                <button type="submit" style="background:none; border:none; padding:0; cursor:pointer; color:#3d2b7a; font-weight:600; text-decoration:underline;">
                                    {{ $post->title }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- New Posts -->
        <div class="new-posts">
            <h2>New Blog Posts</h2>
            <div class="blog-grid">
                @foreach($recentPosts as $post)
                <div class="blog-card">
                    <img src="{{ $post->image }}" alt="">
                    <div class="blog-card-content">
                        <small>{{ $post->category }}</small>
                        <h4>{{ $post->title }}</h4>
                        <p>{{ $post->excerpt }}</p>
                       <!-- POST form for Read Story -->
                <form action="{{ route('blog.show') }}" method="POST">
                    @csrf
                    <input type="hidden" name="slug" value="{{ $post->slug }}">
                    <button type="submit" class="read-more" style="background:none; border:none; padding:0; cursor:pointer; color:#5e2ced; font-weight:600;">
                        Read Story →
                    </button>
                </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Load More -->
        <div class="load-more">
            <button>Load More</button>
        </div>
    </div>
</div>
@endsection