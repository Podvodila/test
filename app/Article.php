<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * @method static Builder filter(Request $request)
 */
class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'categories',
        'media',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'categories' => 'array',
        'media' => 'array',
    ];

    /**
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeFilter(Builder $query, Request $request)
    {
        return $query
            ->when($request->filled('search'), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    $search = "%$request->search%";
                    return $query->where('title', 'like', $search)
                        ->orWhere('content', 'like', $search);
                });
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                return $query->where(function ($query) use ($request) {
                    return $query
                        ->whereNotNull('categories->primary')
                        ->where('categories->primary', '=', $request->category)
                        ->orWhereJsonContains('categories->additional', $request->category);
                });
            });
    }

    /**
     * Get unique categories
     * @return array
     */
    public static function getUniqueCategories()
    {
        $categories = Article
            ::query()
            ->select('categories')
            ->distinct()
            ->get();

        $primaryCategories = $categories
            ->map(function ($article) {
                return $article->categories['primary'];
            });

        $additionalCategories = $categories
            ->map(function ($article) {
                return $article->categories['additional'];
            })->flatten();

        $categories = $primaryCategories->merge($additionalCategories)->unique()->filter();

        return $categories->toArray();
    }
}
