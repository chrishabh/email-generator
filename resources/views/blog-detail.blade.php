@extends('layout.main')

@section('main-section')
@push('title')
<title>{{ $post->title }} | Bouncee Blog</title>
@endpush

<style>
.blog-detail {
    max-width: 900px;
    margin: 60px auto;
    padding: 20px;
    color: #333;
    line-height: 1.7;
}

.blog-detail img.cover {
    width: 100%;
    border-radius: 16px;
    margin-bottom: 25px;
    object-fit: cover;
    max-height: 480px;
}

.blog-detail h1 {
    font-size: 32px;
    font-weight: 700;
    color: #2e1065;
    margin-bottom: 12px;
}

.blog-detail small {
    display: inline-block;
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 20px;
}

.blog-detail .meta {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 15px;
    display: block;
}

.blog-detail p {
    font-size: 17px;
    color: #374151;
    margin-bottom: 20px;
}

.blog-detail .content {
    margin-top: 25px;
}

.blog-detail .content h2 {
    font-size: 24px;
    color: #2e1065;
    margin-top: 30px;
    margin-bottom: 12px;
}

.related-section {
    margin-top: 60px;
}

.related-section h3 {
    font-size: 26px;
    color: #2e1065;
    font-weight: 700;
    margin-bottom: 25px;
    text-align: center;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
}

.related-card {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.07);
    transition: transform 0.3s ease;
    background: #fff;
}

.related-card:hover {
    transform: translateY(-5px);
}

.related-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.related-card-content {
    padding: 15px;
}

.related-card-content small {
    color: #6b7280;
    font-size: 13px;
    text-transform: uppercase;
}

.related-card-content .meta {
    color: #888;
    font-size: 12px;
    display: block;
    margin-top: 3px;
}

.related-card-content h4 {
    font-size: 18px;
    font-weight: 600;
    color: #2e1065;
    margin: 8px 0;
}

.related-card-content a {
    color: #7e22ce;
    text-decoration: none;
    font-weight: 500;
    font-size: 15px;
}
.related-card-content a:hover {
    text-decoration: underline;
}
</style>

<div class="blog-detail">

    <img src="{{ $post->image }}" alt="{{ $post->title }}" class="cover">

    <small>{{ $post->category }}</small>

    <h1>{{ $post->title }}</h1>

    <!-- ✅ NEW: Author + Updated On -->
    <span class="meta">
        By: <strong>{{ $post->author }}</strong>  Updated on: {{ $post->updated_at->format('d M, Y') }}
    </span>

    <div class="content">
        {!! ($post->content) !!}
    </div>

    <div class="related-section">
        <h3>Related Articles</h3>
        <div class="related-grid">
            @foreach($related as $rel)
            <div class="related-card">
                <img src="{{ $rel->image }}" alt="{{ $rel->title }}">
                <div class="related-card-content">
                    <small>{{ $rel->category }}</small>

                    <!-- ✅ NEW: Author + Updated On -->
                    <small class="meta">
                        {{ $rel->author }}  {{ $rel->updated_at->format('d M') }}
                    </small>

                    <h4>{{ $rel->title }}</h4>
                    <form action="{{ route('blog.show') }}" method="POST">
                        @csrf
                        <input type="hidden" name="slug" value="{{ $rel->slug ?? '' }}">
                        <button type="submit" class="read-more">Read Story →</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
