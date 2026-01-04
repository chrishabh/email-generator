@extends('layout.main')

@section('main-section')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

@push('title')
<title>Edit Post | Bouncee</title>
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
        <h1>Update Posts</h1>
    </div>

<div class="post-container">
    <form action="{{ route('posts.update', ['id' => $post->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Title</label>
        <input type="text" name="title" value="{{ old('title', $post->title) }}">

        <label>Category</label>
        <input type="text" name="category" value="{{ old('category', $post->category) }}">

        <label>Excerpt</label>
        <textarea name="excerpt">{{ old('excerpt', $post->excerpt) }}</textarea>

        <label>Content</label>
        <textarea name="content" id="content">
            {{ old('content', $post->content) }}
        </textarea>

        @if($post->image)
            <p>Current Image</p>
            <img src="{{ asset($post->image ?? 'images/default-small.jpg') }}" width="150">
        @endif

        <label>Change Image</label>
        <input type="file" name="image">

        <label>Author</label>
        <input type="text" name="author" value="{{ old('author', $post->author) }}">

        <button type="submit">Update Post</button>
        <a href="{{ route('posts.create') }}" class="cancel-btn">Cancel</a>
    </form>
</div>

</section>


<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/08q9dn1bp6stbnwidq91v2ci01pi53vgeo14mikkbcsv2zya/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
tinymce.init({
    selector: 'textarea#content',
    height: 500,
    menubar: true,

    plugins: 'advlist autolink lists link image charmap preview anchor code table fullscreen',
    toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code fullscreen',

    branding: false,
    content_style: 'body { font-family: Helvetica, Arial, sans-serif; font-size: 14px }',

    automatic_uploads: true,

    images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());

        fetch('/tinymce/upload', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(r => r.json())
        .then(result => {
            if (!result.location) {
                reject("Upload failed");
                return;
            }
            resolve(result.location);
        })
        .catch(() => reject("HTTP error"));
    }),

    file_picker_types: 'image',

    file_picker_callback: (callback, value, meta) => {
        let input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';

        input.onchange = function () {
            let file = this.files[0];
            let formData = new FormData();
            formData.append('file', file);

            fetch('/tinymce/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(r => r.json())
            .then(result => callback(result.location));
        };

        input.click();
    }
});
</script>


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

    .action-buttons {
    display: flex;
    justify-content: center;   /* aligns under header */
    align-items: center;
    gap: 10px;
}

.action-buttons form {
    margin: 0;
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
        text-align: center;
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
