@extends('layouts.app')
@section('content')
<div class="container" style="max-width: 800px">
    {{ $articles->links() }}
    @if (session('info'))
        <div class="alert alert-info">
            {{session('info')}}
        </div>
    @endif

    @if ($articles->isEmpty())
        <div class="alert alert-warning">
            No Blogs Available
        </div>
    @else
        @foreach ($articles as $article)
            <div class="card mb-2 p-3">
                <h4>{{ $article->title }}</h4>
                <div class="text-muted">
                    <b class="text-success">{{ $article->user->name }}</b>,
                    <b>Category: </b>{{ $article->category->name }},
                    <b>Comments: </b>{{ count($article->comments) }},
                    {{ $article->created_at->diffForHumans() }}
                </div>
                <div class="mb-2">
                    {{ $article->body }}
                </div>
                <a href="{{ url('/articles/detail/'.$article->id) }}" style="text-decoration:none">View Detail &raquo;</a>
            </div>
        @endforeach
    @endif
</div>
@endsection
