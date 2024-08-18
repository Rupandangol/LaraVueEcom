<?php

namespace App\Http\Controllers;

use App\Events\BlogCreatedEvent;
use App\Http\Requests\BlogStoreRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use function PHPSTORM_META\map;
use function PHPUnit\Framework\isNull;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $blog = Blog::paginate()->load(['blogCategory']);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $blog
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = 1; // Add auth users for this later
        try {
            $blog = Blog::create($validated);
            event(new BlogCreatedEvent($validated['title']));
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully Created',
            'data' => $blog->load(['user', 'blogCategory'])
        ]);
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
                'message' => $e->getMessage()
            ], $e->getCode());
        }

        return response()->json([
            'status' => 'success',
            'data' => $blog
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
            $blog = Blog::findIdSlug($id,$slug)->first();
            if (is_null($blog)) {
                throw new Exception('Blog Not Found', 404);
            }
            $blog->delete();
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Deleted Successfully',
        ]);
    }
}
