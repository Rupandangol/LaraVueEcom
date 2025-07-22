<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\RatingStoreRequest;
use App\Http\Requests\V1\ReviewStoreRequest;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    public function index($product_id)
    {
        $rating = Rating::where(['product_id' => $product_id])->with('user')->get();
        $avgRating = DB::select('SELECT AVG(rating) AS avgRating FROM ratings GROUP BY product_id HAVING product_id='.$product_id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'rating' => $rating,
                'avg' => $avgRating,
            ],
        ]);
    }

    public function ratingStore(RatingStoreRequest $request, $product_id)
    {
        // $r = Rating::where(['user_id' => Auth::user()->id, 'product_id' => $product_id]);
        // $rf = $r->first();
        // if ($r->exists() && $rf->rating != null) {
        //     return response()->json([
        //         'status' => 'failed',
        //         'message' => 'Rating already exists',
        //         'data' => []
        //     ]);
        // }
        $data = $request->all();
        $data['product_id'] = $product_id;
        $data['user_id'] = Auth::user()->id;
        $rating = Rating::updateOrCreate(['user_id' => Auth::user()->id, 'product_id' => $product_id], $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Submitted Successfully',
            'data' => $rating,
        ]);
    }

    public function reviewStore(ReviewStoreRequest $request, $product_id)
    {
        $r = Rating::where(['user_id' => Auth::user()->id, 'product_id' => $product_id]);
        $rf = $r->first();
        if (Rating::where(['user_id' => Auth::user()->id, 'product_id' => $product_id])->exists() && $rf->review != null) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Comment already exists',
                'data' => [],
            ]);
        }
        $data = $request->all();
        $data['product_id'] = (int) $product_id;
        $data['user_id'] = Auth::user()->id;
        $rating = Rating::updateOrCreate(['user_id' => Auth::user()->id, 'product_id' => $product_id], $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Submitted Successfully',
            'data' => $rating,
        ]);
    }
}
