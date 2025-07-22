<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Services\SummarizerService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    protected $summarizer_service;

    public function __construct(SummarizerService $summarizer_service)
    {
        $this->summarizer_service = $summarizer_service;
    }

    public function ratingAnalytics(Request $request)
    {
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
            $reviews = (clone $query)->select('review')->orderBy('id', 'desc')->limit(1000)->pluck('review')->implode('||');
            $summarize = Cache::remember('summarized_data_'.Carbon::now()->format('Ymd').'_'.md5($reviews), now()->addDay(), function () use ($reviews) {
                return $this->summarizer_service->summarize($reviews);
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully fetched',
                'data' => [
                    'total_rating_count' => $total_rating_count,
                    'top_reviews' => $top_reviews,
                    'rating_count' => $rating_count,
                    'rating' => $rating->latest()->paginate(10),
                    'summarized_review' => $summarize,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
