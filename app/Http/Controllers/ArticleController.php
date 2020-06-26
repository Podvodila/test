<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::filter($request)->get();

        return response()->json($articles);
    }

    public function show($slug)
    {
        $article = Article::query()->where('slug', '=', $slug)->first();

        return response()->json($article);
    }

    public function categories()
    {
        return response()->json(Article::getUniqueCategories());
    }
}
