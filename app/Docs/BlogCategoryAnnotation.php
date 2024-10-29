<?php

namespace App\Docs;


class BlogCategoryAnnotation 
{
    /**
     * @OA\Get(
     *     path="/api/blog-categories",
     *     summary="Get a list of Blog Categories",
     *     tags={"Blog Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                    @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="enable"
     *             ),
     *             @OA\Property(
     *                 property="priority",
     *                 type="integer",
     *                 example=73091
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 example="Corrupti optio aliquam maiores facilis corporis fuga."
     *             ),
     *             @OA\Property(
     *                 property="blog_category",
     *                 type="string",
     *                 example="Eos"
     *             ),
     *             @OA\Property(
     *                 property="time_of_creation",
     *                 type="string",
     *                 example="9 seconds ago"
     *             ),
     *             @OA\Property(
     *                 property="random",
     *                 type="string",
     *                 example="0hSp5ALxdTspNyet"
     *             ),
     *             @OA\Property(
     *                 property="blog_count",
     *                 type="integer",
     *                 example=0
     *             )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     )
     * )
     */
    public function index()
    {
        //For Documentation
    }

    /**
     * @OA\Get(
     *     path="/api/blog-categories/{id}",
     *     summary="Get details of a Blog Category",
     *     tags={"Blog Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the blog category",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="enable"
     *                 ),
     *                 @OA\Property(
     *                     property="priority",
     *                     type="integer",
     *                     example=73091
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     example="Corrupti optio aliquam maiores facilis corporis fuga."
     *                 ),
     *                 @OA\Property(
     *                     property="blog_category",
     *                     type="string",
     *                     example="Eos"
     *                 ),
     *                 @OA\Property(
     *                     property="time_of_creation",
     *                     type="string",
     *                     example="1 day ago"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog category not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="error"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="No query results for model [BlogCategory]."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     )
     * )
     */
    public function show()
    {
        //For Documentation
    }
}
