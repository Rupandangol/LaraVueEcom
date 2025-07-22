<?php

namespace App\Docs;

class BlogAnnotation
{
    /**
     * @OA\Post(
     *     path="/api/blogs",
     *     summary="Create a new blog",
     *     tags={"Blogs"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="title",
     *                 type="string",
     *                 example="My New Blog Post"
     *             ),
     *             @OA\Property(
     *                 property="content",
     *                 type="string",
     *                 example="This is the content of the blog post."
     *             ),
     *             @OA\Property(
     *                 property="blog_category_id",
     *                 type="integer",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 example=123
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Blog successfully created",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Successfully Created"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=101
     *                 ),
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     example="My New Blog Post"
     *                 ),
     *                 @OA\Property(
     *                     property="content",
     *                     type="string",
     *                     example="This is the content of the blog post."
     *                 ),
     *                 @OA\Property(
     *                     property="blog_category",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="Tech"
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=123
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="John Doe"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function store()
    {
        // For Documentation
    }
}
