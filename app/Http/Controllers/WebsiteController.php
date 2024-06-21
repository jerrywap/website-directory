<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Website;
use App\Models\Vote;
use App\Models\Category;

class WebsiteController extends Controller
{
    /*public function index()
    {
        return Website::with('categories')->get();
    }*/
    public function index(Request $request)
    {
        $query = Website::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('url', 'LIKE', "%{$search}%")
                ->orWhereHas('categories', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
        }

        $websites = $query->with('categories', 'votes')->get();

        return response()->json($websites);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
            'categories' => 'required|array',
        ]);

        $website = Website::create($request->only('name', 'url'));
        $website->categories()->sync($request->categories);

        return response()->json($website, 201);
    }

    public function vote($id)
    {
        $website = Website::findOrFail($id);

        $existingVote = Vote::where('user_id', Auth::id())->where('website_id', $id)->first();
        if ($existingVote) {
            return response()->json(['message' => 'You have already voted for this website'], 409);
        }

        Vote::create([
            'user_id' => Auth::id(),
            'website_id' => $id,
        ]);

        return response()->json(['message' => 'Vote registered']);
    }

    public function unvote($id)
    {
        $vote = Vote::where('user_id', Auth::id())->where('website_id', $id)->first();
        if ($vote) {
            $vote->delete();
            return response()->json(['message' => 'Vote removed']);
        }
        return response()->json(['message' => 'Vote not found'], 404);
    }

    public function destroy($id)
    {
        $website = Website::findOrFail($id);
        $website->delete();

        return response()->json(['message' => 'Website deleted']);
    }
}
