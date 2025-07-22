<?php

namespace App\Http\Controllers;

use App\Events\BlogCreatedEvent;
use App\Http\Requests\BlogStoreRequest;
use App\Models\Blog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Point;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $blog = Blog::with(['blogCategory'])->paginate();
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $blog,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogStoreRequest $request)
    {
        $validated = $request->validated();
        $compliedData = $this->compliedData($validated);
        try {
            $blog = Blog::create($compliedData);
            event(new BlogCreatedEvent($compliedData['title']));
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully Created',
            'data' => $blog->load(['user', 'blogCategory']),
        ], 201);
    }

    public function compliedData($validatedData)
    {
        return [
            'title' => $validatedData['title'],
            'status' => $validatedData['status'],
            'blog_category_id' => $validatedData['blog_category_id'],
            'content' => $validatedData['content'],
            'written_from' => new Point($validatedData['latitude'], $validatedData['longitude']),
            'user_id' => 3, // Add auth users for this later
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show($id, $slug)
    {
        try {
            $blog = Blog::where(['id' => $id, 'slug' => $slug])->enabled()->first();
            if (is_null($blog)) {
                throw new \Exception('Blog doesnt exists', 404);
            }
            $blog->load(['blogCategory', 'user']);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }

        return response()->json([
            'status' => 'success',
            'data' => $blog,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, $slug)
    {
        try {
            $blog = Blog::findIdSlug($id, $slug)->first();
            if (is_null($blog)) {
                throw new Exception('Blog Not Found', 404);
            }
            $blog->delete();
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Deleted Successfully',
        ]);
    }

    public function blogAnalytics(Request $request)
    {
        try {
            $query = DB::table('blogs');
            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }
            if ($request->filled('comment_enabled')) {
                $query->where('comment_enabled', $request->get('comment_enabled'));
            }
            if ($request->filled('is_featured')) {
                $query->where('is_featured', $request->get('is_featured'));
            }
            if ($request->filled('title')) {
                $query->where('title', 'like', '%'.$request->get('title').'%');
            }
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->get('user_id'));
            }
            if ($request->filled('blog_category_id')) {
                $query->where('blog_category_id', $request->get('blog_category_id'));
            }
            if ($request->filled('date')) {
                $query->where('created_at', '>=', $request->get('date'));
            }
            // total_count
            // total_status_count
            // top_title
            // top_contributor
            // top_category

            $total_count = (clone $query)->count();
            $total_status_count = (clone $query)
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->orderBy('total', 'desc')
                ->get();

            $top_title = (clone $query)
                ->select('title', DB::raw('COUNT(*) as total'))
                ->groupBy('title')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get();

            $top_contributor = (clone $query)
                ->select('user_id', DB::raw('COUNT(*) as total'))
                ->groupBy('user_id')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get();
            $top_category = (clone $query)
                ->select('blog_category_id', 'blog_categories.blog_category', DB::raw('COUNT(*) as total'))
                ->leftJoin('blog_categories', 'blogs.blog_category_id', '=', 'blog_categories.id')
                ->groupBy('blog_category_id')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Fetched successfully',
                'data' => [
                    'total_count' => $total_count,
                    'total_status_count' => $total_status_count,
                    'top_contributor' => $top_contributor,
                    'top_category' => $top_category,
                    'top_title' => $top_title,
                    'blog' => $query->orderBy('id', 'desc')->paginate(10),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
