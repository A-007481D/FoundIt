<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\ItemMatch;
use App\Services\MatchService;
use Illuminate\Support\Facades\Log;

class MatchController extends Controller
{
    /**
     * Return potential matches for the authenticated user's items.
     */
    public function index(Request $request)
    {
        try {
            $service = app(MatchService::class);
            $matches = $service->getForUser(Auth::id());
            return response()->json($matches);
        } catch (\Throwable $e) {
            Log::error('MatchController@index error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Server error retrieving matches'], 500);
        }
    }
}
