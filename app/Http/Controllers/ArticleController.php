<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','detail','authorArticles','category']);
    }
    public function index(){
        $data=Article::latest()->paginate(5);
        $authors = User::orderBy('name')->get();
        return view("articles.index",["articles"=>$data,"authors"=>$authors]);
    }
    public function detail($id){
        $article=Article::find($id);
        $authors = User::orderBy('name')->get();
        return view("articles.detail",["article"=>$article,"authors"=>$authors]);
    }
    public function delete($id){
        $article=Article::find($id);
        if(Gate::allows('delete-article',$article)){
            $article->delete();
            return redirect("/articles")->with("info","An article deleted");
        }
        return back()->with("info","Unauthorize");
    }
    public function add(){
        return view("articles.add",[
            "categories"=>Category::all(),
            "authors"=>User::orderBy('name')->get(),
        ]);
    }
    public function edit($id){
        $article=Article::find($id);
        $categories=Category::all();
        return view('articles.edit',[
            "article"=>$article,
            "categories"=>$categories,
            "authors"=>User::orderBy('name')->get(),
        ]);
    }
    public function update($id){
        $validator=validator(request()->all(),[
            "title"=>"required",
            "body"=>"required",
            "category_id"=>"required",
        ]);
        if($validator->fails()){
            return back()->withErrors($validator);
        }
        $article = Article::find($id);
        $article->title=request()->title;
        $article->body=request()->body;
        $article->category_id=request()->category_id;
        $article->user_id=Auth::id();
        $article->save();
        return redirect("/articles/detail/$id");
    }
    public function create(){
        $validator=validator(request()->all(),[
            "title"=>"required",
            "body"=>"required",
            "category_id"=>"required",
        ]);
        if($validator->fails()){
            return back()->withErrors($validator);
        }
        $article = new Article;
        $article->title=request()->title;
        $article->body=request()->body;
        $article->category_id=request()->category_id;
        $article->user_id=Auth::id();
        $article->save();
        return redirect("/articles");
    }
    public function authorArticles(User $author)
    {
        $articles = Article::where('user_id', $author->id)->latest()->paginate(5);
        $authors = User::orderBy('name')->get();
        return view('articles.index', compact('articles','authors'));
    }
    public function category($id) {
        $category = Category::findOrFail($id);
        $articles = Article::where('category_id', $id)->latest()->paginate(5);
        $categories = Category::all();
        $authors = User::all();
    
        return view('articles.index', compact('articles', 'categories', 'authors', 'category'));
    }
}
