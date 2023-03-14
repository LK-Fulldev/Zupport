<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\GoogleRestaurants;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $keySearchName = $request->input('search_word') ?? "บางซื่อ";
        // Check Request For Call With Ajax
        if ($request->ajax()) {
            $googleData = GoogleRestaurants::query();
            $googleData->where('key_search', 'like', "%{$keySearchName}%");
            $googleData = $googleData->paginate(10);
        } else {
            // Cache Query
            $googleData = Cache::remember('google_restaurants', 3360, function () use ($keySearchName) {
                $googleData = GoogleRestaurants::query();
                $googleData->where('key_search', 'like', "%{$keySearchName}%");
                $googleData = $googleData->paginate(10);
                return $googleData;
            });
        }
        // Check Request For Call With Ajax & Render Data
        if ($request->ajax()) {
            return response()->json([
                'googleData' => view('layouts.result', compact('googleData', 'keySearchName'))->render()
            ]);
        }

        return view('welcome', compact('googleData', 'keySearchName'));
    }
}
