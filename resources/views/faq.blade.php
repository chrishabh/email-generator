@extends('layout.main')

@php
    $headerData = [];
    $headerData['whichPageRequest'] = 'faq';
@endphp

@section('main-section')
    @push('title')
        <title>FAQ | Bouncee</title>
    @endpush

    <x-faq-section />
@endsection
