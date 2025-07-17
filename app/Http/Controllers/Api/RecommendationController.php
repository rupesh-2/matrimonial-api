<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MatchmakingService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected $matchmakingService;

    public function __construct(MatchmakingService $matchmakingService)
    {
        $this->matchmakingService = $matchmakingService;
    }

    /**
     * Get recommendations for the authenticated user
     */
    public function getRecommendations(Request $request)
    {
        $request->validate([
            'limit' => 'sometimes|integer|min:1|max:50',
        ]);

        $limit = $request->get('limit', 10);
        $user = $request->user();
        
        $recommendations = $this->matchmakingService->getRecommendations($user, $limit);

        return response()->json([
            'recommendations' => $recommendations,
            'total' => count($recommendations),
        ]);
    }
} 