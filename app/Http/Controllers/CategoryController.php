<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['websites' => function ($query) {
            $query->withCount('votes')
                  ->orderBy('votes_count', 'desc');
        }])
        ->select('categories.*', DB::raw('(SELECT COUNT(votes.id) FROM votes
                                           INNER JOIN websites ON websites.id = votes.website_id
                                           INNER JOIN category_website ON category_website.website_id = websites.id
                                           WHERE category_website.category_id = categories.id) as total_votes'))
        ->orderBy('total_votes', 'desc')
        ->get();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category = Category::create($request->only('name'));

        return response()->json($category, 201);
    }
}
