<?php

namespace App\Docs;

class GetAdminDataAnnotation 
{
    /**
     * 
     * @OA\Get(
     *     path="/api/V1/admin/data",
     *     summary="GetAdminData",
     *     tags={"Admin Data"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Admin Data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=101
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="admin"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="admin@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="updated_at",
     *                     type="timestamp",
     *                     example=null
     *                 ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="timestamp",
     *                     example=null
     *                 ),
     *             )
     *         )
     *     ),
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
    public function __invoke()
    {
        //For Documentation
    }
}
