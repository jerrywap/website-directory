<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Models\Website;
use App\Models\Vote;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WebsiteController extends Controller
{
    public function index(Request $request)
    {
        $query = Website::withCount('votes')
                        ->with('categories')
                        ->orderBy('votes_count', 'desc');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('url', 'LIKE', "%{$search}%")
                  ->orWhereHas('categories', function ($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
        }

        $websites = $query->get();

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
        $website->load('categories');


        return response()->json($website, 201);
    }

    public function vote($id)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error voting for website: '.$e->getMessage());
            return response()->json(['error' => 'Server Error'], 500);
        }
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
        try {
            $website = Website::findOrFail($id);

            $this->authorize('delete', $website);

            $website->delete();

            return response()->json(['message' => 'Website deleted']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Resource not found'], 404);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Unauthorized'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server Error'], 500);
        }
    }
}
