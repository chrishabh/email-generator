@extends('layout.main')

@section('main-section')
@push('title')
    <title>Add & Manage Posts | Bouncee</title>
@endpush

@php
    $name = '';
    if(!empty($userData)) {
        $userData = $userData->toArray(); 
        if(!empty($userData['name'])) {
            $fullName = $userData['name'];
            $nameParts = explode(' ', $fullName);
            if(count($nameParts) < 2) {
                $name = strtoupper($nameParts[0]);
            } else {
                $firstCapitalLetter = strtoupper($nameParts[0][0]);
                $lastCapitalLetter = strtoupper($nameParts[count($nameParts)-1][0]);
                $name = $firstCapitalLetter . $lastCapitalLetter;
            }
        }
    }
@endphp

<section id="posts-page">
    <div class="posts-header">
        <h1>Add/Manage Posts</h1>
        <p>Welcome {{ $name ?? 'User' }}, fill in the details below to create a new post.</p>
    </div>

    <!-- Add Post Form -->
    <div class="post-container">
        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}">
            @error('title') <div class="error">{{ $message }}</div> @enderror

            <label for="category">Category</label>
            <input type="text" name="category" id="category" value="{{ old('category') }}">
            @error('category') <div class="error">{{ $message }}</div> @enderror

            <label for="excerpt">Excerpt</label>
            <textarea name="excerpt" id="excerpt" rows="3">{{ old('excerpt') }}</textarea>
            @error('excerpt') <div class="error">{{ $message }}</div> @enderror

            <label for="content">Content</label>
            <textarea name="content" id="content" rows="6">{{ old('content') }}</textarea>
            @error('content') <div class="error">{{ $message }}</div> @enderror

            <label for="image">Image</label>
            <input type="file" name="image" id="image">
            @error('image') <div class="error">{{ $message }}</div> @enderror

            <button type="submit">Add Post</button>
        </form>
    </div>

    <!-- Existing Posts List -->
    <div class="posts-list-container">
        <h2>Existing Posts</h2>
        @if($posts->count() > 0)
        <table class="posts-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $index => $post)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->category ?? '-' }}</td>
                    <td>{{ $post->created_at->format('d M Y') }}</td>
                    <td>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <p>No posts found.</p>
        @endif
    </div>
</section>

@push('styles')
<style>
    /* Background & Header */
    #posts-page {
        min-height: 100vh;
        padding: 3rem 1rem;
        background: linear-gradient(61deg, #8993d4, #cecfd2);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .posts-header {
        text-align: center;
        margin-bottom: 2rem;
        color: #fff;
    }

    .posts-header h1 {
        font-size: 3rem;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        color: #f0f0f0;
    }

    .posts-header p {
        font-size: 1.2rem;
        color: #f0f0f0;
    }

    /* Form Container */
    .post-container {
        width: 100%;
        background: #fff;
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        margin: 0 auto 3rem auto;
        backdrop-filter: blur(10px);
    }

    input, textarea, select {
        width: 100%;
        padding: 14px;
        margin: 10px 0 20px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 16px;
        box-sizing: border-box;
        transition: 0.3s;
    }

    input:focus, textarea:focus {
        border-color: #007BFF;
        box-shadow: 0 0 5px rgba(0,123,255,0.3);
        outline: none;
    }

    button {
        background: #007BFF;
        color: #fff;
        padding: 14px 30px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: 0.3s;
    }

    button:hover {
        background: #0056b3;
        transform: translateY(-2px);
    }

    .success {
        background: #d4edda;
        color: #155724;
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: bold;
    }

    .error {
        color: red;
        font-size: 0.9rem;
        margin-top: -12px;
        margin-bottom: 15px;
    }

    /* Posts Table */
    .posts-list-container {
        margin: 0 auto 3rem auto;
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    .posts-list-container h2 {
        margin-bottom: 1.5rem;
        text-align: center;
        color: #007BFF;
        font-size: 25px;
        font-weight: 700;
    }

    .posts-table {
        width: 100%;
        border-collapse: collapse;
    }

    .posts-table th, .posts-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .posts-table th {
        background: #f7f7f7;
    }

    .delete-btn {
        background: #dc3545;
        padding: 6px 14px;
        border-radius: 5px;
        font-size: 0.9rem;
    }

    .delete-btn:hover {
        background: #a71d2a;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .post-container, .posts-list-container {
            padding: 30px 20px;
        }
        .posts-header h1 {
            font-size: 2rem;
        }
        .posts-header p {
            font-size: 1rem;
        }
    }
</style>
@endpush
@endsection
