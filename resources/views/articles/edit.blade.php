@extends("layouts.app")
@section("content")
<div class="container" style="max-width: 800px">
        @if ($errors->any())
            <div class="alert alert-warning">
                @foreach ($errors->all() as $err)
                    {{$err}}
                @endforeach
            </div>
        @endif
    <form method="POST">
        @csrf
        <input type="text" name="title" class="form-control mb-2" value="{{$article->title}}" placeholder="Title">
        <textarea name="body" class="form-control mb-2" placeholder="Body">{{$article->body}}</textarea>
        <select name="category_id" class="form-select mb-2">
            @foreach ($categories as $category)
                <option value="{{$category->id}}" @selected($article->category_id == $category->id)>
                    {{$category->name}}
                </option>
            @endforeach
        </select>
        <button class="btn btn-primary">Update Article</button>
    </form>
</div>
@endsection
