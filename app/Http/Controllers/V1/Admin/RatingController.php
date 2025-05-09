<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class RatingController extends Controller
{
    public function ratingAnalytics(Request $request)
    {
        //total rating count
        //review message top 3 message with count

        try {
            $query = Rating::query();
            if ($request->filled('product_id')) {
                $query->where('product_id', $request->get('product_id'));
            }
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->get('user_id'));
            }
            if ($request->filled('rating')) {
                $query->where('rating', $request->get('rating'));
            }
            if ($request->filled('from_date')) {
                $query->where('created_at', '>=', $request->get('from_date'));
            }
            $rating = $query;
            $total_rating_count = (clone $query)->count();
            $top_reviews = (clone $query)
                ->select('review', DB::raw('Count(*) as total'))
                ->groupBy('review')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get();
            $rating_count = (clone $query)
                ->select('rating', DB::raw('Count(*) as total'))
                ->groupBy('rating')
                ->orderBy('total', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully fetched',
                'data' => [
                    'total_rating_count' => $total_rating_count,
                    'top_reviews' => $top_reviews,
                    'rating_count' => $rating_count,
                    'rating' => $rating->latest()->paginate(10),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
