@extends('layouts.guest')

@section('title', '| ' . $page->title)

@section('left_panel_content')
    <h2 class="text-4xl md:text-5xl font-black mb-6 tracking-tight">{{ $page->title }}</h2>
    <p class="text-lg text-gray-400 leading-relaxed font-medium">
        Review our latest information and policies below.
    </p>
@endsection

@section('content')
    <div class="prose dark:prose-invert max-w-none">
        {!! $page->content !!}
    </div>
@endsection
